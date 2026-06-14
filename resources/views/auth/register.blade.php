@extends('layouts.app')
@section('title', 'Create your company — BatchDesk')
@section('content')
<div class="max-w-md mx-auto">
    <h1 class="font-bold text-2xl tracking-tight mb-1">Create your company account</h1>
    <p class="text-sm text-muted mb-5">Batch production, GST invoicing and QR-verified Certificates of Analysis — in one place.</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-4 card p-5">
        @csrf
        <div class="section-title">Company details</div>
        <div>
            <label class="label">Company name *</label>
            <input name="company_name" value="{{ old('company_name') }}" required class="field" placeholder="Shree Chem Industries Pvt. Ltd.">
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="label">Phone *</label>
                <input name="phone" value="{{ old('phone') }}" required class="field">
            </div>
            <div>
                <label class="label">City</label>
                <input name="city" value="{{ old('city') }}" class="field" placeholder="Ahmedabad">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="label">GSTIN</label>
                <input name="gst_number" value="{{ old('gst_number') }}" class="field">
            </div>
            <div>
                <label class="label">License no. <span class="text-muted font-normal">(FSSAI / Drug)</span></label>
                <input name="license_no" value="{{ old('license_no') }}" class="field">
            </div>
        </div>

        <div class="section-title pt-2">Your login</div>
        <div>
            <label class="label">Full name *</label>
            <input name="name" value="{{ old('name') }}" required class="field">
        </div>
        <div>
            <label class="label">Email *</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="field">
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="label">Password *</label>
                <input type="password" name="password" required class="field">
            </div>
            <div>
                <label class="label">Confirm password *</label>
                <input type="password" name="password_confirmation" required class="field">
            </div>
        </div>
        <button class="btn-primary w-full">Create company account</button>
        <p class="text-center text-sm text-muted">Already registered? <a href="{{ route('login') }}" class="text-brand font-semibold">Sign in</a></p>
    </form>
</div>
@endsection
