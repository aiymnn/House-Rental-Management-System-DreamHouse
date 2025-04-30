<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckStaff
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role == 1) {
            return $next($request);
        }

        return redirect()->route('staff_login')->with('error', 'Access denied.');
    }
}

