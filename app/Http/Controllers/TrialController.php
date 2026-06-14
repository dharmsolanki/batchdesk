<?php

namespace App\Http\Controllers;

class TrialController extends Controller
{
    public function expired()
    {
        $company = auth()->user()->company;
        return view('trial.expired', compact('company'));
    }
}
