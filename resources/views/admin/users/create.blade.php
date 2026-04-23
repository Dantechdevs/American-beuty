@extends('layouts.admin')
@section('title', 'Add ' . ucfirst(str_replace('_',' ',$role)))

@section('content')
<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <div class="page-title">
            <i class="fas fa-user-plus" style="color:var(--purple)"></i>
            Add {{ ucfirst(str_replace('_',' ',$role)) }}
        </div>
        <div class="page-sub">Create a new {{ str_replace('_',' ',$role) }} account</div>
    </div>
    <a href="javascript:history.back()" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div class="card" style="max-width:620px">
    <div class="card-header"><h3><i class="fas fa-user-pen"></i> User Details</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div class="form-grid-2">
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="John Doe">
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="07XXXXXXXX">
                </div>
            </div>

            <div class="form-group">
                <label>Email Address *</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="john@example.com">
            </div>

            <div class="form-group">
                <label>Role *</label>
                <select name="role">
                    <option value="admin"        {{ $role==='admin'        ? 'selected':'' }}>Administrator</option>
                    <option value="manager"      {{ $role==='manager'      ? 'selected':'' }}>Manager</option>
                    <option value="pos_operator" {{ $role==='pos_operator' ? 'selected':'' }}>POS Operator</option>
                    <option value="delivery"     {{ $role==='delivery'     ? 'selected':'' }}>Delivery Personnel</option>
                    <option value="customer"     {{ $role==='customer'     ? 'selected':'' }}>Customer</option>
                </select>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label>Password *</label>
                    <input type="password" name="password" required placeholder="Min 8 characters">
                </div>
                <div class="form-group">
                    <label>Confirm Password *</label>
                    <input type="password" name="password_confirmation" required placeholder="Repeat password">
                </div>
            </div>

            <div style="display:flex;gap:.75rem;margin-top:.5rem">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check"></i> Create User
                </button>
                <a href="javascript:history.back()" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection