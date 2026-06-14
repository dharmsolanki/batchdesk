<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $sales = Sale::with('customer')
            ->when($request->q, function ($query, $q) {
                $query->where('invoice_no', 'like', "%{$q}%")
                    ->orWhereHas('customer', fn ($c) => $c->where('name', 'like', "%{$q}%")->orWhere('phone', 'like', "%{$q}%"));
            })
            ->latest()->paginate(20);

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $batches = Batch::with('product')->where('status', 'released')->where('qty', '>', 0)->latest()->get();
        return view('sales.create', compact('batches'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name'    => 'required|string|max:120',
            'customer_phone'   => 'required|string|max:20',
            'customer_gst'     => 'nullable|string|max:20',
            'customer_address' => 'nullable|string|max:255',
            'items'            => 'required|array|min:1',
            'items.*.batch_id' => 'required|exists:batches,id',
            'items.*.qty'      => 'required|numeric|min:0.001',
            'paid_amount'      => 'required|numeric|min:0',
            'payment_mode'     => 'required|in:cash,upi,bank,card,credit',
            'notes'            => 'nullable|string|max:255',
        ]);

        $sale = DB::transaction(function () use ($data) {
            $customer = Customer::firstOrCreate(
                ['phone' => $data['customer_phone'], 'company_id' => Auth::user()->company_id],
                ['name' => $data['customer_name'], 'gst_number' => $data['customer_gst'] ?? null, 'address' => $data['customer_address'] ?? null]
            );
            if ($customer->name !== $data['customer_name']) {
                $customer->update(['name' => $data['customer_name']]);
            }

            $invoiceNo = 'INV-' . str_pad(
                (string) (Sale::withoutGlobalScope('company')->where('company_id', Auth::user()->company_id)->count() + 1),
                4, '0', STR_PAD_LEFT
            );

            $sale = Sale::create([
                'customer_id' => $customer->id,
                'user_id'     => Auth::id(),
                'invoice_no'  => $invoiceNo,
                'paid_amount' => 0,
                'notes'       => $data['notes'] ?? null,
            ]);

            $subtotal = 0;
            $gstTotal = 0;

            foreach ($data['items'] as $row) {
                $batch = Batch::with('product')->where('status', 'released')->findOrFail($row['batch_id']);
                $qty = min((float) $row['qty'], (float) $batch->qty);
                if ($qty <= 0) continue;

                // Price is BASE price (GST exclusive)
                $basePrice   = (float) $batch->product->price; // per unit, excl. GST
                $gstRate     = (float) $batch->product->gst_rate;
                $lineBase    = round($basePrice * $qty, 2);
                $lineGst     = round($lineBase * $gstRate / 100, 2);
                $lineTotal   = round($lineBase + $lineGst, 2);

                SaleItem::create([
                    'sale_id'     => $sale->id,
                    'batch_id'    => $batch->id,
                    'description' => $batch->product->name . ' | Batch: ' . $batch->batch_no . ($batch->exp_date ? ' | Exp: ' . $batch->exp_date->format('m/Y') : ''),
                    'qty'         => $qty,
                    'unit'        => $batch->product->unit,
                    'unit_price'  => $basePrice,
                    'gst_rate'    => $gstRate,
                    'gst_amount'  => $lineGst,
                    'line_total'  => $lineTotal,
                ]);

                $batch->decrement('qty', $qty);
                $subtotal += $lineBase;
                $gstTotal += $lineGst;
            }

            $total  = round($subtotal + $gstTotal, 2);
            $paid   = min((float) $data['paid_amount'], $total);
            $status = $paid >= $total ? 'paid' : ($paid > 0 ? 'partial' : 'pending');

            if ($paid > 0) {
                Payment::create([
                    'sale_id' => $sale->id,
                    'amount'  => $paid,
                    'mode'    => $data['payment_mode'],
                    'note'    => 'At billing',
                ]);
            }

            $sale->update([
                'subtotal'    => round($subtotal, 2),
                'gst_amount'  => round($gstTotal, 2),
                'total'       => $total,
                'paid_amount' => $paid,
                'status'      => $status,
            ]);

            return $sale;
        });

        return redirect()->route('sales.show', $sale)
            ->with('success', 'Invoice created. Batch numbers are included — COA links can be shared with it.');
    }

    public function show(Sale $sale)
    {
        $sale->load('items.batch', 'customer', 'company', 'payments');
        return view('sales.invoice', compact('sale'));
    }

    public function addPayment(Request $request, Sale $sale)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:1',
            'mode'   => 'required|in:cash,upi,card,bank,credit',
        ]);

        $amount = min((float) $data['amount'], $sale->pending);
        if ($amount <= 0) {
            return back()->withErrors(['amount' => 'No outstanding balance on this invoice.']);
        }

        DB::transaction(function () use ($sale, $amount, $data) {
            Payment::create(['sale_id' => $sale->id, 'amount' => $amount, 'mode' => $data['mode']]);
            $paid = (float) $sale->paid_amount + $amount;
            $sale->update([
                'paid_amount' => $paid,
                'status'      => $paid >= (float) $sale->total ? 'paid' : 'partial',
            ]);
        });

        return back()->with('success', 'Payment recorded.');
    }
}
