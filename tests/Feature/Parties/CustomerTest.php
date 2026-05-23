<?php

namespace Tests\Feature\Parties;

use App\Models\BusinessProfile;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_customer_via_standard_post(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('customers.store'), [
            'name' => 'Alice Smith',
            'party_type' => 'customer',
            'mobile' => '9999999999',
            'email' => 'alice@example.com',
        ]);

        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseHas('customers', [
            'name' => 'Alice Smith',
            'party_type' => 'customer',
            'mobile' => '9999999999',
            'email' => 'alice@example.com',
        ]);
    }

    public function test_user_can_create_customer_via_ajax_and_auto_assign_business(): void
    {
        $user = User::factory()->create();
        $business = BusinessProfile::create([
            'name' => 'Dairy Shop',
            'business_type_id' => 2,
        ]);

        $response = $this->actingAs($user)
            ->withSession(['active_business_id' => $business->id])
            ->postJson(route('customers.store'), [
                'name' => 'Bob Builder',
                'party_type' => 'vendor',
                'mobile' => '8888888888',
                'email' => 'bob@example.com',
            ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Customer created successfully.',
        ]);

        $customer = Customer::where('name', 'Bob Builder')->first();
        $this->assertNotNull($customer);
        $this->assertEquals('vendor', $customer->party_type);
        
        // Assert sync with business
        $this->assertTrue($customer->businessProfiles->contains($business->id));
    }
}
