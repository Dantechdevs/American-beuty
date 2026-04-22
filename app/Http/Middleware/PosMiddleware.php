<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PosMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->hasRole(['admin', 'manager', 'pos_operator'])) {
            abort(403, 'Unauthorized.');
        }
        return $next($request);
    }
}