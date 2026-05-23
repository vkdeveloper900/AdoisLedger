<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }

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

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $this->call([
            UserSeeder::class,
            BusinessProfileSeeder::class,
            CustomerSeeder::class,
            MaterialUnitSeeder::class,
        ]);
    }
}
