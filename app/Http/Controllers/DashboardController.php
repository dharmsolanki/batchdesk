<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Sale;

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

        return view('dashboard', compact(
            'todaySales', 'monthSales', 'pendingTotal', 'pendingCount',
            'testingCount', 'releasedQty', 'expiringSoon', 'recentBatches', 'recentSales'
        ));
    }
}
