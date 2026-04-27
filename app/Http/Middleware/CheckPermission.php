<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Usage in routes:
     *   ->middleware('permission:products.create')
     *   ->middleware('permission:products.create|products.edit')  // any of these
     */
    public function handle(Request $request, Closure $next, string $permissions): Response
    {
        if (! $request->user()) {
            abort(401);
        }

        $permissionList = explode('|', $permissions);

        if (! $request->user()->hasAnyPermission($permissionList)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized. Insufficient permissions.'], 403);
            }
            abort(403, 'You do not have permission to perform this action.');
        }

        return $next($request);
    }
}

// ─── CheckRole Middleware ────────────────────────────────────────────────────

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Usage in routes:
     *   ->middleware('role:admin')
     *   ->middleware('role:admin|manager')   // any of these roles
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        if (! $request->user()) {
            abort(401);
        }

        $roleList = explode('|', $roles);

        if (! $request->user()->hasAnyRole($roleList)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized. Insufficient role.'], 403);
            }
            abort(403, 'You do not have the required role to access this area.');
        }

        return $next($request);
    }
}