<?php

namespace Tests\Feature\Reports;

use App\Models\BusinessProfile;
use App\Models\Customer;
use App\Models\LedgerEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BalanceSheetTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_balance_sheet_index(): void
    {
        $user = User::factory()->create();
        $business = BusinessProfile::create([
            'name' => 'Test Business',
            'business_type_id' => 1,
        ]);
        $customer = Customer::create([
            'name' => 'John Doe',
            'party_type' => 'customer',
        ]);
        $customer->businessProfiles()->attach($business->id);

        // Create a ledger entry
        LedgerEntry::create([
            'transaction_id' => null,
            'business_profile_id' => $business->id,
            'customer_id' => $customer->id,
            'account_head' => 'receivable',
            'debit' => 1000,
            'credit' => 0,
            'running_balance' => 1000,
            'date' => now(),
            'description' => 'Test entry',
        ]);

        $response = $this->actingAs($user)
            ->withSession(['active_business_id' => $business->id])
            ->get(route('reports.balance-sheet'));

        $response->assertOk();
        $response->assertSee('John Doe');
        $response->assertSee('₹1,000');
    }

    public function test_user_can_view_balance_sheet_customer_detail(): void
    {
        $user = User::factory()->create();
        $business = BusinessProfile::create([
            'name' => 'Test Business',
            'business_type_id' => 1,
        ]);
        $customer = Customer::create([
            'name' => 'John Doe',
            'party_type' => 'customer',
        ]);
        $customer->businessProfiles()->attach($business->id);

        // Create a ledger entry
        LedgerEntry::create([
            'transaction_id' => null,
            'business_profile_id' => $business->id,
            'customer_id' => $customer->id,
            'account_head' => 'receivable',
            'debit' => 1000,
            'credit' => 0,
            'running_balance' => 1000,
            'date' => now(),
            'description' => 'Test entry',
        ]);

        $response = $this->actingAs($user)
            ->withSession(['active_business_id' => $business->id])
            ->get(route('reports.balance-sheet.customer', $customer));

        $response->assertOk();
        $response->assertSee('John Doe');
        $response->assertSee('Test entry');
        $response->assertSee('₹1,000');
    }
}
