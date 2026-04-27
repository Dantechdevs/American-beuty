<?php

namespace App\Services;

use App\Models\User;

class ActivityLogService
{
    public static function login(User $user): void
    {
        activity()
            ->causedBy($user)
            ->withProperties([
                'ip'         => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('Logged in');
    }

    public static function logout(User $user): void
    {
        activity()
            ->causedBy($user)
            ->withProperties([
                'ip'         => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('Logged out');
    }

    public static function register(User $user): void
    {
        activity()
            ->causedBy($user)
            ->withProperties([
                'ip'         => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('Registered account');
    }
}