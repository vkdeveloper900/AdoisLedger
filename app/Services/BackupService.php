<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use ZipArchive;

class BackupService
{
    private string $backupDir;

    public function __construct()
    {
        $this->backupDir = storage_path('app/backups');
    }

    public function create(): string
    {
        if (!is_dir($this->backupDir)) {
            mkdir($this->backupDir, 0755, true);
        }

        $filename = 'adois_backup_' . now()->format('Y-m-d_His') . '.zip';
        $zipPath  = $this->backupDir . '/' . $filename;

        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // Database dump
        $sql = $this->dumpDatabase();
        $zip->addFromString('database.sql', $sql);

        // Uploaded files (avatars, profile images)
        $storagePath = storage_path('app/public');
        if (is_dir($storagePath)) {
            $this->addDirectoryToZip($zip, $storagePath, 'storage/app/public');
        }

        // Backup metadata
        $zip->addFromString('backup_info.json', json_encode([
            'created_at'  => now()->toIso8601String(),
            'db_driver'   => config('database.default'),
            'db_name'     => config('database.connections.' . config('database.default') . '.database'),
            'app_url'     => config('app.url'),
            'version'     => '1.0',
        ], JSON_PRETTY_PRINT));

        $zip->close();

        return $filename;
    }

    public function list(): array
    {
        if (!is_dir($this->backupDir)) {
            return [];
        }

        $files = glob($this->backupDir . '/adois_backup_*.zip');
        if (!$files) {
            return [];
        }

        $backups = [];
        foreach ($files as $file) {
            $backups[] = [
                'filename'   => basename($file),
                'size'       => filesize($file),
                'size_human' => $this->formatBytes(filesize($file)),
                'created_at' => \Carbon\Carbon::createFromTimestamp(filemtime($file), config('app.timezone')),
                'path'       => $file,
            ];
        }

        usort($backups, fn($a, $b) => $b['created_at']->timestamp - $a['created_at']->timestamp);

        return $backups;
    }

    public function delete(string $filename): void
    {
        $path = $this->backupDir . '/' . basename($filename);
        if (file_exists($path)) {
            unlink($path);
        }
    }

    public function path(string $filename): string
    {
        return $this->backupDir . '/' . basename($filename);
    }

    public function restore(string $zipPath): array
    {
        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            throw new \RuntimeException('Cannot open backup ZIP file.');
        }

        // Read metadata
        $meta = [];
        $metaJson = $zip->getFromName('backup_info.json');
        if ($metaJson) {
            $meta = json_decode($metaJson, true);
        }

        // Extract and run database.sql
        $sql = $zip->getFromName('database.sql');
        if (!$sql) {
            $zip->close();
            throw new \RuntimeException('No database.sql found in backup.');
        }

        $driver = config('database.default');

        if ($driver === 'sqlite') {
            $this->restoreSqlite($sql);
        } else {
            $this->restoreMysql($sql);
        }

        // Restore uploaded files
        $storagePath = storage_path('app/public');
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            if (str_starts_with($name, 'storage/app/public/')) {
                $relative  = substr($name, strlen('storage/app/public/'));
                $destPath  = $storagePath . '/' . $relative;
                $destDir   = dirname($destPath);
                if (!is_dir($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                if (!str_ends_with($name, '/')) {
                    file_put_contents($destPath, $zip->getFromIndex($i));
                }
            }
        }

        $zip->close();

        return $meta;
    }

    // ── Private helpers ──────────────────────────────────────────────────────

    private function dumpDatabase(): string
    {
        $driver = config('database.default');

        return $driver === 'sqlite'
            ? $this->dumpSqlite()
            : $this->dumpMysql();
    }

    private function dumpSqlite(): string
    {
        $dbPath = database_path('database.sqlite');
        if (!file_exists($dbPath)) {
            return "-- SQLite database file not found\n";
        }

        $pdo    = DB::connection()->getPdo();
        $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'")->fetchAll(\PDO::FETCH_COLUMN);

        return $this->buildSqlDump($pdo, $tables, 'sqlite');
    }

