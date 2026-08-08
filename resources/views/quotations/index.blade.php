@extends('layouts.app')
@section('title', 'Proforma Invoices — BatchDesk')
@section('content')

    <div class="flex items-center justify-between mb-4">
        <h1 class="font-bold text-2xl tracking-tight">Proforma Invoice</h1>
        <a href="{{ route('quotations.create') }}" class="btn-accent px-4 py-2 text-sm">+ New Proforma Invoice</a>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 text-sm rounded-xl px-4 py-3 mb-4">
            {{ session('success') }}</div>
    @endif

    <div class="card overflow-hidden">
        @forelse ($quotations as $q)
            <div class="flex items-center justify-between px-4 py-3 border-b border-line/60">
                <div>
                    <div class="font-semibold text-sm font-mono">{{ $q->quotation_no }}</div>
                    <div class="text-xs text-muted">{{ $q->customer->name }} · Valid till
                        {{ $q->valid_until->format('d M Y') }}</div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="font-semibold text-sm">₹{{ number_format($q->total, 2) }}</span>
                    <a href="{{ route('quotations.show', $q) }}" class="text-brand text-xs font-bold">View →</a>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-muted text-sm">No Proforma Invoices yet. <a
                    href="{{ route('quotations.create') }}" class="text-brand font-semibold">Create one</a></div>
        @endforelse
    </div>

    {{ $quotations->links() }}
@endsection
