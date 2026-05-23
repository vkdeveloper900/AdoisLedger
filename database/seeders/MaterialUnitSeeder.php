<?php

namespace Database\Seeders;

use App\Models\BusinessProfile;
use App\Models\Material;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class MaterialUnitSeeder extends Seeder
{
    public function run(): void
    {
        $construction = BusinessProfile::where('business_type_id', 3)->first();
        if (! $construction) return;

        $materials = [
            'Reti', 'Bricks', 'Concrete', 'Cement', 'Salt',
            'Gravel', 'Sand', 'Stone', 'JCB',
        ];

        $units = [
            'kg', 'ton', 'bag', 'piece', 'trip',
            'hour', 'liter', 'meter', 'foot', 'CFT',
        ];

        foreach ($materials as $name) {
            Material::firstOrCreate([
                'business_profile_id' => $construction->id,
                'name'                => $name,
            ], ['is_active' => true]);
        }

        foreach ($units as $name) {
            Unit::firstOrCreate([
                'business_profile_id' => $construction->id,
                'name'                => $name,
            ], ['is_active' => true]);
        }
    }
}
