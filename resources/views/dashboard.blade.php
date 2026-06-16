@extends('layouts.app')
@section('title', 'Dashboard — BatchDesk')
@section('content')

{{-- Onboarding checklist (shown until complete) --}}
@if (!$onboardingComplete)
<section class="card border-brand/30 p-5 mb-4">
    <div class="flex items-center justify-between mb-3">
        <div>
            <h2 class="font-bold text-base">Getting started</h2>
            <div class="text-xs text-muted">Complete these steps to get the most out of BatchDesk</div>
        </div>
        <div class="text-xs font-bold text-brand">
            {{ collect($checklist)->where('done', true)->count() }} / {{ count($checklist) }}
        </div>
    </div>
    @php $done = collect($checklist)->where('done', true)->count(); $total = count($checklist); @endphp
    <div class="h-1.5 bg-line rounded-full mb-4 overflow-hidden">
        <div class="h-full bg-brand rounded-full transition-all" style="width: {{ ($done / $total) * 100 }}%"></div>
    </div>
    <div class="space-y-2">
        @foreach ($checklist as $step)
        <div class="flex items-center gap-3 py-1.5">
            @if ($step['done'])
                <div class="w-5 h-5 rounded-full bg-pass flex items-center justify-center shrink-0">
                    <svg class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                </div>
                <span class="text-sm text-muted line-through">{{ $step['label'] }}</span>
            @else
                <div class="w-5 h-5 rounded-full border-2 border-line shrink-0"></div>
                <div class="flex-1 min-w-0">
                    <a href="{{ route($step['route']) }}" class="text-sm font-medium text-brand hover:underline">{{ $step['label'] }}</a>
                    <div class="text-[11px] text-muted">{{ $step['hint'] }}</div>
                </div>
                <a href="{{ route($step['route']) }}" class="text-[11px] font-bold text-brand border border-brand/30 rounded px-2 py-0.5 shrink-0">Go →</a>
            @endif
        </div>
        @endforeach
    </div>
</section>
@endif

<section class="bg-navy text-white rounded-xl p-5 mb-4">
    <div class="text-cyan-200 text-[11px] font-semibold uppercase tracking-widest">Today's sales · {{ now()->format('d M Y') }}</div>
    <div class="font-bold text-4xl tracking-tight mt-1">₹{{ number_format($todaySales) }}</div>
    <div class="text-slate-300 text-sm mt-1">This month: ₹{{ number_format($monthSales) }}</div>
    <div class="grid grid-cols-3 gap-2 mt-4 text-center">
        <a href="{{ route('batches.index') }}" class="bg-white/10 rounded-lg py-2.5">
            <div class="font-bold text-lg">{{ $testingCount }}</div>
            <div class="text-[10px] text-slate-300 uppercase tracking-wide">In testing</div>
        </a>
        <a href="{{ route('batches.index') }}" class="bg-white/10 rounded-lg py-2.5">
            <div class="font-bold text-lg">{{ $releasedQty }}</div>
            <div class="text-[10px] text-slate-300 uppercase tracking-wide">Released</div>
        </a>
        <a href="{{ route('sales.index') }}" class="bg-white/10 rounded-lg py-2.5">
            <div class="font-bold text-lg {{ $pendingTotal > 0 ? 'text-amber-300' : '' }}">₹{{ number_format($pendingTotal) }}</div>
            <div class="text-[10px] text-slate-300 uppercase tracking-wide">{{ $pendingCount }} unpaid</div>
        </a>
    </div>
</section>

<section class="grid grid-cols-2 gap-3 mb-4">
    <a href="{{ route('batches.create') }}" class="btn-accent text-center py-3.5 rounded-xl">
        New Batch
        <div class="text-[11px] font-normal text-cyan-100 mt-0.5">Production entry &amp; traceability</div>
    </a>
    <a href="{{ route('materials.index') }}" class="card text-center py-3.5 font-semibold">
        Receive Material
        <div class="text-[11px] font-normal text-muted mt-0.5">Raw material lots</div>
    </a>
</section>

@if ($expiringSoon->count())
<section class="card border-amber-300 bg-amber-50 p-4 mb-4">
    <div class="text-sm font-semibold text-amber mb-1">Batches expiring within 45 days</div>
    @foreach ($expiringSoon as $b)
        <a href="{{ route('batches.show', $b) }}" class="flex justify-between py-1 text-sm text-amber-900">
            <span>{{ $b->product->name }} · <span class="font-mono text-[13px]">{{ $b->batch_no }}</span></span>
            <span class="font-semibold">{{ $b->exp_date->format('d M Y') }}</span>
        </a>
    @endforeach
</section>
@endif

<section class="card overflow-hidden mb-4">
    <div class="px-4 py-3 border-b border-line flex justify-between items-center">
        <h2 class="font-semibold">Recent batches</h2>
        <a href="{{ route('batches.index') }}" class="text-brand text-sm font-semibold">View all</a>
    </div>
    @forelse ($recentBatches as $b)
        <a href="{{ route('batches.show', $b) }}" class="flex items-center justify-between px-4 py-3 border-b border-line/60 hover:bg-paper">
            <div>
                <div class="font-medium text-sm">{{ $b->product->name }}</div>
                <div class="text-[12px] text-muted"><span class="font-mono">{{ $b->batch_no }}</span> · Mfg {{ $b->mfg_date->format('d M Y') }}</div>
            </div>
            @include('batches._status', ['status' => $b->status])
        </a>
    @empty
        <div class="px-4 py-8 text-center text-muted text-sm">
            No batches yet. Start by creating a <a href="{{ route('products.index') }}" class="text-brand font-semibold">product and its specification</a>.
        </div>
    @endforelse
</section>

<section class="card overflow-hidden">
    <div class="px-4 py-3 border-b border-line flex justify-between items-center">
        <h2 class="font-semibold">Recent invoices</h2>
        <a href="{{ route('sales.index') }}" class="text-brand text-sm font-semibold">View all</a>
    </div>
    @forelse ($recentSales as $s)
        <a href="{{ route('sales.show', $s) }}" class="flex items-center justify-between px-4 py-3 border-b border-line/60 hover:bg-paper">
            <div>
                <div class="font-medium text-sm">{{ $s->customer->name }}</div>
                <div class="text-[12px] text-muted"><span class="font-mono">{{ $s->invoice_no }}</span> · {{ $s->created_at->format('d M') }}</div>
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
        <div class="px-4 py-6 text-center text-muted text-sm">No invoices yet.</div>
    @endforelse
</section>
@endsection
