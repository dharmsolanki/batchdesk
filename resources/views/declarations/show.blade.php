@extends('layouts.app')
@section('title', 'Buyer Declaration')
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

    <div class="no-print flex gap-2 mb-4">
        @if ($declaration)
            <button onclick="window.print()" class="btn-primary flex-1 text-sm py-3">🖨 Print Declaration</button>
        @endif
        <a href="{{ route('sales.show', $sale) }}" class="btn-accent flex-1 text-center text-sm py-3">← Back to Invoice</a>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 text-sm rounded-xl px-4 py-3 mb-4">
            {{ session('success') }}</div>
    @endif

    @if (!$declaration)
        <div class="card p-5 mb-4">
            <div class="section-title mb-3">Fill Buyer Declaration</div>
            <form method="POST" action="{{ route('declarations.store', $sale) }}" class="space-y-3">
                @csrf
                <div>
                    <label class="label">Buyer Name (Authorized Signatory) *</label>
                    <input name="buyer_name" value="{{ $sale->customer->name }}" required class="field">
                </div>
                <div>
                    <label class="label">Buyer Company Name *</label>
                    <input name="buyer_company" value="{{ $sale->customer->name }}" required class="field">
                </div>
                {{-- <div>
                    <label class="label">Material will be used for *</label>
                    <select name="intended_use" class="field">
                        <option value="Manufacturing — own products">Manufacturing — own products</option>
                        <option value="Resale to end users">Resale to end users</option>
                        <option value="Industrial / commercial use">Industrial / commercial use</option>
                        <option value="Research and development">Research and development</option>
                        <option value="Export purpose">Export purpose</option>
                        <option value="Other lawful purpose">Other lawful purpose</option>
                    </select>
                </div> --}}
                <button class="btn-primary w-full py-3">Save & Generate Declaration</button>
            </form>
        </div>
    @else
        <div class="card p-8" id="declaration">
            <div class="text-center border-b-2 border-navy pb-4 mb-5">
                <div class="font-bold text-xl text-navy">{{ $sale->company->name }}</div>
                <div class="text-xs text-muted">
                    {{ $sale->company->address ?: $sale->company->city }}
                    · Ph: {{ $sale->company->phone }}
                    @if ($sale->company->gst_number)
                        · GSTIN: {{ $sale->company->gst_number }}
                    @endif
                    @if ($sale->company->license_no)
                        · Lic: {{ $sale->company->license_no }}
                    @endif
                </div>
                <div class="font-bold text-base mt-3 tracking-[0.2em] text-brand uppercase">
                    Declaration / Undertaking
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm mb-6">
                <div><span class="text-muted">Declaration No.:</span> <strong
                        class="font-mono">{{ $declaration->declaration_no }}</strong></div>
                <div><span class="text-muted">Invoice No.:</span> <strong
                        class="font-mono">{{ $declaration->invoice_no }}</strong></div>
                <div><span class="text-muted">Date:</span> <strong>{{ $declaration->created_at->format('d M Y') }}</strong>
                </div>
            </div>

            <div class="text-sm leading-9 mb-10">
                <p>
                    I, <strong>{{ $declaration->buyer_name }}</strong>, proprietor / authorized signatory of
                    <strong>{{ $declaration->buyer_company }}</strong>, hereby declare that the material / product
                    purchased vide Invoice No. <strong>{{ $declaration->invoice_no }}</strong> from
                    <strong>{{ $sale->company->name }}</strong> will be used only for lawful purposes.
                </p>
                <p class="mt-4">
                    I confirm that the purchased material will not be used for manufacturing any prohibited,
                    banned, or illegal substance, nor will it be misused in any manner contrary to applicable
                    laws and regulations.
                </p>
            </div>

            <div class="grid grid-cols-3 gap-8 mt-16 text-sm text-center">
                <div class="border-t border-slate-400 pt-2 text-muted">Signature</div>
                <div>
                    <div class="font-semibold mb-1">{{ $declaration->buyer_name }}</div>
                    <div class="border-t border-slate-400 pt-2 text-muted">Name</div>
                </div>
                <div class="border-t border-slate-400 pt-2 text-muted">Company Stamp & Date</div>
            </div>

            <div class="mt-8 text-center text-[10px] text-muted border-t border-line pt-3">
                Generated by BatchDesk · {{ $sale->company->name }}
            </div>
        </div>
    @endif
@endsection
