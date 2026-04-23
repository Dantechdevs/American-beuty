@extends('layouts.admin')
@section('title', 'Edit Profile')

@section('content')
<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <div class="page-title">
            <i class="fas fa-user-pen" style="color:var(--purple)"></i> Edit Profile
        </div>
        <div class="page-sub">Update your account information</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:280px 1fr;gap:1.5rem;align-items:start">

    {{-- Side nav --}}
    <div class="card">
        <div class="card-body" style="text-align:center;padding:2rem 1.5rem">

            {{-- Avatar --}}
            <div style="position:relative;display:inline-block;margin-bottom:1rem">
                @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}"
                         alt="{{ $user->name }}"
                         style="width:80px;height:80px;border-radius:20px;object-fit:cover;border:3px solid var(--border)">
                @else
                    <div style="width:80px;height:80px;border-radius:20px;background:linear-gradient(135deg,var(--purple),var(--pink));display:flex;align-items:center;justify-content:center;color:#fff;font-size:2rem;font-weight:700;margin:0 auto">
                        {{ strtoupper(substr($user->name,0,2)) }}
                    </div>
                @endif
            </div>

            <div style="font-weight:700;font-size:1rem;color:var(--text)">{{ $user->name }}</div>
            <div style="font-size:.78rem;color:var(--muted);margin-top:.2rem">{{ $user->email }}</div>
            <div style="margin-top:.75rem">
                <span class="badge {{ $user->role_badge }}">{{ $user->role_label }}</span>
            </div>

            <div style="margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid var(--border);display:flex;flex-direction:column;gap:.5rem">
                <a href="{{ route('admin.profile.edit') }}"
                   class="btn btn-primary btn-sm" style="justify-content:center">
                    <i class="fas fa-user-pen"></i> Edit Profile
                </a>
                <a href="{{ route('admin.profile.password') }}"
                   class="btn btn-outline btn-sm" style="justify-content:center">
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
        <div class="card-header"><h3><i class="fas fa-user-pen"></i> Personal Information</h3></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                {{-- Avatar upload --}}
                <div class="form-group">
                    <label>Profile Photo</label>
                    <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}"
                                 alt="Current avatar"
                                 style="width:56px;height:56px;border-radius:12px;object-fit:cover;border:2px solid var(--border)">
                        @else
                            <div style="width:56px;height:56px;border-radius:12px;background:linear-gradient(135deg,var(--purple),var(--pink));display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.2rem;font-weight:700;flex-shrink:0">
                                {{ strtoupper(substr($user->name,0,2)) }}
                            </div>
                        @endif
                        <div style="flex:1">
                            <input type="file" name="avatar" accept="image/jpg,image/jpeg,image/png,image/webp"
                                style="font-size:.83rem">
                            <div style="font-size:.73rem;color:var(--muted);margin-top:.3rem">
                                JPG, PNG or WebP. Max 2MB.
                            </div>
                        </div>
                        @if($user->avatar)
                            <label style="display:flex;align-items:center;gap:.4rem;font-size:.8rem;color:var(--tango);cursor:pointer;font-weight:600">
                                <input type="checkbox" name="remove_avatar" value="1" style="width:auto">
                                Remove photo
                            </label>
                        @endif
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="07XXXXXXXX">
                    </div>
                </div>

                <div class="form-group">
                    <label>Email Address *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <input type="text" value="{{ $user->role_label }}" disabled
                        style="background:#f8f5ff;color:var(--muted);cursor:not-allowed">
                </div>

                <div style="display:flex;gap:.75rem;margin-top:.5rem">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection