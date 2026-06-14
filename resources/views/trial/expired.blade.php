@extends('layouts.app')
@section('title', 'Trial Ended — BatchDesk')
@section('content')
<div class="max-w-md mx-auto mt-8 text-center">
    <div class="card p-8">
        <div class="w-14 h-14 rounded-full bg-amber-100 flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7 text-amber" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        </div>
        <h1 class="font-bold text-2xl tracking-tight">Your free trial has ended</h1>
        <p class="text-muted mt-3 text-sm leading-relaxed">
            Your 30-day trial for <strong>{{ $company->name }}</strong> ended on
            {{ $company->trial_ends_at?->format('d M Y') }}.
            To continue using BatchDesk, contact us to activate your subscription.
        </p>

        <div class="bg-paper rounded-xl p-5 mt-6 text-left space-y-3">
            <div class="text-xs font-bold text-muted uppercase tracking-wide">Subscription plans</div>
            <div class="flex justify-between items-center">
                <div>
                    <div class="font-semibold text-sm">Monthly</div>
                    <div class="text-xs text-muted">Billed every month</div>
                </div>
                <div class="font-bold text-lg">₹1,499<span class="text-muted text-sm font-normal">/mo</span></div>
            </div>
            <div class="flex justify-between items-center border-t border-line pt-3">
                <div>
                    <div class="font-semibold text-sm">Yearly <span class="text-[10px] font-bold text-pass bg-emerald-50 border border-emerald-200 rounded px-1.5 py-0.5">SAVE 2 MONTHS</span></div>
                    <div class="text-xs text-muted">Billed annually</div>
                </div>
                <div class="font-bold text-lg">₹14,999<span class="text-muted text-sm font-normal">/yr</span></div>
            </div>
        </div>

        <div class="mt-6 space-y-2.5">
            <a href="https://wa.me/919328212251?text=Hi%2C+I+want+to+activate+my+BatchDesk+subscription+for+{{ urlencode($company->name) }}"
               target="_blank"
               class="btn-accent block w-full py-3">
               Contact us on WhatsApp to activate
            </a>
            <div class="text-xs text-muted">Or email: hello@batchdesk.in</div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-5">
            @csrf
            <button class="text-sm text-muted underline">Sign out</button>
        </form>
    </div>
</div>
@endsection
