<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Wipe in correct FK order
        DB::statement('PRAGMA foreign_keys = OFF');

        DB::table('ledger_entries')->truncate();
        DB::table('transaction_items')->truncate();
        DB::table('transactions')->truncate();
        DB::table('payments')->truncate();
        DB::table('business_profile_customer')->truncate();
        DB::table('customers')->truncate();
        DB::table('materials')->truncate();
        DB::table('units')->truncate();
        DB::table('business_profiles')->truncate();
        DB::table('users')->truncate();

        DB::statement('PRAGMA foreign_keys = ON');

        $this->call([
            UserSeeder::class,
            BusinessProfileSeeder::class,
            CustomerSeeder::class,
            MaterialUnitSeeder::class,
        ]);
    }
}
