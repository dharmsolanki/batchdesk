@extends('admin.layout')
@section('title', $company->name)
@section('content')

<a href="{{ route('admin.companies') }}" class="text-brand text-sm font-semibold">← All companies</a>

<div class="grid grid-cols-3 gap-5 mt-4">

    {{-- Left: Company info --}}
    <div class="col-span-2 space-y-4">

        {{-- Header card --}}
        <div class="card p-5">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="font-bold text-xl">{{ $company->name }}</h2>
                    <div class="text-sm text-muted mt-0.5">
                        {{ $company->city }}{{ $company->gst_number ? ' · GSTIN: ' . $company->gst_number : '' }}
                        {{ $company->license_no ? ' · License: ' . $company->license_no : '' }}
                    </div>
                </div>
                @php $status = $company->statusLabel(); @endphp
                <span class="text-[11px] font-bold px-3 py-1.5 rounded border
                    @if($status === 'Subscribed') text-pass bg-emerald-50 border-emerald-200
                    @elseif($status === 'Trial') text-brand bg-cyan-50 border-cyan-200
                    @else text-danger bg-red-50 border-red-200 @endif">
                    {{ strtoupper($status) }}
                    @if($status === 'Trial') · {{ $company->trialDaysLeft() }} days left @endif
                </span>
            </div>

            <div class="grid grid-cols-3 gap-4 mt-4 text-sm">
                <div><span class="text-muted">Phone</span><div class="font-medium">{{ $company->phone }}</div></div>
                <div><span class="text-muted">Registered</span><div class="font-medium">{{ $company->created_at->format('d M Y') }}</div></div>
                <div><span class="text-muted">Trial ends</span><div class="font-medium font-mono">{{ $company->trial_ends_at?->format('d M Y') ?? '—' }}</div></div>
                <div><span class="text-muted">Plan</span><div class="font-medium capitalize">{{ $company->subscription_plan }}</div></div>
                <div><span class="text-muted">Subscribed on</span><div class="font-medium">{{ $company->subscribed_at?->format('d M Y') ?? '—' }}</div></div>
                <div><span class="text-muted">Subscription ends</span><div class="font-medium font-mono">{{ $company->subscription_ends_at?->format('d M Y') ?? '—' }}</div></div>
            </div>

            {{-- Users --}}
            <div class="mt-4 border-t border-line pt-4">
                <div class="text-xs font-semibold text-muted uppercase tracking-wide mb-2">Team members</div>
                @foreach ($company->users as $u)
                <div class="flex items-center justify-between py-1.5 text-sm">
                    <div>
                        <span class="font-medium">{{ $u->name }}</span>
                        <span class="text-muted ml-2">{{ $u->email }}</span>
                    </div>
                    <span class="text-[10px] font-bold text-muted uppercase">{{ $u->role }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Admin notes --}}
        <div class="card p-5">
            <div class="text-xs font-semibold text-muted uppercase tracking-wide mb-3">Admin notes</div>
            <form method="POST" action="{{ route('admin.companies.notes', $company) }}">
                @csrf
                <textarea name="admin_notes" rows="5" placeholder="Payment details, call notes, special arrangements..."
                    class="field w-full text-sm resize-none">{{ $company->admin_notes }}</textarea>
                <button class="btn btn-ghost mt-2 text-sm">Save notes</button>
            </form>
        </div>

    </div>

    {{-- Right: Actions --}}
    <div class="space-y-4">

        {{-- Activate subscription --}}
        <div class="card p-5">
            <div class="text-xs font-semibold text-muted uppercase tracking-wide mb-3">Activate subscription</div>
            <p class="text-[12px] text-muted mb-3">Use this after you receive payment (UPI/bank). The company gets full access immediately.</p>
            <form method="POST" action="{{ route('admin.companies.activate', $company) }}" class="space-y-2.5">
                @csrf
                <div>
                    <label class="text-xs font-medium text-muted block mb-1">Plan</label>
                    <select name="plan" class="field text-sm">
                        <option value="monthly">Monthly — ₹1,499</option>
                        <option value="yearly">Yearly — ₹14,999</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-medium text-muted block mb-1">Note (payment reference etc.)</label>
                    <input name="notes" class="field text-sm" placeholder="UPI ref: 4521XXXX">
                </div>
                <button class="btn btn-accent w-full">Activate now</button>
            </form>
        </div>

        {{-- Extend trial --}}
        <div class="card p-5">
            <div class="text-xs font-semibold text-muted uppercase tracking-wide mb-3">Extend trial</div>
            <p class="text-[12px] text-muted mb-3">Give more time to a company that needs it. Days are added to the current trial end date.</p>
            <form method="POST" action="{{ route('admin.companies.extend', $company) }}" class="space-y-2.5">
                @csrf
                <div>
                    <label class="text-xs font-medium text-muted block mb-1">Extra days</label>
                    <select name="days" class="field text-sm">
                        <option value="7">7 days</option>
                        <option value="14">14 days</option>
                        <option value="30" selected>30 days</option>
                        <option value="60">60 days</option>
                        <option value="90">90 days</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-medium text-muted block mb-1">Reason (optional)</label>
                    <input name="notes" class="field text-sm" placeholder="Requested more time to evaluate">
                </div>
                <button class="btn btn-ghost w-full">Extend trial</button>
            </form>
        </div>

        {{-- Deactivate --}}
        <div class="card p-5 border-red-200">
            <div class="text-xs font-semibold text-danger uppercase tracking-wide mb-3">Deactivate account</div>
            <p class="text-[12px] text-muted mb-3">Immediately blocks access. The company's data is preserved — they can re-activate later.</p>
            <form method="POST" action="{{ route('admin.companies.deactivate', $company) }}"
                  onsubmit="return confirm('Deactivate {{ $company->name }}? They will lose access immediately.')">
                @csrf
                <input name="notes" class="field text-sm mb-2.5" placeholder="Reason (optional)">
                <button class="btn btn-danger w-full">Deactivate</button>
            </form>
        </div>

    </div>
</div>
@endsection
