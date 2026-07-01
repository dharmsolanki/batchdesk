@extends('layouts.app')
@section('title', $customer->name . ' — BatchDesk')
@section('content')
    <div class="card p-5 mb-4" x-data="{ editing: false }">
        <div x-show="!editing" class="flex justify-between items-start">
            <div>
                <div class="font-bold text-xl tracking-tight">{{ $customer->name }}</div>
                <div class="text-sm text-muted">
                    {{ $customer->phone }}{{ $customer->gst_number ? ' · GSTIN: ' . $customer->gst_number : '' }}</div>
                @if ($customer->address)
                    <div class="text-sm text-muted">{{ $customer->address }}</div>
                @endif
            </div>
            <button @click="editing=true" class="text-brand text-xs font-bold">Edit</button>
        </div>
        <form x-show="editing" x-cloak method="POST" action="{{ route('customers.update', $customer) }}" class="space-y-3">
            @csrf @method('PATCH')
            <div class="grid grid-cols-2 gap-3">
                <input name="name" value="{{ $customer->name }}" placeholder="Name" required class="field">
                <input name="phone" value="{{ $customer->phone }}" placeholder="Phone" required class="field">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <input name="gst_number" value="{{ $customer->gst_number }}" placeholder="GSTIN" class="field">
                <input name="address" value="{{ $customer->address }}" placeholder="Address" class="field">
            </div>
            <button class="btn-primary w-full py-2.5">Save changes</button>
        </form>
        <div class="flex gap-2 mt-3">
            <a href="tel:{{ $customer->phone }}" class="btn-primary flex-1 text-center text-sm py-2.5">Call</a>
            @php
                $wp = preg_replace('/\D/', '', $customer->phone);
                if (strlen($wp) === 10) {
                    $wp = '91' . $wp;
                }
            @endphp
            <a href="https://wa.me/{{ $wp }}" target="_blank"
                class="btn-accent flex-1 text-center text-sm py-2.5">WhatsApp</a>
        </div>
    </div>

    <h2 class="font-semibold text-lg tracking-tight mb-2">Purchase history ({{ $customer->sales->count() }})</h2>
    <div class="card overflow-hidden">
        @forelse ($customer->sales as $s)
            <a href="{{ route('sales.show', $s) }}" class="block px-4 py-3 border-b border-line/60 hover:bg-paper">
                <div class="flex justify-between">
                    <div class="font-medium text-sm font-mono">{{ $s->invoice_no }}</div>
                    <div class="font-semibold text-sm">₹{{ number_format($s->total) }}</div>
                </div>
                <div class="text-[12px] text-muted mt-0.5">{{ $s->created_at->format('d M Y') }} ·
                    {{ $s->items->pluck('description')->take(2)->implode(', ') }}</div>
            </a>
        @empty
            <div class="px-4 py-8 text-center text-muted text-sm">No invoices.</div>
        @endforelse
    </div>
@endsection
