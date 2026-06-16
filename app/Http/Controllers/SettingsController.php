<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $company = Auth::user()->company;
        return view('settings.index', compact('company'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:120',
            'phone'      => 'required|string|max:20',
            'gst_number' => 'nullable|string|max:20',
            'license_no' => 'nullable|string|max:60',
            'address'    => 'nullable|string|max:255',
            'city'       => 'nullable|string|max:60',
            'state'      => 'nullable|string|max:60',
        ]);

        Auth::user()->company->update($data);

        return back()->with('success', 'Company details updated.');
    }
}
