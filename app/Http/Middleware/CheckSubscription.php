<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSubscription
{
    public function handle(Request $request, Closure $next)
    {
        // Admin users bypass subscription check
        if (auth()->check() && auth()->user()->is_admin) {
            return $next($request);
        }

        if (auth()->check()) {
            $company = auth()->user()->company;

            if ($company && !$company->isActive()) {
                return redirect()->route('trial.expired');
            }
        }

        return $next($request);
    }
}
