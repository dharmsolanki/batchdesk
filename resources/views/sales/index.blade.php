@extends('layouts.app')
@section('title', 'Invoices — BatchDesk')
@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="font-bold text-2xl tracking-tight">Invoices</h1>
    <a href="{{ route('sales.create') }}" class="btn-accent text-sm px-4 py-2">New invoice</a>
</div>

<form method="GET" class="mb-3">
    <input name="q" value="{{ request('q') }}" placeholder="Search by name, phone or invoice no." class="field">
</form>

<div class="card overflow-hidden">
    @forelse ($sales as $s)
        <a href="{{ route('sales.show', $s) }}" class="flex items-center justify-between px-4 py-3 border-b border-line/60 hover:bg-paper">
            <div>
                <div class="font-medium text-sm">{{ $s->customer->name }}</div>
                <div class="text-[12px] text-muted"><span class="font-mono">{{ $s->invoice_no }}</span> · {{ $s->created_at->format('d M Y') }}</div>
            </div>
            <div class="text-right">
                <div class="font-semibold text-sm">₹{{ number_format($s->total) }}</div>
                @if ($s->status === 'paid')
                    <span class="text-[10px] font-bold text-pass">PAID</span>
                @else
                    <span class="text-[10px] font-bold text-amber">₹{{ number_format($s->pending) }} DUE</span>
                @endif
            </div>
        </a>
    @empty
        <div class="px-4 py-10 text-center text-muted text-sm">No invoices found.</div>
    @endforelse
</div>
<div class="mt-4">{{ $sales->withQueryString()->links() }}</div>
@endsection
