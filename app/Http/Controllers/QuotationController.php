<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public function index()
    {
        $quotations = Quotation::with('customer')->latest()->paginate(20);
        return view('quotations.index', compact('quotations'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products  = Product::orderBy('name')->get();
        return view('quotations.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'new_customer_name'   => 'required_without:customer_id|nullable|string|max:120',
            'new_customer_phone'  => 'required_without:customer_id|nullable|string|max:20',
            'new_customer_gst'    => 'nullable|string|max:20',
            'new_customer_address' => 'nullable|string|max:255',
            'valid_until'  => 'required|date|after:today',
            'terms'        => 'nullable|string',
            'notes'        => 'nullable|string|max:255',
            'items'        => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.hsn'         => 'nullable|string|max:20',
            'items.*.qty'         => 'required|numeric|min:0.001',
            'items.*.unit'        => 'required|string|max:20',
            'items.*.unit_price'  => 'required|numeric|min:0',
            'items.*.gst_rate'    => 'required|numeric|min:0|max:28',
        ]);

        $customerId = $request->filled('customer_id') ? $request->customer_id : null;

        if (!$customerId && $request->filled('new_customer_name')) {
            $customer = \App\Models\Customer::create([
                'company_id' => Auth::user()->company_id,
                'name'       => $request->new_customer_name,
                'phone'      => $request->new_customer_phone,
                'gst_number' => $request->new_customer_gst  ?: null,
                'address'    => $request->new_customer_address ?: null,
            ]);
            $customerId = $customer->id;
        }

        if (!$customerId) {
            return back()->withErrors(['customer_id' => 'Customer required.'])->withInput();
        }

        $quotation = DB::transaction(function () use ($data, $customerId) {
            $no = 'PI-' . str_pad(
                Quotation::withoutGlobalScope('company')
                    ->where('company_id', Auth::user()->company_id)->count() + 1,
                4,
                '0',
                STR_PAD_LEFT
            );

            $quotation = Quotation::create([
                'company_id'   => Auth::user()->company_id,
                'customer_id'  => $customerId,
                'user_id'      => Auth::id(),
                'quotation_no' => $no,
                'valid_until'  => $data['valid_until'],
                'terms'        => $data['terms'] ?? null,
                'notes'        => $data['notes'] ?? null,
                'status'       => 'draft',
            ]);

            $subtotal = $gstTotal = 0;

            foreach ($data['items'] as $row) {
                $lineBase = round($row['unit_price'] * $row['qty'], 2);
                $lineGst  = round($lineBase * $row['gst_rate'] / 100, 2);
                $lineTotal = round($lineBase + $lineGst, 2);

                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'description'  => $row['description'],
                    'hsn'          => $row['hsn'] ?? null,
                    'qty'          => $row['qty'],
                    'unit'         => $row['unit'],
                    'unit_price'   => $row['unit_price'],
                    'gst_rate'     => $row['gst_rate'],
                    'gst_amount'   => $lineGst,
                    'line_total'   => $lineTotal,
                ]);

                $subtotal  += $lineBase;
                $gstTotal  += $lineGst;
            }

            $quotation->update([
                'subtotal'   => round($subtotal, 2),
                'gst_amount' => round($gstTotal, 2),
                'total'      => round($subtotal + $gstTotal, 2),
            ]);

            return $quotation;
        });

        return redirect()->route('quotations.show', $quotation)
            ->with('success', 'Proforma Invoice created.');
    }

    public function show(Quotation $quotation)
    {
        $quotation->load('items', 'customer', 'company');
        return view('quotations.show', compact('quotation'));
    }

    public function updateStatus(Request $request, Quotation $quotation)
    {
        $data = $request->validate([
            'status' => 'required|in:draft,sent,accepted,rejected',
        ]);
        $quotation->update($data);
        return back()->with('success', 'Status updated.');
    }

    public function destroy(Quotation $quotation)
    {
        $quotation->delete();
        return redirect()->route('quotations.index')
            ->with('success', 'Proforma Invoice deleted.');
    }
}
