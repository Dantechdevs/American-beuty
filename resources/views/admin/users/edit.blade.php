@extends('layouts.admin')
@section('title', 'Edit ' . $user->role_label)

@section('content')
<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <div class="page-title">
            <i class="fas fa-user-pen" style="color:var(--purple)"></i> Edit User
        </div>
        <div class="page-sub">Editing account for {{ $user->name }}</div>
    </div>
    <a href="javascript:history.back()" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div class="card" style="max-width:620px">
    <div class="card-header"><h3><i class="fas fa-user-pen"></i> User Details</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PUT')

            <div class="form-grid-2">
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}">
                </div>
            </div>

            <div class="form-group">
                <label>Email Address *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="form-group">
                <label>Role *</label>
                <select name="role">
                    <option value="admin"        {{ $user->role==='admin'        ? 'selected':'' }}>Administrator</option>
                    <option value="manager"      {{ $user->role==='manager'      ? 'selected':'' }}>Manager</option>
                    <option value="pos_operator" {{ $user->role==='pos_operator' ? 'selected':'' }}>POS Operator</option>
                    <option value="delivery"     {{ $user->role==='delivery'     ? 'selected':'' }}>Delivery Personnel</option>
                    <option value="customer"     {{ $user->role==='customer'     ? 'selected':'' }}>Customer</option>
                </select>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label>New Password <span style="color:var(--muted);font-weight:400">(leave blank to keep)</span></label>
                    <input type="password" name="password" placeholder="Min 8 characters">
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" placeholder="Repeat password">
                </div>
            </div>

            <div style="display:flex;gap:.75rem;margin-top:.5rem">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check"></i> Save Changes
                </button>
                <a href="javascript:history.back()" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection