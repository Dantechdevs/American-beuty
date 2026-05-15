<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ── Pagination view ─────────────────────────────────────────────────
        // ── Register Gate so @can / @canany work in Blade ──────────────────
        Gate::before(function (User $user, string $ability) {
            if ($user->hasRole('super-admin')) {
                return true;
            }
            if ($user->role === 'admin') {
                return true;
            }
            if ($user->getAllPermissions()->contains('name', $ability)) {
                return true;
            }
            return null;
        });
    }
}
