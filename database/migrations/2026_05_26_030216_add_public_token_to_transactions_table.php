<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->uuid('public_token')->nullable()->unique()->after('bill_number');
        });

        // Backfill existing rows with unique tokens
        DB::table('transactions')->whereNull('public_token')->orderBy('id')->each(function ($row) {
            DB::table('transactions')->where('id', $row->id)->update(['public_token' => Str::uuid()]);
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('public_token');
        });
    }
};
