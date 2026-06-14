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

    {{-- Customer --}}
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

    {{-- Items --}}
    <div class="card p-4 space-y-3">
        <div class="section-title">Items</div>

        <template x-for="(row, i) in rows" :key="i">
            <div class="border border-line rounded-xl p-3 space-y-2">
                <div class="flex gap-2 items-center">
                    <select :name="'items[' + i + '][batch_id]'"
                            x-model="row.batchId"
                            @change="onBatchChange(i)"
                            class="field flex-1 min-w-0">
                        <option value="">— Select batch —</option>
                        @foreach ($batches as $b)
                            <option value="{{ $b->id }}"
                                    data-price="{{ $b->product->price }}"
                                    data-gst="{{ $b->product->gst_rate }}"
                                    data-max="{{ $b->qty }}"
                                    data-unit="{{ $b->product->unit }}">
                                {{ $b->product->name }} · {{ $b->batch_no }}
                                ({{ rtrim(rtrim(number_format((float) $b->qty, 3), '0'), '.') }} {{ $b->product->unit }} available)
                            </option>
                        @endforeach
                    </select>
                    <button type="button" @click="rows.splice(i, 1); recalc()"
                            x-show="rows.length > 1"
                            class="text-danger font-bold px-2 text-lg">✕</button>
                </div>

                {{-- Price info row --}}
                <div class="grid grid-cols-3 gap-2" x-show="row.batchId">
                    <div>
                        <div class="text-[11px] text-muted mb-1">Qty (<span x-text="row.unit || 'unit'"></span>) *</div>
                        <input :name="'items[' + i + '][qty]'"
                               x-model="row.qty"
                               @input="recalc()"
                               type="number" step="0.001" min="0.001"
                               placeholder="0"
                               class="field text-sm">
                        <div class="text-[10px] text-muted mt-0.5">Max: <span x-text="row.maxQty"></span></div>
                    </div>
                    <div>
                        <div class="text-[11px] text-muted mb-1">Rate (excl. GST)</div>
                        <div class="field bg-paper text-sm font-mono">₹<span x-text="row.price"></span></div>
                    </div>
                    <div>
                        <div class="text-[11px] text-muted mb-1">GST</div>
                        <div class="field bg-paper text-sm font-mono"><span x-text="row.gst"></span>%</div>
                    </div>
                </div>

                {{-- Line total --}}
                <div x-show="row.batchId && row.qty > 0" class="bg-paper rounded-lg px-3 py-2 text-sm flex justify-between">
                    <span class="text-muted">
                        <span x-text="row.qty"></span> ×
                        ₹<span x-text="row.price"></span> +
                        <span x-text="row.gst"></span>% GST
                    </span>
                    <span class="font-bold">₹<span x-text="row.lineTotal.toLocaleString('en-IN')"></span></span>
                </div>
            </div>
        </template>

        <button type="button" @click="rows.push({batchId:'',qty:'',price:0,gst:0,maxQty:0,unit:'',lineTotal:0})"
                class="text-brand font-semibold text-sm">+ Add item</button>

        {{-- Grand total --}}
        <div class="border-t border-line pt-3 space-y-1 text-sm">
            <div class="flex justify-between text-muted"><span>Subtotal (excl. GST)</span><span>₹<span x-text="subtotal.toLocaleString('en-IN')"></span></span></div>
            <div class="flex justify-between text-muted"><span>GST</span><span>₹<span x-text="gstAmt.toLocaleString('en-IN')"></span></span></div>
            <div class="flex justify-between font-bold text-base border-t border-line pt-1.5">
                <span>Total (incl. GST)</span>
                <span>₹<span x-text="total.toLocaleString('en-IN')"></span></span>
            </div>
        </div>
    </div>

    {{-- Payment --}}
    <div class="card p-4 space-y-3">
        <div class="section-title">Payment</div>
        <div class="grid grid-cols-2 gap-2.5">
            <div>
                <label class="text-xs font-medium text-muted block mb-1">Amount received *</label>
                <input name="paid_amount" x-model="paidAmount" type="number" step="0.01" min="0"
                       required placeholder="0" class="field font-semibold">
                <button type="button" @click="paidAmount = total"
                        class="text-[11px] text-brand font-semibold mt-1">
                    Paid in full (₹<span x-text="total.toLocaleString('en-IN')"></span>)
                </button>
            </div>
            <div>
                <label class="text-xs font-medium text-muted block mb-1">Payment mode *</label>
                <select name="payment_mode" class="field">
                    <option value="cash">Cash</option>
                    <option value="upi">UPI</option>
                    <option value="bank">Bank / NEFT</option>
                    <option value="card">Card</option>
                    <option value="credit">On credit</option>
                </select>
            </div>
        </div>
        <input name="notes" placeholder="Notes (PO no., transport, etc.)" class="field">
    </div>

    <button class="btn-primary w-full py-3.5 rounded-xl text-base">
        Create invoice — ₹<span x-text="total.toLocaleString('en-IN')"></span>
    </button>
</form>

<script>
function billForm() {
    return {
        rows: [{ batchId: '', qty: '', price: 0, gst: 0, maxQty: 0, unit: '', lineTotal: 0 }],
        subtotal: 0,
        gstAmt: 0,
        total: 0,
        paidAmount: '',

        onBatchChange(i) {
            const selects = document.querySelectorAll('select[name^="items"]');
            const opt = selects[i]?.options[selects[i].selectedIndex];
            if (opt && opt.dataset.price) {
                this.rows[i].price  = parseFloat(opt.dataset.price) || 0;
                this.rows[i].gst    = parseFloat(opt.dataset.gst)   || 0;
                this.rows[i].maxQty = parseFloat(opt.dataset.max)   || 0;
                this.rows[i].unit   = opt.dataset.unit || '';
            }
            this.recalc();
        },

        recalc() {
            let sub = 0, gst = 0;
            this.rows.forEach(row => {
                let qty = parseFloat(row.qty) || 0;
                if (qty > row.maxQty && row.maxQty > 0) {
                    row.qty = row.maxQty;
                    qty = row.maxQty;
                }
                const lineBase = Math.round(row.price * qty * 100) / 100;
                const lineGst  = Math.round(lineBase * row.gst / 100 * 100) / 100;
                row.lineTotal  = Math.round((lineBase + lineGst) * 100) / 100;
                sub += lineBase;
                gst += lineGst;
            });
            this.subtotal = Math.round(sub * 100) / 100;
            this.gstAmt   = Math.round(gst * 100) / 100;
            this.total    = Math.round((sub + gst) * 100) / 100;
        }
    }
}
</script>
@endif
@endsection
