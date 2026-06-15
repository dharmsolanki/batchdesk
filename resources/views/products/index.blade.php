@extends('layouts.app')
@section('title', 'Products — BatchDesk')
@section('content')
<div class="flex items-center justify-between mb-1">
    <h1 class="font-bold text-2xl tracking-tight">Products</h1>
    <a href="{{ route('materials.index') }}" class="text-brand font-semibold text-sm">Raw materials →</a>
</div>
<p class="text-sm text-muted mb-4">Define each product's specification once — every batch COA is generated from it.</p>

<div class="card p-4 mb-4">
    <div class="section-title mb-3">New product</div>
    <form method="POST" action="{{ route('products.store') }}" class="grid grid-cols-2 gap-2.5">
        @csrf
        <input name="name" required placeholder="Product name" class="field col-span-2">
        <input name="hsn" placeholder="HSN code" class="field">
        <select name="unit" class="field">
            <option value="kg">kg</option><option value="ltr">ltr</option><option value="pcs">pcs</option><option value="box">box</option><option value="mt">MT</option>
        </select>
        <input name="price" type="number" step="0.01" required placeholder="Price per unit excl. GST (e.g. 120)" class="field">
        <select name="gst_rate" class="field">
            <option value="18">GST 18%</option><option value="12">GST 12%</option><option value="5">GST 5%</option><option value="28">GST 28%</option><option value="0">GST 0%</option>
        </select>
        <button class="btn-primary col-span-2">Create product</button>
    </form>
</div>

<div class="card overflow-hidden">
    @forelse ($products as $p)
        <a href="{{ route('products.show', $p) }}" class="flex items-center justify-between px-4 py-3 border-b border-line/60 hover:bg-paper">
            <div>
                <div class="font-medium text-sm">{{ $p->name }}</div>
                <div class="text-[12px] text-muted">₹{{ number_format((float) $p->price) }}/{{ $p->unit }} (excl. GST) · GST {{ rtrim(rtrim(number_format((float) $p->gst_rate, 2), '0'), '.') }}%</div>
            </div>
            @if ($p->spec_params_count > 0)
                <span class="text-[10px] font-bold text-pass bg-emerald-50 border border-emerald-200 rounded-md px-2 py-1">{{ $p->spec_params_count }} PARAMETERS</span>
            @else
                <span class="text-[10px] font-bold text-amber bg-amber-50 border border-amber-200 rounded-md px-2 py-1">SPEC PENDING</span>
            @endif
        </a>
    @empty
        <div class="px-4 py-8 text-center text-muted text-sm">Create your first product above.</div>
    @endforelse
</div>
@endsection
