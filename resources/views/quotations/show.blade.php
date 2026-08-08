@extends('layouts.app')
@section('title', 'Proforma Invoice ' . $quotation->quotation_no)
@section('content')

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white;
            }
        }
    </style>

    {{-- Actions --}}
    <div class="no-print flex flex-wrap gap-2 mb-4">
        <button onclick="window.print()" class="btn-primary flex-1 text-sm py-3">🖨 Print</button>
        <a href="{{ route('quotations.index') }}" class="btn-accent flex-1 text-center text-sm py-3">← Back</a>

        <form method="POST" action="{{ route('quotations.destroy', $quotation) }}"
            onsubmit="return confirm('Delete this Proforma Invoice?')" class="w-full">
            @csrf @method('DELETE')
            <button
                class="w-full text-danger text-sm font-semibold border border-danger/30 rounded-xl py-2.5">Delete</button>
        </form>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 text-sm rounded-xl px-4 py-3 mb-4 no-print">
            {{ session('success') }}</div>
    @endif

    {{-- Printable Quotation --}}
    <div class="card p-6">

        {{-- Header --}}
        <div class="text-center border-b-2 border-navy pb-4 mb-5">
            <div class="font-bold text-xl text-navy">{{ $quotation->company->name }}</div>
            <div class="text-xs text-muted">
                {{ $quotation->company->address ?: $quotation->company->city }}
                · Ph: {{ $quotation->company->phone }}
                @if ($quotation->company->gst_number)
                    · GSTIN: {{ $quotation->company->gst_number }}
                @endif
                @if ($quotation->company->license_no)
                    · Lic: {{ $quotation->company->license_no }}
                @endif
            </div>
            <div class="font-bold text-base mt-3 tracking-[0.2em] text-brand uppercase">Proforma Invoice</div>
        </div>

        {{-- Meta --}}
        <div class="grid grid-cols-2 gap-4 text-sm mb-5">
            <div><span class="text-muted">Quotation No.:</span> <strong
                    class="font-mono">{{ $quotation->quotation_no }}</strong></div>
            <div><span class="text-muted">Date:</span> <strong>{{ $quotation->created_at->format('d M Y') }}</strong></div>
            <div><span class="text-muted">Valid Until:</span>
                <strong>{{ $quotation->valid_until->format('d M Y') }}</strong>
            </div>
            <div><span class="text-muted">Status:</span> <strong
                    class="{{ $quotation->status_color }}">{{ $quotation->status_label }}</strong></div>
        </div>

        {{-- Customer --}}
        <div class="bg-paper rounded-xl p-4 mb-5 text-sm">
            <div class="text-xs font-bold text-muted uppercase tracking-wide mb-1">To</div>
            <div class="font-bold">{{ $quotation->customer->name }}</div>
            @if ($quotation->customer->gst_number)
                <div class="text-muted">GSTIN: {{ $quotation->customer->gst_number }}</div>
            @endif
            @if ($quotation->customer->address)
                <div class="text-muted">{{ $quotation->customer->address }}</div>
            @endif
            @if ($quotation->customer->phone)
                <div class="text-muted">Ph: {{ $quotation->customer->phone }}</div>
            @endif
        </div>

        {{-- Items --}}
        <table class="w-full text-sm mb-5">
            <thead>
                <tr class="text-left text-[11px] text-muted uppercase tracking-wide border-b border-line">
                    <th class="py-2">#</th>
                    <th class="py-2">Description</th>
                    <th class="py-2 text-center">HSN</th>
                    <th class="py-2 text-center">Qty</th>
                    <th class="py-2 text-right">Rate</th>
                    <th class="py-2 text-right">GST</th>
                    <th class="py-2 text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quotation->items as $i => $item)
                    <tr class="border-b border-line/60">
                        <td class="py-2.5 text-muted text-xs">{{ $i + 1 }}</td>
                        <td class="py-2.5 pr-2">{{ $item->description }}</td>
                        <td class="py-2.5 text-center text-muted text-xs font-mono">{{ $item->hsn ?: '—' }}</td>
                        <td class="py-2.5 text-center">{{ rtrim(rtrim(number_format((float) $item->qty, 3), '0'), '.') }}
                            {{ $item->unit }}</td>
                        <td class="py-2.5 text-right">₹{{ number_format((float) $item->unit_price, 2) }}</td>
                        <td class="py-2.5 text-right text-xs">
                            {{ rtrim(rtrim(number_format((float) $item->gst_rate, 2), '0'), '.') }}%</td>
                        <td class="py-2.5 text-right font-semibold">₹{{ number_format((float) $item->line_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Totals --}}
        <div class="flex justify-end mb-5">
            <div class="w-64 space-y-1 text-sm">
                <div class="flex justify-between text-muted">
                    <span>Subtotal</span><span>₹{{ number_format($quotation->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-muted">
                    <span>GST</span><span>₹{{ number_format($quotation->gst_amount, 2) }}</span>
                </div>
                <div class="flex justify-between font-bold text-base border-t border-line pt-1.5">
                    <span>Total</span><span>₹{{ number_format($quotation->total, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Terms --}}
        @if ($quotation->terms)
            <div class="border-t border-line pt-4 text-xs text-muted">
                <div class="font-bold text-ink mb-1">Terms & Conditions</div>
                <div class="whitespace-pre-line">{{ $quotation->terms }}</div>
            </div>
        @endif

        {{-- Footer --}}
        <div class="mt-8 text-center text-[10px] text-muted border-t border-line pt-3">
            Generated by BatchDesk · {{ $quotation->company->name }}
        </div>
    </div>
@endsection
