<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::withCount('sales')
            ->when($request->q, function ($query, $q) {
                $query->where('name', 'like', "%{$q}%")->orWhere('phone', 'like', "%{$q}%");
            })
            ->latest()->paginate(20);

        return view('customers.index', compact('customers'));
    }

    public function show(Customer $customer)
    {
        $customer->load(['sales.items']);
        return view('customers.show', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:120',
            'phone'      => 'required|string|max:20',
            'gst_number' => 'nullable|string|max:20',
            'address'    => 'nullable|string|max:255',
        ]);
        $customer->update($data);

        return back()->with('success', 'Customer updated.');
    }
}
