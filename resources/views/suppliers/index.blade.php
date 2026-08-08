@extends('layouts.app')
@section('title', 'Suppliers — BatchDesk')
@section('content')

    <div class="flex items-center justify-between mb-4">
        <h1 class="font-bold text-2xl tracking-tight">Suppliers</h1>
        <a href="{{ route('suppliers.create') }}" class="btn-accent px-4 py-2 text-sm">+ New Supplier</a>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 text-sm rounded-xl px-4 py-3 mb-4">
            {{ session('success') }}</div>
    @endif

    <div class="card overflow-hidden">
        @forelse ($suppliers as $s)
            <div class="flex items-center justify-between px-4 py-3 border-b border-line/60">
                <div>
                    <div class="font-semibold text-sm">{{ $s->name }}</div>
                    <div class="text-xs text-muted">
                        {{ $s->city ?: '' }}{{ $s->phone ? ' · ' . $s->phone : '' }}
                        · {{ $s->products_count }} product(s)
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if ($s->payment_terms)
                        <span
                            class="text-xs text-muted border border-line rounded px-2 py-0.5">{{ $s->payment_terms }}</span>
                    @endif
                    <a href="{{ route('suppliers.show', $s) }}" class="text-brand text-xs font-bold">View →</a>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-muted text-sm">
                No suppliers yet.
                <a href="{{ route('suppliers.create') }}" class="text-brand font-semibold">Add one</a>
            </div>
        @endforelse
    </div>

    {{ $suppliers->links() }}
@endsection
