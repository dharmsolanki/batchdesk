@extends('layouts.app')
@section('title', 'Raw Materials — BatchDesk')
@section('content')
    <a href="{{ route('products.index') }}" class="text-brand text-sm font-semibold">← Products</a>
    <h1 class="font-bold text-2xl tracking-tight mt-1 mb-1">Raw Materials</h1>
    <p class="text-sm text-muted mb-4">Receive supplier lots here — they link to batches for full traceability.</p>

    <div class="grid grid-cols-1 gap-4 mb-4">
        <div class="card p-4">
            <div class="section-title mb-2">New material</div>
            <form method="POST" action="{{ route('materials.store') }}" class="flex gap-2">
                @csrf
                <input name="name" required placeholder="Material name" class="field flex-1 min-w-0">
                <select name="unit" class="field w-24">
                    <option value="kg">kg</option>
                    <option value="ltr">ltr</option>
                    <option value="pcs">pcs</option>
                    <option value="mt">MT</option>
                </select>
                <button class="btn-accent px-4">Add</button>
            </form>
        </div>

        @if ($materials->count())
            <div class="card p-4">
                <div class="section-title mb-2">Receive lot</div>
                <form method="POST" action="{{ route('materials.lots.store') }}" class="grid grid-cols-2 gap-2.5">
                    @csrf
                    <select name="raw_material_id" required class="field col-span-2">
                        <option value="">Select material</option>
                        @foreach ($materials as $m)
                            <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->unit }})</option>
                        @endforeach
                    </select>
                    <input name="lot_no" required placeholder="Supplier lot / batch no." class="field">
                    <input name="supplier" placeholder="Supplier name" class="field">
                    <input name="qty" type="number" step="0.001" required placeholder="Quantity" class="field">
                    <div>
                        <input name="expiry" type="date" class="field">
                        <div class="text-[11px] text-muted mt-0.5">Material expiry (optional)</div>
                    </div>
                    <button class="btn-primary col-span-2">Receive lot</button>
                </form>
            </div>
        @endif
    </div>

    <div class="card overflow-hidden">
        <div class="px-4 py-3 border-b border-line font-semibold">In stock</div>
        @forelse ($materials as $m)
            <div class="px-4 py-4 border-b border-line/60">
                {{-- Material name + unit --}}
                <form method="POST" action="{{ route('materials.update', $m) }}" class="flex gap-2 mb-3">
                    @csrf @method('PATCH')
                    <input name="name" value="{{ $m->name }}" class="field text-sm flex-1 font-medium">
                    <select name="unit" class="field text-sm w-24">
                        @foreach (['kg', 'ltr', 'pcs', 'mt'] as $u)
                            <option value="{{ $u }}" {{ $m->unit === $u ? 'selected' : '' }}>{{ $u }}
                            </option>
                        @endforeach
                    </select>
                    <button class="btn-accent text-xs font-bold px-4">Save</button>
                </form>

                {{-- Lots under this material --}}
                @if ($m->lots->count())
                    <div class="pl-4 border-l-2 border-line space-y-2">
                        @foreach ($m->lots as $lot)
                            <form method="POST" action="{{ route('materials.lots.update', $lot) }}"
                                class="grid grid-cols-[1.3fr_2fr_0.8fr_1.5fr_auto] gap-2 items-end">

                                @csrf
                                @method('PATCH')

                                <input name="lot_no" value="{{ $lot->lot_no }}" placeholder="Lot no."
                                    class="field text-sm w-full">

                                <input name="supplier" value="{{ $lot->supplier }}" placeholder="Supplier"
                                    class="field text-sm w-full">

                                <input name="qty" type="number" step="0.001" value="{{ (float) $lot->qty }}"
                                    placeholder="Qty" class="field text-sm w-full">

                                <input name="expiry" type="date" value="{{ $lot->expiry?->format('Y-m-d') }}"
                                    class="field text-sm w-full min-w-[170px]">

                                <button type="submit" class="btn-accent px-3 whitespace-nowrap">
                                    Save
                                </button>
                            </form>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <div class="px-4 py-8 text-center text-muted text-sm">No materials yet — add one above.</div>
        @endforelse
    </div>
@endsection
