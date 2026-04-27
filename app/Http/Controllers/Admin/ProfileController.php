<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

class ProfileController extends Controller
{
    // -- Edit Profile --------------------------------------------------
    public function edit()
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'   => 'required|string|max:100',
            'email'  => 'required|email|unique:users,email,'.$user->id,
            'phone'  => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Handle avatar removal
        if ($request->input('remove_avatar') && $user->avatar) {
            if (Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = null;
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    // -- Change Password -----------------------------------------------
    public function password()
    {
        return view('admin.profile.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Manually log password change since it's not a tracked field
        activity()
            ->causedBy($user)
            ->log('Changed password');

        return back()->with('success', 'Password changed successfully.');
    }

    // -- Activity Log --------------------------------------------------
    public function activity()
    {
        $user = Auth::user();

        $logs = Activity::where('causer_id', $user->id)
                    ->where('causer_type', get_class($user))
                    ->orderByDesc('created_at')
                    ->paginate(30);

        return view('admin.profile.activity', compact('logs'));
    }
}