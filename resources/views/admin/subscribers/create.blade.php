@extends('layouts.admin')

@section('title', 'Add Subscriber')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0"><i class="fas fa-user-plus me-2 text-primary"></i>Add Subscriber</h4>
        <a href="{{ route('admin.subscribers.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card shadow-sm" style="max-width: 600px;">
        <div class="card-body">
            <form action="{{ route('admin.subscribers.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Name <span class="text-muted">(optional)</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="Full name">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="email@example.com">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone / WhatsApp</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                           value="{{ old('phone') }}" placeholder="+254700000000">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Subscription Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror">
                        <option value="">-- Select Type --</option>
                        @foreach(['email','sms','whatsapp','push'] as $t)
                            <option value="{{ $t }}" {{ old('type') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Source <span class="text-danger">*</span></label>
                    <select name="source" class="form-select @error('source') is-invalid @enderror">
                        <option value="">-- Select Source --</option>
                        <option value="footer_form" {{ old('source') == 'footer_form' ? 'selected' : '' }}>Footer Newsletter Form</option>
                        <option value="checkout" {{ old('source') == 'checkout' ? 'selected' : '' }}>Checkout Signup</option>
                        <option value="manual" {{ old('source') == 'manual' ? 'selected' : '' }}>Manual Entry</option>
                        <option value="registration" {{ old('source') == 'registration' ? 'selected' : '' }}>Account Registration</option>
                    </select>
                    @error('source')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Tag / Segment <span class="text-muted">(optional)</span></label>
                    <input type="text" name="tag" class="form-control" value="{{ old('tag') }}"
                           placeholder="e.g. vip, newsletter, promo">
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-save me-1"></i> Save Subscriber
                </button>
            </form>
        </div>
    </div>
</div>
@endsection