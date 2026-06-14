@extends('layouts.app')
@section('title', $sale->invoice_no . ' — BatchDesk')
@section('content')

@php
    $modeLabels = ['cash' => 'Cash', 'upi' => 'UPI', 'card' => 'Card', 'bank' => 'Bank/NEFT', 'credit' => 'Credit'];
    $coaLinks = $sale->items->filter(fn ($i) => $i->batch && $i->batch->status === 'released')
        ->map(fn ($i) => $i->batch->batch_no . ": " . route('coa.verify', $i->batch->coa_token))->implode("\n");
    $waText = rawurlencode(
        "*" . $sale->company->name . "*\n" .
        "Invoice: " . $sale->invoice_no . "\n" .
        "Date: " . $sale->created_at->format('d M Y') . "\n" .
        "------------------\n" .
        $sale->items->map(fn ($i) => $i->description . "\n" . rtrim(rtrim(number_format((float) $i->qty, 3), '0'), '.') . " " . $i->unit . " = ₹" . number_format((float) $i->line_total))->implode("\n") . "\n" .
        "------------------\n" .
        "*Total: ₹" . number_format((float) $sale->total) . "*\n" .
        $sale->payments->map(fn ($p) => "Paid (" . ($modeLabels[$p->mode] ?? $p->mode) . "): ₹" . number_format((float) $p->amount))->implode("\n") .
        ($sale->pending > 0 ? "\n*Balance due: ₹" . number_format($sale->pending) . "*" : "") .
        ($coaLinks ? "\n\n*COA verification links:*\n" . $coaLinks : "") . "\n\n" .
        "Thank you for your business."
    );
    $waPhone = preg_replace('/\D/', '', $sale->customer->phone);
    if (strlen($waPhone) === 10) { $waPhone = '91' . $waPhone; }
@endphp

<div class="no-print flex gap-2 mb-4">
    <a href="https://wa.me/{{ $waPhone }}?text={{ $waText }}" target="_blank" class="btn-accent flex-1 text-center text-sm py-3">WhatsApp (Invoice + COA)</a>
    <button onclick="window.print()" class="btn-primary flex-1 text-sm py-3">Print / Save as PDF</button>
</div>

@if ($sale->pending > 0)
<div class="no-print card border-amber-300 bg-amber-50 p-4 mb-4">
    <div class="text-sm font-semibold text-amber mb-2">Balance due: ₹{{ number_format($sale->pending) }}</div>
    <form method="POST" action="{{ route('sales.payment', $sale) }}" class="flex gap-2">
        @csrf
        <input name="amount" type="number" step="0.01" min="1" max="{{ $sale->pending }}" required placeholder="Amount received" class="field flex-1 min-w-0">
        <select name="mode" class="field w-28">
            <option value="cash">Cash</option><option value="upi">UPI</option><option value="bank">Bank</option><option value="card">Card</option><option value="credit">Credit</option>
        </select>
        <button class="btn-primary px-4">Record</button>
    </form>
</div>
@endif

