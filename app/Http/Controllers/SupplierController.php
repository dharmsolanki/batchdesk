<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierCoa;
use App\Models\SupplierProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount('products')->latest()->paginate(20);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:120',
            'phone'         => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:120',
            'gst_number'    => 'nullable|string|max:20',
            'address'       => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:60',
            'payment_terms' => 'nullable|string|max:120',
            'notes'         => 'nullable|string',
        ]);

        $supplier = Supplier::create([...$data, 'company_id' => Auth::user()->company_id]);

        return redirect()->route('suppliers.show', $supplier)
            ->with('success', 'Supplier added.');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load('products.coas');
        return view('suppliers.show', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:120',
            'phone'         => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:120',
            'gst_number'    => 'nullable|string|max:20',
            'address'       => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:60',
            'payment_terms' => 'nullable|string|max:120',
            'notes'         => 'nullable|string',
        ]);
        $supplier->update($data);

        return back()->with('success', 'Supplier updated.');
    }

    // ── Supplier Products ──────────────────────────────
    public function storeProduct(Request $request, Supplier $supplier)
    {
        $data = $request->validate([
            'product_name' => 'required|string|max:120',
            'hsn'          => 'nullable|string|max:20',
            'unit'         => 'required|string|max:20',
            'rate'         => 'nullable|numeric|min:0',
            'gst_rate'     => 'required|numeric|min:0|max:28',
            'notes'        => 'nullable|string|max:255',
        ]);
        $supplier->products()->create($data);

        return back()->with('success', 'Product added.');
    }

    public function updateProduct(Request $request, Supplier $supplier, SupplierProduct $product)
    {
        abort_unless($product->supplier_id === $supplier->id, 404);
        $data = $request->validate([
            'product_name' => 'required|string|max:120',
            'hsn'          => 'nullable|string|max:20',
            'unit'         => 'required|string|max:20',
            'rate'         => 'nullable|numeric|min:0',
            'gst_rate'     => 'required|numeric|min:0|max:28',
            'notes'        => 'nullable|string|max:255',
        ]);
        $product->update($data);

        return back()->with('success', 'Product updated.');
    }

    // ── Supplier COAs ──────────────────────────────────
    public function storeCoa(Request $request, Supplier $supplier, SupplierProduct $product)
    {
        abort_unless($product->supplier_id === $supplier->id, 404);
        $data = $request->validate([
            'lot_no'        => 'required|string|max:60',
            'received_date' => 'nullable|date',
            'expiry_date'   => 'nullable|date',
            'coa_status'    => 'required|in:pending,received,verified',
            'notes'         => 'nullable|string|max:255',
        ]);
        $product->coas()->create([...$data, 'supplier_id' => $supplier->id]);

        return back()->with('success', 'COA lot added.');
    }

    public function updateCoa(Request $request, SupplierCoa $coa)
    {
        $data = $request->validate([
            'coa_status' => 'required|in:pending,received,verified',
            'notes'      => 'nullable|string|max:255',
        ]);
        $coa->update($data);

        return back()->with('success', 'COA updated.');
    }
}
