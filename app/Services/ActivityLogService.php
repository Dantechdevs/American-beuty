<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class ActivityLogService
{
    public static function login(User $user): void
    {
        Log::info('User logged in', [
            'user_id' => $user->id,
            'email'   => $user->email,
            'ip'      => request()->ip(),
            'at'      => now(),
        ]);
    }

    public static function logout(User $user): void
    {
        Log::info('User logged out', [
            'user_id' => $user->id,
            'email'   => $user->email,
            'ip'      => request()->ip(),
            'at'      => now(),
        ]);
    }
}