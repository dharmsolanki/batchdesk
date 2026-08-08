@extends('layouts.app')
@section('title', 'Supplier')
@section('content')

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 text-sm rounded-xl px-4 py-3 mb-4">
            {{ session('success') }}</div>
    @endif

    {{-- Supplier Details --}}
    <div class="card p-5 mb-4" x-data="{ editing: false }">
        <div x-show="!editing" class="space-y-1">
            <div class="flex justify-between items-start">
                <div>
                    <div class="font-bold text-xl">{{ $supplier->name }}</div>
                    <div class="text-sm text-muted">
                        {{ $supplier->city }}{{ $supplier->phone ? ' · ' . $supplier->phone : '' }}
                        {{ $supplier->email ? ' · ' . $supplier->email : '' }}
                    </div>
                    @if ($supplier->gst_number)
                        <div class="text-xs text-muted">GSTIN: {{ $supplier->gst_number }}</div>
                    @endif
                    @if ($supplier->address)
                        <div class="text-xs text-muted">{{ $supplier->address }}</div>
                    @endif
                    @if ($supplier->payment_terms)
                        <div class="mt-2">
                            <span
                                class="text-xs font-semibold border border-line rounded px-2 py-0.5">{{ $supplier->payment_terms }}</span>
                        </div>
                    @endif
                    @if ($supplier->notes)
                        <div class="text-xs text-muted mt-1">{{ $supplier->notes }}</div>
                    @endif
                </div>
                <button @click="editing=true" class="text-brand text-xs font-bold">Edit</button>
            </div>
            <div class="flex gap-2 mt-3">
                @if ($supplier->phone)
                    <a href="tel:{{ $supplier->phone }}" class="btn-primary flex-1 text-center text-sm py-2">Call</a>
                    @php
                        $wp = preg_replace('/\D/', '', $supplier->phone);
                        if (strlen($wp) === 10) {
                            $wp = '91' . $wp;
                        }
                    @endphp
                    <a href="https://wa.me/{{ $wp }}" target="_blank"
                        class="btn-accent flex-1 text-center text-sm py-2">WhatsApp</a>
                @endif
            </div>
        </div>

        <form x-show="editing" x-cloak method="POST" action="{{ route('suppliers.update', $supplier) }}"
            class="space-y-3">
            @csrf @method('PATCH')
            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2"><input name="name" value="{{ $supplier->name }}" required class="field"
                        placeholder="Name *"></div>
                <input name="phone" value="{{ $supplier->phone }}" class="field" placeholder="Phone">
                <input name="email" value="{{ $supplier->email }}" class="field" placeholder="Email">
                <input name="gst_number" value="{{ $supplier->gst_number }}" class="field" placeholder="GSTIN">
                <input name="city" value="{{ $supplier->city }}" class="field" placeholder="City">
                <div class="col-span-2"><input name="address" value="{{ $supplier->address }}" class="field"
                        placeholder="Address"></div>
                <div class="col-span-2"><input name="payment_terms" value="{{ $supplier->payment_terms }}" class="field"
                        placeholder="Payment terms"></div>
                <div class="col-span-2">
                    <textarea name="notes" class="field" rows="2">{{ $supplier->notes }}</textarea>
                </div>
            </div>
            <div class="flex gap-2">
                <button class="btn-primary flex-1 py-2.5">Save</button>
                <button type="button" @click="editing=false"
                    class="flex-1 border border-line rounded-lg py-2.5 text-sm">Cancel</button>
            </div>
        </form>
    </div>

    {{-- Products / Rate Card --}}
    <div class="card mb-4">
        <div class="flex items-center justify-between px-4 py-3 border-b border-line">
            <div class="section-title">Products & Rate Card</div>
        </div>

        @foreach ($supplier->products as $product)
            <div class="border-b border-line/60" x-data="{ editing: false, showCoa: false }">
                <div class="px-4 py-3">
                    <div class="flex justify-between items-start">
                        <div x-show="!editing">
                            <div class="font-semibold text-sm">{{ $product->product_name }}</div>
                            <div class="text-xs text-muted">
                                HSN: {{ $product->hsn ?: '—' }} ·
                                ₹{{ number_format((float) $product->rate, 2) }}/{{ $product->unit }} +
                                {{ $product->gst_rate }}% GST
                            </div>
                            @if ($product->notes)
                                <div class="text-xs text-muted">{{ $product->notes }}</div>
                            @endif
                        </div>
                        <div class="flex gap-2 text-xs font-bold shrink-0">
                            <button @click="editing=!editing" class="text-brand"
                                x-text="editing ? 'Cancel' : 'Edit'"></button>
                            <button @click="showCoa=!showCoa" class="text-muted"
                                x-text="showCoa ? 'Hide COAs' : 'COAs (' + {{ $product->coas->count() }} + ')'"></button>
                        </div>
                    </div>

                    <form x-show="editing" x-cloak method="POST"
                        action="{{ route('suppliers.products.update', [$supplier, $product]) }}"
                        class="grid grid-cols-2 gap-2 mt-3">
                        @csrf @method('PATCH')
                        <div class="col-span-2"><input name="product_name" value="{{ $product->product_name }}" required
                                class="field text-sm" placeholder="Product name *"></div>
                        <input name="hsn" value="{{ $product->hsn }}" class="field text-sm" placeholder="HSN">
                        <select name="unit" class="field text-sm">
                            @foreach (['kg', 'ltr', 'pcs', 'mt'] as $u)
                                <option value="{{ $u }}" {{ $product->unit === $u ? 'selected' : '' }}>
                                    {{ $u }}</option>
                            @endforeach
                        </select>
                        <input name="rate" type="number" step="0.01" value="{{ $product->rate }}"
                            class="field text-sm" placeholder="Rate">
                        <select name="gst_rate" class="field text-sm">
                            @foreach ([0, 5, 12, 18, 28] as $r)
                                <option value="{{ $r }}"
                                    {{ (float) $product->gst_rate == $r ? 'selected' : '' }}>GST
                                    {{ $r }}%</option>
                            @endforeach
                        </select>
                        <div class="col-span-2"><input name="notes" value="{{ $product->notes }}"
                                class="field text-sm" placeholder="Notes"></div>
                        <button class="btn-primary col-span-2 py-2 text-sm">Save</button>
                    </form>

                    {{-- COA List --}}
                    <div x-show="showCoa" x-cloak class="mt-3 space-y-2">
                        @foreach ($product->coas as $coa)
                            <div class="bg-paper rounded-lg px-3 py-2 text-xs flex justify-between items-center">
                                <div>
                                    <span class="font-mono font-semibold">{{ $coa->lot_no }}</span>
                                    {{ $coa->received_date ? ' · Recd: ' . $coa->received_date->format('d M Y') : '' }}
                                    {{ $coa->expiry_date ? ' · Exp: ' . $coa->expiry_date->format('d M Y') : '' }}
                                </div>
                                <form method="POST" action="{{ route('suppliers.coas.update', $coa) }}"
                                    class="flex gap-1">
                                    @csrf @method('PATCH')
                                    <select name="coa_status" class="field text-xs py-1 w-24">
                                        @foreach (['pending' => 'Pending', 'received' => 'Received', 'verified' => 'Verified'] as $v => $l)
                                            <option value="{{ $v }}"
                                                {{ $coa->coa_status === $v ? 'selected' : '' }}>{{ $l }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button class="btn-accent px-2 py-1 text-xs">Save</button>
                                </form>
                            </div>
                        @endforeach

                        {{-- Add COA lot --}}
                        <form method="POST" action="{{ route('suppliers.coas.store', [$supplier, $product]) }}"
                            class="grid grid-cols-2 gap-2 border border-line rounded-lg p-3">
                            @csrf
                            <div class="col-span-2 text-xs font-bold text-muted uppercase">Add LOT</div>
                            <input name="lot_no" required class="field text-sm" placeholder="Lot No *">
                            <select name="coa_status" class="field text-sm">
                                <option value="pending">Pending</option>
                                <option value="received">Received</option>
                                <option value="verified">Verified</option>
                            </select>
                            <input name="received_date" type="date" class="field text-sm">
                            <input name="expiry_date" type="date" class="field text-sm">
                            <div class="col-span-2"><input name="notes" class="field text-sm" placeholder="Notes">
                            </div>
                            <button class="btn-primary col-span-2 py-2 text-sm">Add LOT</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Add Product --}}
        <div class="px-4 py-3" x-data="{ open: false }">
            <button @click="open=!open" class="text-brand text-sm font-semibold"
                x-text="open ? 'Cancel' : '+ Add product / material'"></button>
            <form x-show="open" x-cloak method="POST" action="{{ route('suppliers.products.store', $supplier) }}"
                class="grid grid-cols-2 gap-2 mt-3">
                @csrf
                <div class="col-span-2"><input name="product_name" required class="field text-sm"
                        placeholder="Product name *"></div>
                <input name="hsn" class="field text-sm" placeholder="HSN">
                <select name="unit" class="field text-sm">
                    @foreach (['kg', 'ltr', 'pcs', 'mt'] as $u)
                        <option value="{{ $u }}">{{ $u }}</option>
                    @endforeach
                </select>
                <input name="rate" type="number" step="0.01" class="field text-sm"
                    placeholder="Rate (excl. GST)">
                <select name="gst_rate" class="field text-sm">
                    @foreach ([0, 5, 12, 18, 28] as $r)
                        <option value="{{ $r }}" {{ $r === 18 ? 'selected' : '' }}>GST {{ $r }}%
                        </option>
                    @endforeach
                </select>
                <div class="col-span-2"><input name="notes" class="field text-sm" placeholder="Notes"></div>
                <button class="btn-primary col-span-2 py-2 text-sm">Add Product</button>
            </form>
        </div>
    </div>

    <a href="{{ route('suppliers.index') }}" class="text-muted text-sm">← Back to suppliers</a>
@endsection
