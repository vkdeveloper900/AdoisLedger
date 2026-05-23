<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Unit;
use Illuminate\Http\Request;

class MaterialUnitController extends Controller
{
    public function index()
    {
        $bizId     = session('active_business_id');
        $materials = Material::where('business_profile_id', $bizId)->orderBy('name')->get();
        $units     = Unit::where('business_profile_id', $bizId)->orderBy('name')->get();

        return view('settings.material-units', compact('materials', 'units'));
    }

    public function storeMaterial(Request $request)
    {
        $request->validate(['name' => ['required', 'string', 'max:100']]);
        $bizId = session('active_business_id');

        Material::firstOrCreate(
            ['business_profile_id' => $bizId, 'name' => trim($request->name)],
            ['is_active' => true]
        );

        return back()->with('success', 'Material added.');
    }

    public function destroyMaterial(Material $material)
    {
        $material->delete();
        return back()->with('success', 'Material deleted.');
    }

    public function storeUnit(Request $request)
    {
        $request->validate(['name' => ['required', 'string', 'max:50']]);
        $bizId = session('active_business_id');

        Unit::firstOrCreate(
            ['business_profile_id' => $bizId, 'name' => trim($request->name)],
            ['is_active' => true]
        );

        return back()->with('success', 'Unit added.');
    }

    public function destroyUnit(Unit $unit)
    {
        $unit->delete();
        return back()->with('success', 'Unit deleted.');
    }
}