    private function dumpMysql(): string
    {
        $pdo    = DB::connection()->getPdo();
        $tables = $pdo->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);

        return $this->buildSqlDump($pdo, $tables, 'mysql');
    }

    private function buildSqlDump(\PDO $pdo, array $tables, string $driver): string
    {
        $sql = "-- AdoisLedger Database Backup\n";
        $sql .= "-- Generated: " . now()->toIso8601String() . "\n";
        $sql .= "-- Driver: {$driver}\n\n";

        if ($driver === 'mysql') {
            $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
        }

        foreach ($tables as $table) {
            // Schema
            if ($driver === 'mysql') {
                $createRow = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch(\PDO::FETCH_ASSOC);
                $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";
                $sql .= $createRow['Create Table'] . ";\n\n";
            } else {
                $createRow = $pdo->query("SELECT sql FROM sqlite_master WHERE type='table' AND name='{$table}'")->fetch(\PDO::FETCH_ASSOC);
                $sql .= "DROP TABLE IF EXISTS \"{$table}\";\n";
                $sql .= $createRow['sql'] . ";\n\n";
            }

            // Data
            $rows = $pdo->query("SELECT * FROM `{$table}`")->fetchAll(\PDO::FETCH_ASSOC);
            if ($rows) {
                $columns = array_keys($rows[0]);
                $colList = implode(', ', array_map(fn($c) => "`{$c}`", $columns));

                foreach (array_chunk($rows, 100) as $chunk) {
                    $sql .= "INSERT INTO `{$table}` ({$colList}) VALUES\n";
                    $valueRows = [];
                    foreach ($chunk as $row) {
                        $values = array_map(function ($val) use ($pdo) {
                            if ($val === null) return 'NULL';
                            return $pdo->quote((string) $val);
                        }, array_values($row));
                        $valueRows[] = '(' . implode(', ', $values) . ')';
                    }
                    $sql .= implode(",\n", $valueRows) . ";\n";
                }
                $sql .= "\n";
            }
        }

        if ($driver === 'mysql') {
            $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
        }

        return $sql;
    }

    private function restoreMysql(string $sql): void
    {
        DB::unprepared("SET FOREIGN_KEY_CHECKS=0;");
        // Split on semicolons but ignore those inside strings
        $statements = $this->splitSql($sql);
        foreach ($statements as $statement) {
            $s = trim($statement);
            if ($s && !str_starts_with($s, '--')) {
                DB::unprepared($s);
            }
        }
        DB::unprepared("SET FOREIGN_KEY_CHECKS=1;");
    }

    private function restoreSqlite(string $sql): void
    {
        $statements = $this->splitSql($sql);
        foreach ($statements as $statement) {
            $s = trim($statement);
            if ($s && !str_starts_with($s, '--')) {
                DB::unprepared($s);
            }
        }
    }

    private function splitSql(string $sql): array
    {
        // Split by semicolons not inside quotes
        $statements = [];
        $current    = '';
        $inString   = false;
        $stringChar = '';
        $len        = strlen($sql);

        for ($i = 0; $i < $len; $i++) {
            $char = $sql[$i];

            if (!$inString && ($char === "'" || $char === '"')) {
                $inString   = true;
                $stringChar = $char;
            } elseif ($inString && $char === $stringChar && ($i === 0 || $sql[$i - 1] !== '\\')) {
                $inString = false;
            }

            if (!$inString && $char === ';') {
                $statements[] = $current;
                $current      = '';
            } else {
                $current .= $char;
            }
        }

        if (trim($current)) {
            $statements[] = $current;
        }

        return $statements;
    }

    private function addDirectoryToZip(ZipArchive $zip, string $dir, string $zipPrefix): void
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            $filePath   = $file->getRealPath();
            $relativePath = $zipPrefix . '/' . str_replace('\\', '/', substr($filePath, strlen($dir) + 1));

            if ($file->isDir()) {
                $zip->addEmptyDir($relativePath);
            } else {
                $zip->addFile($filePath, $relativePath);
            }
        }
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }
}
