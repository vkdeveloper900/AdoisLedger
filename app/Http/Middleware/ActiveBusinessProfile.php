<?php

namespace App\Http\Middleware;

use App\Models\BusinessProfile;
use Closure;
use Illuminate\Http\Request;

class ActiveBusinessProfile
{
    public function handle(Request $request, Closure $next)
    {
        $activeProfile = null;

        if ($id = session('active_business_id')) {
            $activeProfile = BusinessProfile::find($id);

            // Clear stale session if profile was deleted
            if (! $activeProfile) {
                session()->forget('active_business_id');
            }
        }

        view()->share('activeProfile', $activeProfile);

        return $next($request);
    }
}
