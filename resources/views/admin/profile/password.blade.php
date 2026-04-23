@extends('layouts.admin')
@section('title', 'Change Password')

@section('content')
<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <div class="page-title">
            <i class="fas fa-lock" style="color:var(--purple)"></i> Change Password
        </div>
        <div class="page-sub">Update your login password</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:280px 1fr;gap:1.5rem;align-items:start">

    {{-- Side nav --}}
    <div class="card">
        <div class="card-body" style="text-align:center;padding:2rem 1.5rem">

            @if(auth()->user()->avatar)
                <img src="{{ Storage::url(auth()->user()->avatar) }}"
                     alt="{{ auth()->user()->name }}"
                     style="width:80px;height:80px;border-radius:20px;object-fit:cover;border:3px solid var(--border);margin-bottom:1rem">
            @else
                <div style="width:80px;height:80px;border-radius:20px;background:linear-gradient(135deg,var(--purple),var(--pink));display:flex;align-items:center;justify-content:center;color:#fff;font-size:2rem;font-weight:700;margin:0 auto 1rem">
                    {{ strtoupper(substr(auth()->user()->name,0,2)) }}
                </div>
            @endif

            <div style="font-weight:700;font-size:1rem;color:var(--text)">{{ auth()->user()->name }}</div>
            <div style="font-size:.78rem;color:var(--muted);margin-top:.2rem">{{ auth()->user()->email }}</div>
            <div style="margin-top:.75rem">
                <span class="badge {{ auth()->user()->role_badge }}">{{ auth()->user()->role_label }}</span>
            </div>

            <div style="margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid var(--border);display:flex;flex-direction:column;gap:.5rem">
                <a href="{{ route('admin.profile.edit') }}"
                   class="btn btn-outline btn-sm" style="justify-content:center">
                    <i class="fas fa-user-pen"></i> Edit Profile
                </a>
                <a href="{{ route('admin.profile.password') }}"
                   class="btn btn-primary btn-sm" style="justify-content:center">
                    <i class="fas fa-lock"></i> Change Password
                </a>
                <a href="{{ route('admin.profile.activity') }}"
                   class="btn btn-outline btn-sm" style="justify-content:center">
                    <i class="fas fa-clock-rotate-left"></i> Activity Log
                </a>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-lock"></i> Change Password</h3></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.profile.password.update') }}">
                @csrf @method('PUT')

                <div class="form-group">
                    <label>Current Password *</label>
                    <input type="password" name="current_password" required placeholder="Enter current password">
                    @error('current_password')
                        <div style="color:var(--tango);font-size:.78rem;margin-top:.3rem">
                            <i class="fas fa-circle-xmark"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label>New Password *</label>
                        <input type="password" name="password" required placeholder="Min 8 characters">
                        @error('password')
                            <div style="color:var(--tango);font-size:.78rem;margin-top:.3rem">
                                <i class="fas fa-circle-xmark"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password *</label>
                        <input type="password" name="password_confirmation" required placeholder="Repeat new password">
                    </div>
                </div>

                <div style="background:var(--purple-soft);border:1px solid #ddd6fe;border-radius:10px;padding:.9rem 1rem;margin-bottom:1.25rem;font-size:.82rem;color:var(--purple)">
                    <i class="fas fa-circle-info" style="margin-right:.4rem"></i>
                    Password must be at least 8 characters long.
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check"></i> Update Password
                </button>
            </form>
        </div>
    </div>

</div>
@endsection