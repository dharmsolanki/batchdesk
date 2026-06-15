@extends('layouts.app')
@section('title', 'COA Verification — BatchDesk')
@section('content')
<div class="max-w-lg mx-auto">
    <div class="card border-emerald-300 bg-emerald-50 p-5 text-center mb-4">
        <svg class="w-10 h-10 mx-auto text-pass mb-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.5 0 10-4.5 10-10S17.5 2 12 2 2 6.5 2 12s4.5 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
        <div class="font-bold text-xl text-pass tracking-tight">Certificate Verified</div>
        <div class="text-sm text-emerald-800 mt-1">This Certificate of Analysis was issued by {{ $batch->company->name }} and is authentic.</div>
    </div>

    <div class="card p-5">
        <div class="text-center border-b border-line pb-3 mb-3">
            @if ($batch->company->logo_path)
                <img src="/public/storage/{{ $batch->company->logo_path }}"
                     alt="{{ $batch->company->name }}"
                     class="h-10 object-contain mx-auto mb-1">
            @endif
            <div class="font-bold text-lg tracking-tight">{{ $batch->company->name }}</div>
            <div class="text-xs text-muted">{{ $batch->company->city }} · Ph: {{ $batch->company->phone }}</div>
        </div>
        <div class="grid grid-cols-2 gap-x-4 gap-y-1.5 text-sm mb-4">
            <div><span class="text-muted">Product:</span> <span class="font-semibold">{{ $batch->product->name }}</span></div>
            <div><span class="text-muted">Batch:</span> <span class="font-semibold font-mono">{{ $batch->batch_no }}</span></div>
            <div><span class="text-muted">Mfg:</span> <span class="font-semibold">{{ $batch->mfg_date->format('d M Y') }}</span></div>
            <div><span class="text-muted">Exp:</span> <span class="font-semibold">{{ $batch->exp_date ? $batch->exp_date->format('d M Y') : 'N/A' }}</span></div>
            <div><span class="text-muted">Status:</span>
                @if ($batch->status === 'released')<span class="font-bold text-pass">RELEASED</span>
                @else<span class="font-bold text-danger">{{ strtoupper($batch->status) }}</span>@endif
            </div>
            <div><span class="text-muted">Report:</span> <span class="font-semibold">{{ $batch->updated_at->format('d M Y') }}</span></div>
        </div>

        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-[11px] text-muted uppercase tracking-wide border-b border-line">
                    <th class="py-1.5 font-semibold">Parameter</th><th class="py-1.5 font-semibold">Specification</th><th class="py-1.5 font-semibold">Result</th><th class="py-1.5"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($batch->product->specParams as $param)
                    @php $r = $batch->testResults->firstWhere('spec_param_id', $param->id); @endphp
                    <tr class="border-b border-line/60">
                        <td class="py-2 font-medium">{{ $param->parameter }}</td>
                        <td class="py-2 text-muted">{{ $param->specification }}</td>
                        <td class="py-2 font-semibold">{{ $r?->result ?: '—' }}</td>
                        <td class="py-2">
                            @if ($r && $r->pass)<span class="text-pass font-bold text-xs">PASS</span>
                            @elseif ($r)<span class="text-danger font-bold text-xs">FAIL</span>@endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4 text-[11px] text-muted text-center">Verified at {{ now()->format('d M Y, h:i a') }} · Powered by BatchDesk</div>
    </div>
</div>
@endsection
