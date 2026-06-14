@extends('admin.layout')
@section('title', 'All Companies')
@section('content')

<div class="flex items-center gap-3 mb-4">
    <form method="GET" class="flex gap-2 flex-1">
        <input name="q" value="{{ request('q') }}" placeholder="Search by name, phone, city..." class="field flex-1 max-w-xs">
        <select name="status" class="field w-36" onchange="this.form.submit()">
            <option value="">All status</option>
            <option value="trial"      {{ request('status') === 'trial'      ? 'selected' : '' }}>Trial</option>
            <option value="subscribed" {{ request('status') === 'subscribed' ? 'selected' : '' }}>Subscribed</option>
            <option value="expired"    {{ request('status') === 'expired'    ? 'selected' : '' }}>Expired</option>
        </select>
        <button class="btn btn-ghost px-4">Search</button>
    </form>
</div>

<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-[11px] text-muted uppercase tracking-wide border-b border-line bg-paper">
                <th class="px-4 py-3 font-semibold">Company</th>
                <th class="px-4 py-3 font-semibold">Contact</th>
                <th class="px-4 py-3 font-semibold">Registered</th>
                <th class="px-4 py-3 font-semibold">Status</th>
                <th class="px-4 py-3 font-semibold">Trial ends</th>
                <th class="px-4 py-3 font-semibold"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($companies as $c)
            <tr class="border-b border-line/60 hover:bg-paper">
                <td class="px-4 py-3">
                    <div class="font-medium">{{ $c->name }}</div>
                    <div class="text-[11px] text-muted">{{ $c->city }}{{ $c->gst_number ? ' · ' . $c->gst_number : '' }}</div>
                </td>
                <td class="px-4 py-3">
                    <div>{{ $c->phone }}</div>
                    <div class="text-[11px] text-muted">{{ $c->users->first()?->email }}</div>
                </td>
                <td class="px-4 py-3 text-muted">{{ $c->created_at->format('d M Y') }}</td>
                <td class="px-4 py-3">
                    @php $status = $c->statusLabel(); @endphp
                    <span class="text-[10px] font-bold px-2 py-1 rounded border
                        @if($status === 'Subscribed') text-pass bg-emerald-50 border-emerald-200
                        @elseif($status === 'Trial') text-brand bg-cyan-50 border-cyan-200
                        @else text-danger bg-red-50 border-red-200 @endif">
                        {{ strtoupper($status) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-muted font-mono text-[12px]">
                    @if($c->trial_ends_at)
                        {{ $c->trial_ends_at->format('d M Y') }}
                        @if($c->isTrialActive())
                            <span class="text-[10px] text-amber font-sans">({{ $c->trialDaysLeft() }}d left)</span>
                        @endif
                    @else —
                    @endif
                </td>
                <td class="px-4 py-3">
                    <a href="{{ route('admin.companies.show', $c) }}" class="text-brand text-xs font-semibold">Manage →</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-10 text-center text-muted">No companies found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-line">{{ $companies->withQueryString()->links() }}</div>
</div>
@endsection
