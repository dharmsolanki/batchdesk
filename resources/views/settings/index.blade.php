@extends('layouts.app')
@section('title', 'Settings — BatchDesk')
@section('content')

<h1 class="font-bold text-2xl tracking-tight mb-4">Company Settings</h1>

<div class="space-y-4">

    {{-- Logo --}}
    <div class="card p-5">
        <div class="section-title mb-3">Company Logo</div>
        <p class="text-sm text-muted mb-4">Your logo appears on the COA letterhead. Use PNG or JPG, recommended size 300×80px.</p>

        @if ($company->logo_path)
            <div class="flex items-center gap-4 mb-4">
                <img src="/public/storage/{{ $company->logo_path }}"
                     alt="Company logo"
                     class="h-14 object-contain border border-line rounded-lg p-2 bg-white">
                <form method="POST" action="{{ route('settings.logo.remove') }}"
                      onsubmit="return confirm('Remove logo?')">
                    @csrf @method('DELETE')
                    <button class="text-danger text-sm font-semibold">Remove logo</button>
                </form>
            </div>
        @endif

        <form method="POST" action="{{ route('settings.logo') }}" enctype="multipart/form-data"
              class="flex items-center gap-3">
            @csrf
            <input type="file" name="logo" accept="image/png,image/jpeg"
                   class="field flex-1 text-sm py-2">
            <button class="btn-accent px-5 py-2 text-sm">Upload</button>
        </form>
    </div>

    {{-- Company details --}}
    <div class="card p-5">
        <div class="section-title mb-3">Company Details</div>
        <form method="POST" action="{{ route('settings.update') }}" class="space-y-3">
            @csrf
            <div>
                <label class="label">Company name *</label>
                <input name="name" value="{{ $company->name }}" required class="field">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="label">Phone *</label>
                    <input name="phone" value="{{ $company->phone }}" required class="field">
                </div>
                <div>
                    <label class="label">City</label>
                    <input name="city" value="{{ $company->city }}" class="field">
                </div>
            </div>
            <div>
                <label class="label">Address</label>
                <input name="address" value="{{ $company->address }}" class="field">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="label">GSTIN</label>
                    <input name="gst_number" value="{{ $company->gst_number }}" class="field">
                </div>
                <div>
                    <label class="label">License no. (FSSAI / Drug)</label>
                    <input name="license_no" value="{{ $company->license_no }}" class="field">
                </div>
            </div>
            <button class="btn-primary w-full py-2.5">Save changes</button>
        </form>
    </div>

    {{-- Subscription info --}}
    <div class="card p-5">
        <div class="section-title mb-3">Subscription</div>
        <div class="text-sm space-y-1.5">
            <div class="flex justify-between">
                <span class="text-muted">Status</span>
                <span class="font-semibold
                    @if($company->statusLabel() === 'Subscribed') text-pass
                    @elseif($company->statusLabel() === 'Trial') text-brand
                    @else text-danger @endif">
                    {{ $company->statusLabel() }}
                </span>
            </div>
            @if ($company->isTrialActive())
            <div class="flex justify-between">
                <span class="text-muted">Trial ends</span>
                <span class="font-semibold">{{ $company->trial_ends_at->format('d M Y') }} ({{ $company->trialDaysLeft() }} days left)</span>
            </div>
            @endif
            @if ($company->subscribed)
            <div class="flex justify-between">
                <span class="text-muted">Plan</span>
                <span class="font-semibold capitalize">{{ $company->subscription_plan }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-muted">Renews on</span>
                <span class="font-semibold">{{ $company->subscription_ends_at?->format('d M Y') }}</span>
            </div>
            @endif
        </div>
        @if (!$company->subscribed)
        <a href="https://wa.me/919723720728?text=Hi,+I+want+to+subscribe+to+BatchDesk+for+{{ urlencode($company->name) }}"
           target="_blank"
           class="btn-accent block text-center w-full py-2.5 mt-3 text-sm">
            Contact us to subscribe
        </a>
        @endif
    </div>

</div>
@endsection
