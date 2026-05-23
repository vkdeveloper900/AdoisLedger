<?php

namespace Database\Seeders;

use App\Models\BusinessProfile;
use Illuminate\Database\Seeder;

class BusinessProfileSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = [
            [
                'name'             => 'Ranisa Dairy',
                'business_type_id' => 2,
                'email'            => 'dairy@ranisa.in',
                'phone'            => '9414000001',
                'manager_name'     => 'Ramesh Prajapat',
                'address'          => 'Near Panchayat Bhawan, Sadri',
                'city'             => 'Sadri',
                'country'          => 'India',
            ],
            [
                'name'             => 'Ranisa General Store',
                'business_type_id' => 1,
                'email'            => 'general@ranisa.in',
                'phone'            => '9414000002',
                'manager_name'     => 'Suresh Prajapat',
                'address'          => 'Main Market, Sadri, Pali',
                'city'             => 'Pali',
                'country'          => 'India',
            ],
            [
                'name'             => 'Ranisa Construction Materials',
                'business_type_id' => 3,
                'email'            => 'construction@ranisa.in',
                'phone'            => '9414000003',
                'manager_name'     => 'Mahesh Prajapat',
                'address'          => 'Industrial Area, Sadri, Pali',
                'city'             => 'Pali',
                'country'          => 'India',
            ],
        ];

        foreach ($profiles as $profile) {
            BusinessProfile::create($profile);
        }
    }
}
