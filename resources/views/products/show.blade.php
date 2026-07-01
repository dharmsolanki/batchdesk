@extends('layouts.app')
@section('title', $product->name . ' — BatchDesk')
@section('content')
    <a href="{{ route('products.index') }}" class="text-brand text-sm font-semibold">← Products</a>
    <h1 class="font-bold text-2xl tracking-tight mt-1 mb-2">{{ $product->name }}</h1>

    <form method="POST" action="{{ route('products.update', $product) }}" class="card p-4 mb-4 space-y-3">
        @csrf @method('PATCH')
        <div class="section-title">Edit Product</div>
        <div>
            <label class="label">Product name *</label>
            <input name="name" value="{{ $product->name }}" required class="field">
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="label">HSN code</label>
                <input name="hsn" value="{{ $product->hsn }}" class="field">
            </div>
            <div>
                <label class="label">Unit *</label>
                <select name="unit" class="field">
                    @foreach (['kg', 'ltr', 'pcs', 'box', 'mt'] as $u)
                        <option value="{{ $u }}" {{ $product->unit === $u ? 'selected' : '' }}>
                            {{ $u }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="label">Price (excl. GST) *</label>
                <input name="price" type="number" step="0.01" value="{{ (float) $product->price }}" required
                    class="field">
            </div>
            <div>
                <label class="label">GST rate *</label>
                <select name="gst_rate" class="field">
                    @foreach ([0, 5, 12, 18, 28] as $r)
                        <option value="{{ $r }}" {{ (float) $product->gst_rate == $r ? 'selected' : '' }}>GST
                            {{ $r }}%</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button class="btn-primary w-full py-2.5">Save changes</button>
    </form>

    <div class="card p-4 mb-4">
        <div class="section-title">COA Specification</div>
        <p class="text-[12px] text-muted mb-3 mt-0.5">These parameters appear on every batch COA. During testing, only the
            results need to be entered.</p>

        @if ($product->specParams->count())
            <table class="w-full text-sm mb-3">
                <thead>
                    <tr class="text-left text-[11px] text-muted uppercase tracking-wide border-b border-line">
                        <th class="py-2 font-semibold">Parameter</th>
                        <th class="py-2 font-semibold">Specification</th>
                        <th class="py-2 font-semibold">Method</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($product->specParams as $param)
                        <tr class="border-b border-line/60">
                            <td colspan="4" class="py-2">
                                <form method="POST" action="{{ route('products.params.update', [$product, $param]) }}"
                                    class="flex gap-2 items-center">
                                    @csrf @method('PATCH')
                                    <input name="parameter" value="{{ $param->parameter }}" class="field text-sm flex-1">
                                    <input name="specification" value="{{ $param->specification }}"
                                        class="field text-sm flex-1">
                                    <input name="method" value="{{ $param->method }}" class="field text-sm flex-1">
                                    <button class="text-brand text-xs font-bold whitespace-nowrap">Save</button>
                                </form>
                            </td>
                            <td class="py-2 pl-2">
                                <form method="POST" action="{{ route('products.params.destroy', [$product, $param]) }}"
                                    onsubmit="return confirm('Remove?')">
                                    @csrf @method('DELETE')
                                    <button class="text-danger text-xs font-bold">✕</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <form method="POST" action="{{ route('products.params.store', $product) }}" class="grid grid-cols-2 gap-2.5">
            @csrf
            <input name="parameter" required placeholder="Parameter (e.g. pH, Assay)" class="field text-sm">
            <input name="specification" required placeholder="Specification (e.g. 8.0–9.0, NMT 0.5%)" class="field text-sm">
            <input name="method" placeholder="Test method (optional)" class="field text-sm">
            <button class="btn-accent text-sm">Add parameter</button>
        </form>
    </div>

    @if ($product->specParams->count())
        <a href="{{ route('batches.create') }}" class="btn-primary block text-center py-3.5 rounded-xl">Create a batch of
            this product →</a>
    @endif
@endsection
