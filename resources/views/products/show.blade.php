@extends('layouts.app')
@section('title', $product->name . ' — BatchDesk')
@section('content')
<a href="{{ route('products.index') }}" class="text-brand text-sm font-semibold">← Products</a>
<h1 class="font-bold text-2xl tracking-tight mt-1 mb-2">{{ $product->name }}</h1>

<form method="POST" action="{{ route('products.update', $product) }}" class="flex items-center gap-2 mb-4">
    @csrf @method('PATCH')
    <input name="price" type="number" step="0.01" value="{{ (float) $product->price }}" class="field w-32 text-sm">
    <select name="gst_rate" class="field w-28 text-sm">
        @foreach ([0, 5, 12, 18, 28] as $r)
            <option value="{{ $r }}" {{ (float) $product->gst_rate == $r ? 'selected' : '' }}>GST {{ $r }}%</option>
        @endforeach
    </select>
    <button class="text-brand text-sm font-bold">Save</button>
</form>

<div class="card p-4 mb-4">
    <div class="section-title">COA Specification</div>
    <p class="text-[12px] text-muted mb-3 mt-0.5">These parameters appear on every batch COA. During testing, only the results need to be entered.</p>

    @if ($product->specParams->count())
    <table class="w-full text-sm mb-3">
        <thead>
            <tr class="text-left text-[11px] text-muted uppercase tracking-wide border-b border-line">
                <th class="py-2 font-semibold">Parameter</th><th class="py-2 font-semibold">Specification</th><th class="py-2 font-semibold">Method</th><th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($product->specParams as $param)
            <tr class="border-b border-line/60">
                <td class="py-2.5 font-medium">{{ $param->parameter }}</td>
                <td class="py-2.5 text-slate-600">{{ $param->specification }}</td>
                <td class="py-2.5 text-muted text-xs">{{ $param->method ?: '—' }}</td>
                <td class="py-2.5 text-right">
                    <form method="POST" action="{{ route('products.params.destroy', [$product, $param]) }}" onsubmit="return confirm('Remove this parameter?')">
                        @csrf @method('DELETE')
                        <button class="text-danger text-xs font-bold px-1">Remove</button>
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
<a href="{{ route('batches.create') }}" class="btn-primary block text-center py-3.5 rounded-xl">Create a batch of this product →</a>
@endif
@endsection
