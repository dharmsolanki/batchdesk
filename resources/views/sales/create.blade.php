@extends('layouts.app')
@section('title', 'New Invoice — BatchDesk')
@section('content')
<h1 class="font-bold text-2xl tracking-tight mb-4">New Invoice</h1>

@if (!$batches->count())
    <div class="card p-6 text-center text-sm text-muted">
        No released batches in stock. <a href="{{ route('batches.create') }}" class="text-brand font-semibold">Create a batch</a> and pass its tests first.
        <div class="text-[11px] text-muted mt-2">Only released (quality-passed) batches can be invoiced — this is enforced by design.</div>
    </div>
@else
<form method="POST" action="{{ route('sales.store') }}" x-data="billForm()" class="space-y-4">
    @csrf
    <div class="card p-4 space-y-3">
        <div class="section-title">Customer</div>
        <div class="grid grid-cols-2 gap-2.5">
            <input name="customer_name" value="{{ old('customer_name') }}" required placeholder="Customer / company name *" class="field">
            <input name="customer_phone" value="{{ old('customer_phone') }}" required inputmode="numeric" placeholder="Phone *" class="field">
        </div>
        <div class="grid grid-cols-2 gap-2.5">
            <input name="customer_gst" value="{{ old('customer_gst') }}" placeholder="Customer GSTIN" class="field">
            <input name="customer_address" value="{{ old('customer_address') }}" placeholder="Address" class="field">
        </div>
    </div>

    <div class="card p-4 space-y-3">
        <div class="section-title">Items <span class="text-muted normal-case font-medium tracking-normal">(released batches only)</span></div>
        <template x-for="(row, i) in rows" :key="i">
            <div class="flex gap-2 items-center">
                <select :name="'items[' + i + '][batch_id]'" x-model="row.batchId" @change="recalc()" class="field flex-1 min-w-0">
                    <option value="">Select batch</option>
                    @foreach ($batches as $b)
                        <option value="{{ $b->id }}" data-price="{{ $b->product->price }}" data-max="{{ $b->qty }}">
                            {{ $b->product->name }} · {{ $b->batch_no }} ({{ rtrim(rtrim(number_format((float) $b->qty, 3), '0'), '.') }} {{ $b->product->unit }} @ ₹{{ number_format((float) $b->product->price) }})
                        </option>
                    @endforeach
                </select>
                <input :name="'items[' + i + '][qty]'" x-model="row.qty" @input="recalc()" type="number" step="0.001" min="0.001" placeholder="Qty" class="field w-24">
                <button type="button" @click="rows.splice(i, 1); recalc()" x-show="rows.length > 1" class="text-danger font-bold px-1">✕</button>
            </div>
        </template>
        <button type="button" @click="rows.push({batchId: '', qty: ''})" class="text-brand font-semibold text-sm">+ Add item</button>
        <div class="text-right font-bold text-lg border-t border-line pt-2">Total: ₹<span x-text="total.toLocaleString('en-IN')"></span></div>
    </div>

    <div class="card p-4 space-y-3">
        <div class="section-title">Payment</div>
        <div class="grid grid-cols-2 gap-2.5">
            <div>
                <input name="paid_amount" x-model="paidAmount" type="number" step="0.01" min="0" required placeholder="Amount received *" class="field font-semibold">
                <button type="button" @click="paidAmount = total" class="text-[11px] text-brand font-semibold mt-1">Paid in full</button>
            </div>
            <select name="payment_mode" class="field h-fit">
                <option value="cash">Cash</option>
                <option value="upi">UPI</option>
                <option value="bank">Bank / NEFT</option>
                <option value="card">Card</option>
                <option value="credit">On credit</option>
            </select>
        </div>
        <input name="notes" placeholder="Notes (PO no., transport, etc.)" class="field">
    </div>

    <button class="btn-primary w-full py-3.5 rounded-xl text-base">Create invoice — ₹<span x-text="total.toLocaleString('en-IN')"></span></button>
</form>

<script>
function billForm() {
    return {
        rows: [{batchId: '', qty: ''}],
        total: 0,
        paidAmount: '',
        recalc() {
            let total = 0;
            this.rows.forEach((row, i) => {
                const sel = document.querySelectorAll('select[name^=items]')[i];
                if (sel) {
                    const opt = sel.options[sel.selectedIndex];
                    if (opt && opt.dataset.price) {
                        let qty = parseFloat(row.qty) || 0;
                        const max = parseFloat(opt.dataset.max) || 0;
                        if (qty > max) { qty = max; row.qty = max; }
                        total += parseFloat(opt.dataset.price) * qty;
                    }
                }
            });
            this.total = Math.round(total * 100) / 100;
        }
    }
}
</script>
@endif
@endsection
