<?php

namespace App\Http\Controllers;

use App\Models\BuyerDeclaration;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeclarationController extends Controller
{
    public function show(Sale $sale)
    {
        $declaration = BuyerDeclaration::where('sale_id', $sale->id)->first();
        return view('declarations.show', compact('sale', 'declaration'));
    }

    public function store(Request $request, Sale $sale)
    {
        $data = $request->validate([
            'buyer_name'    => 'required|string|max:120',
            'buyer_company' => 'required|string|max:120',
            'intended_use' => 'nullable|string|max:255',
        ]);

        BuyerDeclaration::updateOrCreate(
            ['sale_id' => $sale->id],
            [
                'company_id'     => Auth::user()->company_id,
                'invoice_no'     => $sale->invoice_no,
                'declaration_no' => 'DCL-' . $sale->invoice_no,
                'buyer_name'     => $data['buyer_name'],
                'buyer_company'  => $data['buyer_company'],
                'intended_use' => 'Lawful purpose only',
            ]
        );

        return redirect()->route('declarations.show', $sale)
            ->with('success', 'Declaration saved.');
    }
}
