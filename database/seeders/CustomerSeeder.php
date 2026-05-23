<?php

namespace Database\Seeders;

use App\Models\BusinessProfile;
use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['name' => 'Ramesh Kumar Sharma',    'mobile' => '9876543201', 'mobile2' => '9876543211', 'city' => 'Sadri'],
            ['name' => 'Suresh Chand Prajapat',  'mobile' => '9876543202', 'mobile2' => null,          'city' => 'Pali'],
            ['name' => 'Mahesh Lal Meena',       'mobile' => '9876543203', 'mobile2' => '9876543213', 'city' => 'Jodhpur'],
            ['name' => 'Dinesh Kumar Jat',       'mobile' => '9876543204', 'mobile2' => null,          'city' => 'Sadri'],
            ['name' => 'Rajesh Singh Rathore',   'mobile' => '9876543205', 'mobile2' => '9876543215', 'city' => 'Pali'],
            ['name' => 'Naresh Patel',           'mobile' => '9876543206', 'mobile2' => null,          'city' => 'Sirohi'],
            ['name' => 'Ganesh Prasad Gupta',    'mobile' => '9876543207', 'mobile2' => '9876543217', 'city' => 'Sadri'],
            ['name' => 'Umesh Chand Bishnoi',    'mobile' => '9876543208', 'mobile2' => null,          'city' => 'Barmer'],
            ['name' => 'Kamlesh Kumari Devi',    'mobile' => '9876543209', 'mobile2' => '9876543219', 'city' => 'Pali'],
            ['name' => 'Santosh Kumar Yadav',    'mobile' => '9876543210', 'mobile2' => null,          'city' => 'Jodhpur'],
            ['name' => 'Prakash Chand Soni',     'mobile' => '9876543221', 'mobile2' => '9876543231', 'city' => 'Sadri'],
            ['name' => 'Mukesh Kumar Verma',     'mobile' => '9876543222', 'mobile2' => null,          'city' => 'Pali'],
            ['name' => 'Rakesh Lal Kumawat',     'mobile' => '9876543223', 'mobile2' => '9876543233', 'city' => 'Sirohi'],
            ['name' => 'Lokesh Prajapat',        'mobile' => '9876543224', 'mobile2' => null,          'city' => 'Sadri'],
            ['name' => 'Hitesh Kumar Nayak',     'mobile' => '9876543225', 'mobile2' => '9876543235', 'city' => 'Jodhpur'],
            ['name' => 'Viresh Chand Tak',       'mobile' => '9876543226', 'mobile2' => null,          'city' => 'Pali'],
            ['name' => 'Brijesh Kumar Mali',     'mobile' => '9876543227', 'mobile2' => '9876543237', 'city' => 'Sadri'],
            ['name' => 'Yogesh Prasad Sharma',   'mobile' => '9876543228', 'mobile2' => null,          'city' => 'Barmer'],
            ['name' => 'Nilesh Kumar Suthar',    'mobile' => '9876543229', 'mobile2' => '9876543239', 'city' => 'Pali'],
            ['name' => 'Jitesh Lal Darji',       'mobile' => '9876543230', 'mobile2' => null,          'city' => 'Sadri'],
        ];

        $profiles = BusinessProfile::all();

        foreach ($customers as $i => $data) {
            $customer = Customer::create([
                'name'       => $data['name'],
                'party_type' => $i < 7 ? 'both' : ($i < 14 ? 'vendor' : 'customer'),
                'mobile'     => $data['mobile'],
                'mobile2'    => $data['mobile2'],
                'email'      => strtolower(str_replace(' ', '.', $data['name'])) . '@example.in',
                'address'    => $data['city'] . ', Pali, Rajasthan',
            ]);

            // Assign to all 3 businesses (round-robin ensures spread)
            // First 7 → all 3, next 7 → dairy+general, last 6 → general+construction
            if ($i < 7) {
                $customer->businessProfiles()->attach($profiles->pluck('id'));
            } elseif ($i < 14) {
                $customer->businessProfiles()->attach($profiles->whereIn('business_type_id', [1, 2])->pluck('id'));
            } else {
                $customer->businessProfiles()->attach($profiles->whereIn('business_type_id', [1, 3])->pluck('id'));
            }
        }
    }
}
