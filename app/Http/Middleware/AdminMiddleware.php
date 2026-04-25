<?php
// app/Http/Middleware/AdminMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Allow all staff roles into admin panel
        if (!Auth::user()->isStaff()) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}