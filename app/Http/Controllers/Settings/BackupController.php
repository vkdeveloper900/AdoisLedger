<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Services\BackupService;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    public function __construct(private BackupService $backup) {}

    public function index()
    {
        $backups = $this->backup->list();
        return view('settings.backup.index', compact('backups'));
    }

    public function store()
    {
        try {
            $filename = $this->backup->create();
            return back()->with('success', "Backup created: {$filename}");
        } catch (\Throwable $e) {
            return back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function download(string $filename)
    {
        $path = $this->backup->path($filename);

        if (!file_exists($path)) {
            return back()->with('error', 'Backup file not found.');
        }

        return response()->download($path, $filename, [
            'Content-Type' => 'application/zip',
        ]);
    }

    public function destroy(string $filename)
    {
        $this->backup->delete($filename);
        return back()->with('success', 'Backup deleted.');
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => ['required', 'file', 'mimes:zip', 'max:102400'],
        ]);

        try {
            $uploaded = $request->file('backup_file');
            $tmpPath  = $uploaded->store('backups/tmp', 'local');
            $fullPath = storage_path('app/' . $tmpPath);

            $meta = $this->backup->restore($fullPath);

            @unlink($fullPath);

            $createdAt = $meta['created_at'] ?? 'unknown';
            return back()->with('success', "Restore complete. Backup was created on: {$createdAt}");
        } catch (\Throwable $e) {
            return back()->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }
}
