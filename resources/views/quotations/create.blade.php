@extends('layouts.app')
@section('title', 'New Proforma — BatchDesk')
@section('content')

    <h1 class="font-bold text-2xl tracking-tight mb-4">New Proforma Invoice</h1>
    <form method="POST" action="{{ route('quotations.store') }}" x-data="qtForm()" class="space-y-4">
        @csrf

        {{-- Customer --}}
        <div class="card p-4 space-y-3" x-data="{ newCustomer: false }">
            <div class="section-title">Customer</div>

            <div x-show="!newCustomer">
                <select name="customer_id" :required="!newCustomer" class="field">
                    <option value="">— Select existing customer —</option>
                    @foreach ($customers as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} {{ $c->phone ? '· ' . $c->phone : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div x-show="newCustomer" class="grid grid-cols-2 gap-2">
                <input name="new_customer_name" placeholder="Customer name *" class="field text-sm">
                <input name="new_customer_phone" placeholder="Phone *" class="field text-sm">
                <input name="new_customer_gst" placeholder="GSTIN" class="field text-sm">
                <input name="new_customer_address" placeholder="Address" class="field text-sm">
            </div>

            <button type="button" @click="newCustomer = !newCustomer" class="text-brand text-xs font-semibold"
                x-text="newCustomer ? '← Select existing customer' : '+ New customer'">
            </button>
        </div>

        {{-- Validity + Terms --}}
        <div class="card p-4 space-y-3">
            <div class="section-title">Details</div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="label">Valid until *</label>
                    <input name="valid_until" type="date" required class="field"
                        value="{{ now()->addDays(15)->format('Y-m-d') }}">
                </div>
                <div>
                    <label class="label">Notes</label>
                    <input name="notes" placeholder="Internal notes" class="field">
                </div>
            </div>
            <div>
                <label class="label">Terms & Conditions</label>
                <textarea name="terms" rows="3" class="field text-sm" placeholder="Payment terms, delivery, etc.">Prices are subject to change without prior notice.
                Delivery: Ex-works.
                Payment: Advance / As agreed.</textarea>
            </div>
        </div>

        {{-- Items --}}
        <div class="card p-4 space-y-3">
            <div class="section-title">Items</div>

            <template x-for="(row, i) in rows" :key="i">
                <div class="border border-line rounded-xl p-3 space-y-2">
                    <div class="flex gap-2">
                        <input :name="'items[' + i + '][description]'" x-model="row.description"
                            placeholder="Product / material description *" class="field flex-1 text-sm">
                        <button type="button" @click="rows.splice(i,1); recalc()" x-show="rows.length > 1"
                            class="text-danger font-bold px-2">✕</button>
                    </div>
                    <div class="grid grid-cols-4 gap-2">
                        <div>
                            <div class="text-[11px] text-muted mb-1">HSN</div>
                            <input :name="'items[' + i + '][hsn]'" x-model="row.hsn" placeholder="HSN"
                                class="field text-sm">
                        </div>
                        <div>
                            <div class="text-[11px] text-muted mb-1">Qty *</div>
                            <input :name="'items[' + i + '][qty]'" x-model="row.qty" @input="recalc()" type="number"
                                step="0.001" min="0.001" class="field text-sm">
                        </div>
                        <div>
                            <div class="text-[11px] text-muted mb-1">Unit *</div>
                            <select :name="'items[' + i + '][unit]'" x-model="row.unit" class="field text-sm">
                                @foreach (['kg', 'ltr', 'pcs', 'box', 'mt'] as $u)
                                    <option value="{{ $u }}">{{ $u }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <div class="text-[11px] text-muted mb-1">GST %</div>
                            <select :name="'items[' + i + '][gst_rate]'" x-model="row.gst_rate" @change="recalc()"
                                class="field text-sm">
                                @foreach ([0, 5, 12, 18, 28] as $r)
                                    <option value="{{ $r }}">{{ $r }}%</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <div class="text-[11px] text-muted mb-1">Rate (excl. GST) *</div>
                        <input :name="'items[' + i + '][unit_price]'" x-model="row.unit_price" @input="recalc()"
                            type="number" step="0.01" min="0" class="field text-sm">
                    </div>
                    <div x-show="row.qty > 0 && row.unit_price > 0"
                        class="bg-paper rounded-lg px-3 py-2 text-sm flex justify-between">
                        <span class="text-muted">
                            <span x-text="row.qty"></span> ×
                            ₹<span x-text="row.unit_price"></span> +
                            <span x-text="row.gst_rate"></span>% GST
                        </span>
                        <span class="font-bold">₹<span x-text="row.lineTotal.toLocaleString('en-IN')"></span></span>
                    </div>
                </div>
            </template>

            <button type="button"
                @click="rows.push({description:'',hsn:'',qty:'',unit:'kg',unit_price:'',gst_rate:18,lineTotal:0})"
                class="text-brand font-semibold text-sm">+ Add item</button>

            <div class="border-t border-line pt-3 space-y-1 text-sm">
                <div class="flex justify-between text-muted"><span>Subtotal</span><span>₹<span
                            x-text="subtotal.toLocaleString('en-IN')"></span></span></div>
                <div class="flex justify-between text-muted"><span>GST</span><span>₹<span
                            x-text="gstAmt.toLocaleString('en-IN')"></span></span></div>
                <div class="flex justify-between font-bold text-base border-t border-line pt-1.5">
                    <span>Total</span>
                    <span>₹<span x-text="total.toLocaleString('en-IN')"></span></span>
                </div>
            </div>
        </div>

        <button class="btn-primary w-full py-3.5 rounded-xl text-base">
            Create Proforma Invoice — ₹<span x-text="total.toLocaleString('en-IN')"></span>
        </button>
    </form>

    <script>
        function qtForm() {
            return {
                rows: [{
                    description: '',
                    hsn: '',
                    qty: '',
                    unit: 'kg',
                    unit_price: '',
                    gst_rate: 18,
                    lineTotal: 0
                }],
                subtotal: 0,
                gstAmt: 0,
                total: 0,
                recalc() {
                    let sub = 0,
                        gst = 0;
                    this.rows.forEach(row => {
                        const base = Math.round(parseFloat(row.unit_price || 0) * parseFloat(row.qty || 0) * 100) /
                            100;
                        const g = Math.round(base * parseFloat(row.gst_rate || 0) / 100 * 100) / 100;
                        row.lineTotal = Math.round((base + g) * 100) / 100;
                        sub += base;
                        gst += g;
                    });
                    this.subtotal = Math.round(sub * 100) / 100;
                    this.gstAmt = Math.round(gst * 100) / 100;
                    this.total = Math.round((sub + gst) * 100) / 100;
                }
            }
        }
    </script>
@endsection
