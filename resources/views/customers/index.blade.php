@extends('layouts.app')
@section('title', 'Customers — BatchDesk')
@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="font-bold text-2xl tracking-tight">Customers</h1>
    <a href="{{ route('sales.index') }}" class="text-brand font-semibold text-sm">All invoices →</a>
</div>

<form method="GET" class="mb-3">
    <input name="q" value="{{ request('q') }}" placeholder="Search by name or phone" class="field">
</form>

<div class="card overflow-hidden">
    @forelse ($customers as $c)
        <a href="{{ route('customers.show', $c) }}" class="flex items-center justify-between px-4 py-3 border-b border-line/60 hover:bg-paper">
            <div>
                <div class="font-medium text-sm">{{ $c->name }}</div>
                <div class="text-[12px] text-muted">{{ $c->phone }}{{ $c->gst_number ? ' · ' . $c->gst_number : '' }}</div>
            </div>
            <div class="text-xs text-muted">{{ $c->sales_count }} {{ $c->sales_count === 1 ? 'invoice' : 'invoices' }} →</div>
        </a>
    @empty
        <div class="px-4 py-10 text-center text-muted text-sm">Customers are added automatically when you create their first invoice.</div>
    @endforelse
</div>
<div class="mt-4">{{ $customers->withQueryString()->links() }}</div>
@endsection
