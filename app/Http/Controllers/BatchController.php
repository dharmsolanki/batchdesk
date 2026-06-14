<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\BatchMaterial;
use App\Models\MaterialLot;
use App\Models\Product;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BatchController extends Controller
{
    public function index(Request $request)
    {
        $batches = Batch::with('product')
            ->when($request->q, fn ($q, $term) => $q->where('batch_no', 'like', "%{$term}%")
                ->orWhereHas('product', fn ($p) => $p->where('name', 'like', "%{$term}%")))
            ->latest()->paginate(20);

        return view('batches.index', compact('batches'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        $lots = MaterialLot::with('rawMaterial')->where('qty', '>', 0)->orderBy('expiry')->get();

        return view('batches.create', compact('products', 'lots'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id'   => 'required|exists:products,id',
            'batch_no'     => 'required|string|max:60',
            'mfg_date'     => 'required|date',
            'exp_date'     => 'nullable|date|after:mfg_date',
            'produced_qty' => 'required|numeric|min:0.001',
            'lots'         => 'nullable|array',
            'lots.*.material_lot_id' => 'nullable|exists:material_lots,id',
            'lots.*.qty_used'        => 'nullable|numeric|min:0',
        ]);

        $batch = DB::transaction(function () use ($data) {
            $batch = Batch::create([
                'product_id'   => $data['product_id'],
                'batch_no'     => $data['batch_no'],
                'mfg_date'     => $data['mfg_date'],
                'exp_date'     => $data['exp_date'] ?? null,
                'produced_qty' => $data['produced_qty'],
                'qty'          => $data['produced_qty'],
                'status'       => 'testing',
                'coa_token'    => Str::random(40),
            ]);

            foreach ($data['lots'] ?? [] as $row) {
                if (empty($row['material_lot_id']) || empty($row['qty_used']) || (float) $row['qty_used'] <= 0) {
                    continue;
                }
                $lot = MaterialLot::findOrFail($row['material_lot_id']);
                $used = min((float) $row['qty_used'], (float) $lot->qty);
                BatchMaterial::create([
                    'batch_id'        => $batch->id,
                    'material_lot_id' => $lot->id,
                    'qty_used'        => $used,
                ]);
                $lot->decrement('qty', $used);
            }

            return $batch;
        });

        return redirect()->route('batches.show', $batch)->with('success', 'Batch created. Enter test results to release it and generate the COA.');
    }

    public function show(Batch $batch)
    {
        $batch->load(['product.specParams', 'materials.materialLot.rawMaterial', 'testResults']);
        $results = $batch->testResults->keyBy('spec_param_id');

        return view('batches.show', compact('batch', 'results'));
    }

    public function saveResults(Request $request, Batch $batch)
    {
        $data = $request->validate([
            'results'             => 'required|array',
            'results.*.result'    => 'nullable|string|max:160',
            'results.*.pass'      => 'required|in:0,1',
            'tested_by'           => 'nullable|string|max:120',
            'approved_by'         => 'nullable|string|max:120',
            'remarks'             => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($batch, $data) {
            foreach ($data['results'] as $paramId => $row) {
                TestResult::updateOrCreate(
                    ['batch_id' => $batch->id, 'spec_param_id' => $paramId],
                    ['result' => $row['result'] ?? '', 'pass' => (bool) $row['pass']]
                );
            }

            $batch->update([
                'tested_by'   => $data['tested_by'] ?? $batch->tested_by,
                'approved_by' => $data['approved_by'] ?? $batch->approved_by,
                'remarks'     => $data['remarks'] ?? $batch->remarks,
            ]);

            $batch->refresh()->load('product.specParams', 'testResults');
            $batch->update(['status' => $batch->all_passed ? 'released' : ($batch->testResults->contains('pass', false) ? 'rejected' : 'testing')]);
        });

        $batch->refresh();
        $msg = $batch->status === 'released' ? 'All tests passed — batch RELEASED. COA is ready.' : ($batch->status === 'rejected' ? 'A test failed — batch marked as REJECTED.' : 'Results saved.');

        return back()->with('success', $msg);
    }

    public function coa(Batch $batch)
    {
        abort_unless($batch->status === 'released', 403, 'COA can only be generated for a released batch.');
        $batch->load(['product.specParams', 'testResults.specParam', 'company']);

        return view('batches.coa', compact('batch'));
    }
}
