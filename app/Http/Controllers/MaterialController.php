<?php

namespace App\Http\Controllers;

use App\Models\MaterialLot;
use App\Models\RawMaterial;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = RawMaterial::with(['lots' => fn($q) => $q->where('qty', '>', 0)])->orderBy('name')->get();
        return view('materials.index', compact('materials'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'unit' => 'required|string|max:20',
        ]);
        RawMaterial::create($data);

        return back()->with('success', 'Raw material added. You can now receive lots against it.');
    }

    public function storeLot(Request $request)
    {
        $data = $request->validate([
            'raw_material_id' => 'required|exists:raw_materials,id',
            'lot_no'          => 'required|string|max:60',
            'supplier'        => 'nullable|string|max:120',
            'qty'             => 'required|numeric|min:0.001',
            'expiry'          => 'nullable|date',
            'received_date'   => 'nullable|date',
        ]);
        $data['received_date'] = $data['received_date'] ?? today();
        MaterialLot::create($data);

        return back()->with('success', 'Lot received — traceability is now linked.');
    }

    public function update(Request $request, RawMaterial $rawMaterial)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'unit' => 'required|string|max:20',
        ]);
        $rawMaterial->update($data);

        return back()->with('success', 'Material updated.');
    }

    public function updateLot(Request $request, MaterialLot $lot)
    {
        $data = $request->validate([
            'lot_no'   => 'required|string|max:60',
            'supplier' => 'nullable|string|max:120',
            'qty'      => 'required|numeric|min:0',
            'expiry'   => 'nullable|date',
        ]);
        $lot->update($data);

        return back()->with('success', 'Lot updated.');
    }
}
