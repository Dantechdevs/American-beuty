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
        // ── Register Gate so @can / @canany work in Blade ──────────────────
        Gate::before(function (User $user, string $ability) {
            // super-admin bypasses everything
            if ($user->hasRole('super-admin')) {
                return true;
            }

            // admin role (legacy string column) bypasses everything
            if ($user->role === 'admin') {
                return true;
            }

            // For all other users, check their role permissions
            if ($user->getAllPermissions()->contains('name', $ability)) {
                return true;
            }

            return null; // let other gates/policies handle it
        });
    }
}