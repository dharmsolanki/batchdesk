<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\Sale;
use App\Models\SpecParam;

class DashboardController extends Controller
{
    public function index()
    {
        $todaySales   = Sale::whereDate('created_at', today())->sum('total');
        $monthSales   = Sale::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total');
        $pendingTotal = Sale::whereIn('status', ['partial', 'pending'])->get()->sum(fn ($s) => $s->pending);
        $pendingCount = Sale::whereIn('status', ['partial', 'pending'])->count();
        $testingCount = Batch::where('status', 'testing')->count();
        $releasedQty  = Batch::where('status', 'released')->count();
        $expiringSoon = Batch::with('product')
            ->where('status', 'released')->where('qty', '>', 0)
            ->whereNotNull('exp_date')
            ->whereBetween('exp_date', [today(), today()->addDays(45)])
            ->orderBy('exp_date')->take(5)->get();
        $recentBatches = Batch::with('product')->latest()->take(4)->get();
        $recentSales   = Sale::with('customer')->latest()->take(4)->get();

        // Onboarding checklist
        $companyId = auth()->user()->company_id;
        $hasProduct  = Product::count() > 0;
        $hasSpec     = $hasProduct && SpecParam::whereHas('product', fn ($q) => $q->where('company_id', $companyId))->exists();
        $hasMaterial = RawMaterial::count() > 0;
        $hasBatch    = Batch::count() > 0;
        $hasCoa      = Batch::where('status', 'released')->count() > 0;
        $onboardingComplete = $hasProduct && $hasSpec && $hasMaterial && $hasBatch && $hasCoa;

        $checklist = [
            ['label' => 'Add your first product',        'done' => $hasProduct,  'route' => 'products.index',  'hint' => 'Go to Products → New product'],
            ['label' => 'Add a COA specification',        'done' => $hasSpec,     'route' => 'products.index',  'hint' => 'Open a product → Add parameters (pH, Assay, etc.)'],
            ['label' => 'Receive a raw material lot',     'done' => $hasMaterial, 'route' => 'materials.index', 'hint' => 'Go to Products → Raw materials → Receive lot'],
            ['label' => 'Create your first batch',        'done' => $hasBatch,    'route' => 'batches.create',  'hint' => 'Go to Batches → New batch'],
            ['label' => 'Generate your first COA',        'done' => $hasCoa,      'route' => 'batches.index',   'hint' => 'Enter test results in a batch → all pass → View COA'],
        ];

        return view('dashboard', compact(
            'todaySales', 'monthSales', 'pendingTotal', 'pendingCount',
            'testingCount', 'releasedQty', 'expiringSoon', 'recentBatches', 'recentSales',
            'checklist', 'onboardingComplete'
        ));
    }
}
