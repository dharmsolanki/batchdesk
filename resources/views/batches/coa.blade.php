@extends('layouts.app')
@section('title', 'COA ' . $batch->batch_no . ' — BatchDesk')
@section('content')

@php
    $verifyUrl = route('coa.verify', $batch->coa_token);
    $waText = rawurlencode(
        "*Certificate of Analysis*\n" .
        $batch->company->name . "\n" .
        "Product: " . $batch->product->name . "\n" .
        "Batch No: " . $batch->batch_no . "\n" .
        "Mfg: " . $batch->mfg_date->format('d M Y') . ($batch->exp_date ? " | Exp: " . $batch->exp_date->format('d M Y') : "") . "\n" .
        "Status: RELEASED — all parameters within specification\n\n" .
        "Verify online: " . $verifyUrl
    );
@endphp

<div class="no-print flex gap-2 mb-4">
    <a href="https://wa.me/?text={{ $waText }}" target="_blank" class="btn-accent flex-1 text-center text-sm py-3">Share on WhatsApp</a>
    <button onclick="window.print()" class="btn-primary flex-1 text-sm py-3">Print / Save as PDF</button>
</div>

<div class="card p-7">
    <div class="text-center border-b-2 border-navy pb-4">
        @if ($batch->company->logo_path)
            <img src="{{ Storage::url($batch->company->logo_path) }}"
                 alt="{{ $batch->company->name }}"
                 class="h-14 object-contain mx-auto mb-2">
        @endif
        <div class="font-bold text-2xl tracking-tight text-navy">{{ $batch->company->name }}</div>
        <div class="text-xs text-muted mt-1">
            {{ $batch->company->address ?: $batch->company->city }} · Ph: {{ $batch->company->phone }}
            @if ($batch->company->gst_number) · GSTIN: {{ $batch->company->gst_number }}@endif
            @if ($batch->company->license_no) · License: {{ $batch->company->license_no }}@endif
        </div>
        <div class="font-bold text-base mt-4 tracking-[0.2em] text-brand uppercase">Certificate of Analysis</div>
    </div>

    <div class="grid grid-cols-2 gap-x-8 gap-y-1.5 text-sm py-4 border-b border-line">
        <div class="flex justify-between"><span class="text-muted">Product</span> <span class="font-semibold text-right">{{ $batch->product->name }}</span></div>
        <div class="flex justify-between"><span class="text-muted">Batch no.</span> <span class="font-semibold font-mono">{{ $batch->batch_no }}</span></div>
        <div class="flex justify-between"><span class="text-muted">Mfg date</span> <span class="font-semibold">{{ $batch->mfg_date->format('d M Y') }}</span></div>
        <div class="flex justify-between"><span class="text-muted">Expiry date</span> <span class="font-semibold">{{ $batch->exp_date ? $batch->exp_date->format('d M Y') : 'N/A' }}</span></div>
        <div class="flex justify-between"><span class="text-muted">Batch size</span> <span class="font-semibold">{{ rtrim(rtrim(number_format((float) $batch->produced_qty, 3), '0'), '.') }} {{ $batch->product->unit }}</span></div>
        <div class="flex justify-between"><span class="text-muted">Report date</span> <span class="font-semibold">{{ $batch->updated_at->format('d M Y') }}</span></div>
    </div>

    <table class="w-full text-sm mt-5">
        <thead>
            <tr class="text-left text-[11px] uppercase tracking-wide text-white bg-navy">
                <th class="py-2.5 px-3">Parameter</th>
                <th class="py-2.5 px-3">Specification</th>
                <th class="py-2.5 px-3">Method</th>
                <th class="py-2.5 px-3">Result</th>
                <th class="py-2.5 px-3">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($batch->product->specParams as $param)
                @php $r = $batch->testResults->firstWhere('spec_param_id', $param->id); @endphp
                <tr class="border-b border-line">
                    <td class="py-2.5 px-3 font-medium">{{ $param->parameter }}</td>
                    <td class="py-2.5 px-3 text-slate-600">{{ $param->specification }}</td>
                    <td class="py-2.5 px-3 text-muted text-xs">{{ $param->method ?: '—' }}</td>
                    <td class="py-2.5 px-3 font-semibold">{{ $r?->result ?: '—' }}</td>
                    <td class="py-2.5 px-3">
                        @if ($r && $r->pass)
                            <span class="text-pass font-bold text-xs">PASS</span>
                        @elseif ($r)
                            <span class="text-danger font-bold text-xs">FAIL</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-5 bg-emerald-50 border border-emerald-200 rounded-lg px-4 py-2.5 text-sm font-semibold text-pass text-center">
        CONCLUSION: The above batch conforms to all specifications and is released for sale.
    </div>

    @if ($batch->remarks)
    <div class="mt-3 text-xs text-muted">Remarks: {{ $batch->remarks }}</div>
    @endif

    <div class="flex items-end justify-between mt-10">
        <div class="text-sm">
            <div class="mb-7">
                <div class="font-semibold">{{ $batch->tested_by ?: '________________' }}</div>
                <div class="border-t border-slate-400 w-40 pt-1 text-xs text-muted">Tested by</div>
            </div>
            <div>
                <div class="font-semibold">{{ $batch->approved_by ?: '________________' }}</div>
                <div class="border-t border-slate-400 w-40 pt-1 text-xs text-muted">Approved by — Authorized Signatory</div>
            </div>
        </div>
        <div class="text-center">
            <div id="coa-qr" class="inline-block p-1.5 bg-white border border-line rounded-lg"></div>
            <div class="text-[10px] text-muted mt-1 max-w-[130px]">Scan to verify this certificate online</div>
        </div>
    </div>

    <div class="mt-7 text-center text-[10px] text-muted border-t border-line pt-3">
        This certificate was generated digitally and can be verified at {{ $verifyUrl }}<br>
        Generated by BatchDesk
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    new QRCode(document.getElementById('coa-qr'), {
        text: @json($verifyUrl),
        width: 96,
        height: 96,
        correctLevel: QRCode.CorrectLevel.M
    });
</script>
@endsection
