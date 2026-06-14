<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        $stats = [
            'total'      => Company::count(),
            'trial'      => Company::where('subscribed', false)->where('trial_ends_at', '>', now())->count(),
            'subscribed' => Company::where('subscribed', true)->count(),
            'expired'    => Company::where('subscribed', false)->where('trial_ends_at', '<=', now())->count(),
        ];

        $recent = Company::with('users')->latest()->take(8)->get();
        $expiringSoon = Company::where('subscribed', false)
            ->where('trial_ends_at', '>', now())
            ->where('trial_ends_at', '<=', now()->addDays(7))
            ->orderBy('trial_ends_at')
            ->get();

        return view('admin.dashboard', compact('stats', 'recent', 'expiringSoon'));
    }

    // All companies list
    public function companies(Request $request)
    {
        $companies = Company::with('users')
            ->when($request->q, fn ($q, $term) =>
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('phone', 'like', "%{$term}%")
                  ->orWhere('city', 'like', "%{$term}%")
            )
            ->when($request->status === 'trial',      fn ($q) => $q->where('subscribed', false)->where('trial_ends_at', '>', now()))
            ->when($request->status === 'subscribed', fn ($q) => $q->where('subscribed', true))
            ->when($request->status === 'expired',    fn ($q) => $q->where('subscribed', false)->where('trial_ends_at', '<=', now()))
            ->latest()
            ->paginate(20);

        return view('admin.companies', compact('companies'));
    }

    // Company detail page
    public function show(Company $company)
    {
        $company->load('users');
        return view('admin.company-show', compact('company'));
    }

    // Activate subscription (manually after payment received)
    public function activate(Request $request, Company $company)
    {
        $data = $request->validate([
            'plan'     => 'required|in:monthly,yearly',
            'notes'    => 'nullable|string|max:500',
        ]);

        $ends = $data['plan'] === 'yearly'
            ? now()->addYear()
            : now()->addMonth();

        $company->update([
            'subscribed'           => true,
            'subscribed_at'        => now(),
            'subscription_ends_at' => $ends,
            'subscription_plan'    => $data['plan'],
            'admin_notes'          => $data['notes']
                ? ($company->admin_notes ? $company->admin_notes . "\n" : '') . now()->format('d M Y') . ': ' . $data['notes']
                : $company->admin_notes,
        ]);

        return back()->with('success', "{$company->name} activated on {$data['plan']} plan until " . $ends->format('d M Y') . ".");
    }

    // Extend trial
    public function extendTrial(Request $request, Company $company)
    {
        $data = $request->validate([
            'days'  => 'required|integer|min:1|max:90',
            'notes' => 'nullable|string|max:500',
        ]);

        $current = $company->trial_ends_at && $company->trial_ends_at->isFuture()
            ? $company->trial_ends_at
            : now();

        $newEnd = $current->addDays((int) $data['days']);

        $company->update([
            'trial_ends_at' => $newEnd,
            'subscribed'    => false,
            'admin_notes'   => $data['notes']
                ? ($company->admin_notes ? $company->admin_notes . "\n" : '') . now()->format('d M Y') . ': Trial extended ' . $data['days'] . ' days. ' . $data['notes']
                : $company->admin_notes,
        ]);

        return back()->with('success', "Trial extended to " . $newEnd->format('d M Y') . ".");
    }

    // Deactivate / suspend
    public function deactivate(Request $request, Company $company)
    {
        $data = $request->validate(['notes' => 'nullable|string|max:500']);

        $company->update([
            'subscribed'    => false,
            'trial_ends_at' => now()->subDay(), // expire immediately
            'admin_notes'   => ($company->admin_notes ? $company->admin_notes . "\n" : '') . now()->format('d M Y') . ': Deactivated. ' . ($data['notes'] ?? ''),
        ]);

        return back()->with('success', "{$company->name} has been deactivated.");
    }

    // Save admin notes
    public function saveNotes(Request $request, Company $company)
    {
        $data = $request->validate(['admin_notes' => 'nullable|string|max:2000']);
        $company->update(['admin_notes' => $data['admin_notes']]);

        return back()->with('success', 'Notes saved.');
    }
}
