<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\StoreBusinessProfileRequest;
use App\Models\BusinessProfile;

class BusinessProfileController extends Controller
{
    public function index()
    {
        $profiles = BusinessProfile::latest()->get();
        return view('settings.business-profiles.index', compact('profiles'));
    }

    public function create()
    {
        $businessTypes = config('constants.business_types');
        return view('settings.business-profiles.form', compact('businessTypes'));
    }

    public function store(StoreBusinessProfileRequest $request)
    {
        BusinessProfile::create($request->validated());

        return redirect()->route('settings.business-profiles.index')
            ->with('success', 'Business profile created.');
    }

    public function show(BusinessProfile $businessProfile)
    {
        return view('settings.business-profiles.show', compact('businessProfile'));
    }

    public function edit(BusinessProfile $businessProfile)
    {
        $businessTypes = config('constants.business_types');
        return view('settings.business-profiles.form', compact('businessProfile', 'businessTypes'));
    }

    public function update(StoreBusinessProfileRequest $request, BusinessProfile $businessProfile)
    {
        $businessProfile->update($request->validated());

        return redirect()->route('settings.business-profiles.index')
            ->with('success', 'Business profile updated.');
    }

    public function destroy(BusinessProfile $businessProfile)
    {
        $businessProfile->delete();

        return redirect()->route('settings.business-profiles.index')
            ->with('success', 'Business profile deleted.');
    }
}
