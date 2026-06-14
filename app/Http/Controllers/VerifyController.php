<?php

namespace App\Http\Controllers;

use App\Models\Batch;

class VerifyController extends Controller
{
    public function show(string $token)
    {
        $batch = Batch::withoutGlobalScope('company')
            ->with(['product.specParams', 'testResults.specParam', 'company'])
            ->where('coa_token', $token)
            ->firstOrFail();

        return view('verify.show', compact('batch'));
    }
}
