@extends('admin.layout')
@section('title', 'Dashboard')
@section('content')

{{-- Stat cards --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    @php
    $cards = [
        ['Total companies', $stats['total'],      'ink',    ''],
        ['Active trials',   $stats['trial'],      'brand',  ''],
        ['Subscribed',      $stats['subscribed'], 'pass',   ''],
        ['Expired',         $stats['expired'],    'danger', ''],
    ];
    @endphp
    @foreach ($cards as $c)
    <div class="card p-5">
        <div class="text-xs font-semibold text-muted uppercase tracking-wide">{{ $c[0] }}</div>
        <div class="font-bold text-3xl mt-1 text-{{ $c[2] }}">{{ $c[1] }}</div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-3 gap-5">
    {{-- Expiring soon --}}
    <div class="card overflow-hidden col-span-1">
        <div class="px-4 py-3 border-b border-line flex items-center justify-between">
            <h2 class="font-semibold text-sm">⏰ Trial expiring in 7 days</h2>
            <span class="text-[11px] font-bold text-amber bg-amber-50 border border-amber-200 rounded px-2 py-0.5">{{ $expiringSoon->count() }}</span>
        </div>
        @forelse ($expiringSoon as $c)
        <a href="{{ route('admin.companies.show', $c) }}" class="flex items-center justify-between px-4 py-2.5 border-b border-line/60 hover:bg-paper text-sm">
            <div>
                <div class="font-medium">{{ $c->name }}</div>
                <div class="text-xs text-muted">{{ $c->city }}</div>
            </div>
            <div class="text-xs font-bold text-amber">{{ $c->trialDaysLeft() }}d left</div>
        </a>
        @empty
        <div class="px-4 py-6 text-center text-muted text-sm">No trials expiring soon.</div>
        @endforelse
    </div>

    {{-- Recent signups --}}
    <div class="card overflow-hidden col-span-2">
        <div class="px-4 py-3 border-b border-line flex items-center justify-between">
            <h2 class="font-semibold text-sm">Recent signups</h2>
            <a href="{{ route('admin.companies') }}" class="text-xs text-brand font-semibold">View all →</a>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-[11px] text-muted uppercase tracking-wide border-b border-line bg-paper">
                    <th class="px-4 py-2.5 font-semibold">Company</th>
                    <th class="px-4 py-2.5 font-semibold">City</th>
                    <th class="px-4 py-2.5 font-semibold">Registered</th>
                    <th class="px-4 py-2.5 font-semibold">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recent as $c)
                <tr class="border-b border-line/60 hover:bg-paper">
                    <td class="px-4 py-2.5">
                        <a href="{{ route('admin.companies.show', $c) }}" class="font-medium text-brand hover:underline">{{ $c->name }}</a>
                        <div class="text-[11px] text-muted">{{ $c->users->first()?->email }}</div>
                    </td>
                    <td class="px-4 py-2.5 text-muted">{{ $c->city ?: '—' }}</td>
                    <td class="px-4 py-2.5 text-muted">{{ $c->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-2.5">
                        @php $status = $c->statusLabel(); @endphp
                        <span class="text-[10px] font-bold px-2 py-1 rounded border
                            @if($status === 'Subscribed') text-pass bg-emerald-50 border-emerald-200
                            @elseif($status === 'Trial') text-brand bg-cyan-50 border-cyan-200
                            @else text-danger bg-red-50 border-red-200 @endif">
                            {{ strtoupper($status) }}
                            @if($status === 'Trial') · {{ $c->trialDaysLeft() }}d @endif
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
