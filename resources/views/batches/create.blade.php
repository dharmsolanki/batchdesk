@extends('layouts.app')
@section('title', 'New Batch — BatchDesk')
@section('content')
<h1 class="font-bold text-2xl tracking-tight mb-4">New Batch</h1>

@if (!$products->count())
    <div class="card p-6 text-center text-sm text-muted">
        Create a product first. <a href="{{ route('products.index') }}" class="text-brand font-semibold">Go to products →</a>
    </div>
@else
<form method="POST" action="{{ route('batches.store') }}" x-data="{ rows: [{lot: '', qty: ''}] }" class="space-y-4">
    @csrf
    <div class="card p-4 space-y-3">
        <div class="section-title">Batch details</div>
        <div>
            <label class="label">Product *</label>
            <select name="product_id" required class="field">
                <option value="">Select product</option>
                @foreach ($products as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach
            </select>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="label">Batch no. *</label>
                <input name="batch_no" required value="{{ old('batch_no', $suggestedBatchNo) }}" class="field font-mono">
            </div>
            <div>
                <label class="label">Quantity produced *</label>
                <input name="produced_qty" type="number" step="0.001" required placeholder="100" class="field">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="label">Mfg date *</label>
                <input name="mfg_date" type="date" value="{{ now()->format('Y-m-d') }}" required class="field">
            </div>
            <div>
                <label class="label">Expiry date</label>
                <input name="exp_date" type="date" class="field">
            </div>
        </div>
    </div>

    <div class="card p-4 space-y-3">
        <div class="section-title">Raw materials consumed <span class="text-muted normal-case font-medium tracking-normal">(optional, recommended for traceability)</span></div>
        @if ($lots->count())
            <template x-for="(row, i) in rows" :key="i">
                <div class="flex gap-2 items-center">
                    <select :name="'lots[' + i + '][material_lot_id]'" x-model="row.lot" class="field flex-1 min-w-0">
                        <option value="">Select lot</option>
                        @foreach ($lots as $lot)
                            <option value="{{ $lot->id }}">{{ $lot->rawMaterial->name }} · {{ $lot->lot_no }} ({{ rtrim(rtrim(number_format((float) $lot->qty, 3), '0'), '.') }} {{ $lot->rawMaterial->unit }} available)</option>
                        @endforeach
                    </select>
                    <input :name="'lots[' + i + '][qty_used]'" x-model="row.qty" type="number" step="0.001" min="0" placeholder="Qty" class="field w-24">
                    <button type="button" @click="rows.splice(i, 1)" x-show="rows.length > 1" class="text-danger font-bold px-1">✕</button>
                </div>
            </template>
            <button type="button" @click="rows.push({lot: '', qty: ''})" class="text-brand font-semibold text-sm">+ Add material</button>
        @else
            <p class="text-sm text-muted">No material lots in stock. <a href="{{ route('materials.index') }}" class="text-brand font-semibold">Receive a lot →</a> or continue without linking.</p>
        @endif
    </div>

    <button class="btn-primary w-full py-3.5 rounded-xl text-base">Create batch — status: Testing</button>
</form>
@endif
@endsection
