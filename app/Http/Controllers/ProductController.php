<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SpecParam;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::withCount('specParams')->orderBy('name')->get();
        return view('products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:120',
            'hsn'      => 'nullable|string|max:20',
            'unit'     => 'required|string|max:20',
            'price'    => 'required|numeric|min:0',
            'gst_rate' => 'required|numeric|min:0|max:28',
        ]);
        $product = Product::create($data);

        return redirect()->route('products.show', $product)->with('success', 'Product created. Now add specification parameters for the COA.');
    }

    public function show(Product $product)
    {
        $product->load('specParams');
        return view('products.show', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'price'    => 'required|numeric|min:0',
            'gst_rate' => 'required|numeric|min:0|max:28',
        ]);
        $product->update($data);

        return back()->with('success', 'Product updated.');
    }

    public function storeParam(Request $request, Product $product)
    {
        $data = $request->validate([
            'parameter'     => 'required|string|max:120',
            'specification' => 'required|string|max:160',
            'method'        => 'nullable|string|max:120',
        ]);
        $product->specParams()->create($data + ['sort' => $product->specParams()->count() + 1]);

        return back()->with('success', 'Parameter added.');
    }

    public function destroyParam(Product $product, SpecParam $param)
    {
        abort_unless($param->product_id === $product->id, 404);
        $param->delete();

        return back()->with('success', 'Parameter removed.');
    }
}
