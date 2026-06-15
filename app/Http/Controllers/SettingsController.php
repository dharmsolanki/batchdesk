<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    public function uploadLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $company = Auth::user()->company;

        // Delete old logo
        if ($company->logo_path) {
            Storage::disk('public')->delete($company->logo_path);
        }

        $path = $request->file('logo')->store('logos', 'public');
        $company->update(['logo_path' => $path]);

        return back()->with('success', 'Logo uploaded successfully.');
    }

    public function removeLogo()
    {
        $company = Auth::user()->company;

        if ($company->logo_path) {
            Storage::disk('public')->delete($company->logo_path);
            $company->update(['logo_path' => null]);
        }

        return back()->with('success', 'Logo removed.');
    }
}
