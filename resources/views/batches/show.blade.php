@extends('layouts.app')
@section('title', $batch->batch_no . ' — BatchDesk')
@section('content')
<a href="{{ route('batches.index') }}" class="text-brand text-sm font-semibold">← Batches</a>
<div class="flex items-start justify-between mt-1 mb-4">
    <div>
        <h1 class="font-bold text-2xl tracking-tight">{{ $batch->product->name }}</h1>
        <div class="text-sm text-muted"><span class="font-mono">{{ $batch->batch_no }}</span> · Mfg {{ $batch->mfg_date->format('d M Y') }}{{ $batch->exp_date ? ' · Exp ' . $batch->exp_date->format('d M Y') : '' }} · {{ rtrim(rtrim(number_format((float) $batch->qty, 3), '0'), '.') }}/{{ rtrim(rtrim(number_format((float) $batch->produced_qty, 3), '0'), '.') }} {{ $batch->product->unit }}</div>
    </div>
    <div class="mt-1">@include('batches._status', ['status' => $batch->status])</div>
</div>

@if ($batch->status === 'released')
<a href="{{ route('batches.coa', $batch) }}" class="btn-primary block text-center py-3.5 rounded-xl mb-4 text-base">View Certificate of Analysis</a>
@endif

<div class="card p-4 mb-4">
    <div class="section-title mb-3">Test results</div>
    @if ($batch->product->specParams->count())
    <form method="POST" action="{{ route('batches.results', $batch) }}" class="space-y-3">
        @csrf
        @foreach ($batch->product->specParams as $param)
            @php $r = $results->get($param->id); @endphp
            <div class="border-b border-line/60 pb-3">
                <div class="flex justify-between text-sm mb-1.5">
                    <span class="font-medium">{{ $param->parameter }}</span>
                    <span class="text-muted text-xs">Spec: {{ $param->specification }}</span>
                </div>
                <div class="flex gap-2">
                    <input name="results[{{ $param->id }}][result]" value="{{ $r?->result }}" placeholder="Result" class="field flex-1 min-w-0 text-sm">
                    <select name="results[{{ $param->id }}][pass]" class="field w-28 text-sm font-semibold">
                        <option value="1" {{ $r === null || $r->pass ? 'selected' : '' }}>Pass</option>
                        <option value="0" {{ $r !== null && !$r->pass ? 'selected' : '' }}>Fail</option>
                    </select>
                </div>
            </div>
        @endforeach
        <div class="grid grid-cols-2 gap-2.5">
            <input name="tested_by" value="{{ $batch->tested_by }}" placeholder="Tested by" class="field text-sm">
            <input name="approved_by" value="{{ $batch->approved_by }}" placeholder="Approved by" class="field text-sm">
        </div>
        <input name="remarks" value="{{ $batch->remarks }}" placeholder="Remarks (optional)" class="field text-sm">
        <button class="btn-accent w-full py-3">Save results</button>
        <p class="text-[11px] text-muted text-center">All parameters passing releases the batch automatically and enables the COA. Any failure marks it rejected.</p>
    </form>
    @else
        <p class="text-sm text-muted">No specification defined for this product. <a href="{{ route('products.show', $batch->product) }}" class="text-brand font-semibold">Define specification →</a></p>
    @endif
</div>

@if ($batch->materials->count())
<div class="card p-4">
    <div class="section-title mb-2">Raw materials consumed (traceability)</div>
    @foreach ($batch->materials as $bm)
        <div class="flex justify-between text-sm py-1.5 border-b border-line/60">
            <span>{{ $bm->materialLot->rawMaterial->name }} · <span class="font-mono text-[13px]">{{ $bm->materialLot->lot_no }}</span><span class="text-muted text-xs">{{ $bm->materialLot->supplier ? ' · ' . $bm->materialLot->supplier : '' }}</span></span>
            <span class="font-semibold">{{ rtrim(rtrim(number_format((float) $bm->qty_used, 3), '0'), '.') }} {{ $bm->materialLot->rawMaterial->unit }}</span>
        </div>
    @endforeach
</div>
@endif
@endsection