<div class="card p-7">
    <div class="flex justify-between items-start border-b border-line pb-4">
        <div>
            <div class="font-bold text-xl tracking-tight">{{ $sale->company->name }}</div>
            <div class="text-xs text-muted mt-0.5">
                {{ $sale->company->address ?: $sale->company->city }}<br>
                Ph: {{ $sale->company->phone }}
                @if ($sale->company->gst_number)<br>GSTIN: {{ $sale->company->gst_number }}@endif
            </div>
        </div>
        <div class="text-right">
            <div class="font-bold text-brand text-sm tracking-[0.15em] uppercase">Tax Invoice</div>
            <div class="text-sm font-semibold font-mono">{{ $sale->invoice_no }}</div>
            <div class="text-xs text-muted">{{ $sale->created_at->format('d M Y, h:i a') }}</div>
        </div>
    </div>

    <div class="py-3 border-b border-line/60 text-sm">
        <span class="text-muted">Bill to:</span>
        <span class="font-semibold">{{ $sale->customer->name }}</span> · {{ $sale->customer->phone }}
        @if ($sale->customer->gst_number)<span class="text-xs text-muted"> · GSTIN: {{ $sale->customer->gst_number }}</span>@endif
        @if ($sale->customer->address)<div class="text-xs text-muted">{{ $sale->customer->address }}</div>@endif
    </div>

    <table class="w-full text-sm mt-3">
        <thead>
            <tr class="text-left text-[11px] text-muted uppercase tracking-wide border-b border-line">
                <th class="py-2 font-semibold">Item / Batch</th>
                <th class="py-2 font-semibold text-center">Qty</th>
                <th class="py-2 font-semibold text-right">Rate</th>
                <th class="py-2 font-semibold text-right">GST</th>
                <th class="py-2 font-semibold text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->items as $item)
            <tr class="border-b border-line/60">
                <td class="py-2.5 pr-2">{{ $item->description }}</td>
                <td class="py-2.5 text-center whitespace-nowrap">{{ rtrim(rtrim(number_format((float) $item->qty, 3), '0'), '.') }} {{ $item->unit }}</td>
                <td class="py-2.5 text-right">₹{{ number_format((float) $item->unit_price, 2) }}</td>
                <td class="py-2.5 text-right text-xs">{{ rtrim(rtrim(number_format((float) $item->gst_rate, 2), '0'), '.') }}%</td>
                <td class="py-2.5 text-right font-semibold">₹{{ number_format((float) $item->line_total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4 ml-auto max-w-xs text-sm space-y-1">
        <div class="flex justify-between text-muted"><span>Subtotal</span><span>₹{{ number_format((float) $sale->subtotal, 2) }}</span></div>
        <div class="flex justify-between text-muted"><span>CGST</span><span>₹{{ number_format((float) $sale->gst_amount / 2, 2) }}</span></div>
        <div class="flex justify-between text-muted"><span>SGST</span><span>₹{{ number_format((float) $sale->gst_amount / 2, 2) }}</span></div>
        <div class="flex justify-between font-bold text-lg border-t border-line pt-2"><span>Total</span><span>₹{{ number_format((float) $sale->total, 2) }}</span></div>
    </div>

    <div class="mt-4 border-t border-line/60 pt-3">
        <div class="text-[11px] font-bold text-muted uppercase tracking-wide mb-2">Payment history</div>
        @forelse ($sale->payments->sortBy('created_at') as $p)
            <div class="flex justify-between text-sm py-1">
                <span class="text-slate-600">{{ $p->created_at->format('d M Y, h:i a') }} · <span class="font-semibold text-brand">{{ $modeLabels[$p->mode] ?? strtoupper($p->mode) }}</span></span>
                <span class="font-semibold">₹{{ number_format((float) $p->amount, 2) }}</span>
            </div>
        @empty
            <div class="text-sm text-muted">No payments recorded yet.</div>
        @endforelse
        <div class="flex justify-between text-sm font-bold border-t border-line/60 mt-2 pt-2">
            <span>Total paid</span><span class="text-pass">₹{{ number_format((float) $sale->paid_amount, 2) }}</span>
        </div>
        @if ($sale->pending > 0)
        <div class="flex justify-between text-sm font-bold text-amber"><span>Balance due</span><span>₹{{ number_format($sale->pending, 2) }}</span></div>
        @endif
    </div>

    @php $coaBatches = $sale->items->filter(fn ($i) => $i->batch && $i->batch->status === 'released'); @endphp
    @if ($coaBatches->count())
    <div class="no-print mt-4 border-t border-line/60 pt-3">
        <div class="text-[11px] font-bold text-muted uppercase tracking-wide mb-2">COA documents</div>
        @foreach ($coaBatches as $item)
            <a href="{{ route('batches.coa', $item->batch) }}" class="block text-brand font-semibold text-sm py-1">COA — <span class="font-mono">{{ $item->batch->batch_no }}</span> →</a>
        @endforeach
    </div>
    @endif

    @if ($sale->notes)
    <div class="mt-4 text-xs text-muted border-t border-line/60 pt-3">Notes: {{ $sale->notes }}</div>
    @endif

    <div class="mt-6 text-center text-[10px] text-muted">This is a computer-generated invoice · Generated by BatchDesk</div>
</div>
@endsection
