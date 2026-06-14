<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'company_name' => 'required|string|max:120',
            'phone'        => 'required|string|max:20',
            'gst_number'   => 'nullable|string|max:20',
            'license_no'   => 'nullable|string|max:60',
            'city'         => 'nullable|string|max:60',
            'name'         => 'required|string|max:120',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|string|min:6|confirmed',
        ]);

        $user = DB::transaction(function () use ($data) {
            $company = Company::create([
                'name'          => $data['company_name'],
                'phone'         => $data['phone'],
                'gst_number'    => $data['gst_number'] ?? null,
                'license_no'    => $data['license_no'] ?? null,
                'city'          => $data['city'] ?? null,
                'trial_ends_at' => now()->addDays(30), // 30-day trial starts now
                'subscribed'    => false,
            ]);

            return User::create([
                'company_id' => $company->id,
                'name'       => $data['name'],
                'email'      => $data['email'],
                'phone'      => $data['phone'],
                'password'   => Hash::make($data['password']),
                'role'       => 'owner',
                'is_admin'   => false,
            ]);
        });

        Auth::login($user);

        return redirect()->route('products.index')
            ->with('success', 'Company created. Your 30-day free trial has started. Start by adding a product and its specification.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['email' => 'Invalid email or password.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
