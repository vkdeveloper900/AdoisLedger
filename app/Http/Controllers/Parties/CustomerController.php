<?php

namespace App\Http\Controllers\Parties;

use App\Http\Controllers\Controller;
use App\Http\Requests\Parties\StoreCustomerRequest;
use App\Models\BusinessProfile;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::with('businessProfiles');

        // Scope by active business unless "view all" is requested
        $activeProfile = session('active_business_id')
            ? \App\Models\BusinessProfile::find(session('active_business_id'))
            : null;

        $viewAll = $request->boolean('view_all');

        if ($activeProfile && ! $viewAll) {
            $query->whereHas('businessProfiles', fn($q) => $q->where('business_profiles.id', $activeProfile->id));
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($profileId = $request->input('business_profile_id')) {
            $query->whereHas('businessProfiles', fn($q) => $q->where('business_profiles.id', $profileId));
        }

        $customers = $query->latest()->paginate(20)->withQueryString();
        $businessProfiles = BusinessProfile::orderBy('name')->get();

        return view('parties.customers.index', compact('customers', 'businessProfiles', 'activeProfile', 'viewAll'));
    }

    public function create()
    {
        return view('parties.customers.form');
    }

    public function store(StoreCustomerRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('customers', 'public');
        }

        $customer = Customer::create($data);

        // Sync with active business profile if session is active
        if ($activeBusinessId = session('active_business_id')) {
            $customer->businessProfiles()->syncWithoutDetaching([$activeBusinessId]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully.',
                'customer' => $customer,
            ]);
        }

        return redirect()->route('customers.index')->with('success', 'Customer created.');
    }

    public function show(Customer $customer)
    {
        return view('parties.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('parties.customers.form', compact('customer'));
    }

    public function update(StoreCustomerRequest $request, Customer $customer)
    {
        $data = $request->validated();

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('customers', 'public');
        }

        $customer->update($data);

        return redirect()->route('customers.index')->with('success', 'Customer updated.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted.');
    }

    public function assignBusiness(Request $request, Customer $customer)
    {
        $request->validate([
            'business_profile_ids'   => ['nullable', 'array'],
            'business_profile_ids.*' => ['exists:business_profiles,id'],
        ]);

        $customer->businessProfiles()->sync($request->input('business_profile_ids', []));

        return back()->with('success', 'Businesses updated for customer.');
    }
}
