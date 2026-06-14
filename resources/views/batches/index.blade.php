@extends('layouts.app')
@section('title', 'Batches — BatchDesk')
@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="font-bold text-2xl tracking-tight">Batches</h1>
    <a href="{{ route('batches.create') }}" class="btn-accent text-sm px-4 py-2">New batch</a>
</div>

<form method="GET" class="mb-3">
    <input name="q" value="{{ request('q') }}" placeholder="Search by batch no. or product" class="field">
</form>

<div class="card overflow-hidden">
    @forelse ($batches as $b)
        <a href="{{ route('batches.show', $b) }}" class="flex items-center justify-between px-4 py-3 border-b border-line/60 hover:bg-paper">
            <div>
                <div class="font-medium text-sm">{{ $b->product->name }}</div>
                <div class="text-[12px] text-muted"><span class="font-mono">{{ $b->batch_no }}</span> · Mfg {{ $b->mfg_date->format('d M Y') }} · {{ rtrim(rtrim(number_format((float) $b->qty, 3), '0'), '.') }} {{ $b->product->unit }} remaining</div>
            </div>
            @include('batches._status', ['status' => $b->status])
        </a>
    @empty
        <div class="px-4 py-10 text-center text-muted text-sm">No batches found.</div>
    @endforelse
</div>
<div class="mt-4">{{ $batches->withQueryString()->links() }}</div>
@endsection
