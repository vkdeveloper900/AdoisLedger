<?php

namespace App\Http\Controllers;

use App\Models\BusinessProfile;

class EnterShopController extends Controller
{
    public function enter(BusinessProfile $businessProfile)
    {
        session(['active_business_id' => $businessProfile->id]);
        return redirect()->route('dashboard')->with('success', "Entered {$businessProfile->name}");
    }

    public function exit()
    {
        session()->forget('active_business_id');
        return redirect()->route('dashboard');
    }
}
