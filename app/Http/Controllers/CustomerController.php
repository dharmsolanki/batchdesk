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
}
