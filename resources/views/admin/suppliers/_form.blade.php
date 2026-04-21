{{-- Shared supplier form fields --}}
<div style="display:grid;gap:1rem">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
        <div class="pf-field">
            <label class="pf-label">Name <span style="color:var(--pink)">*</span></label>
            <input type="text" name="name"
                   class="pf-input {{ $errors->has('name') ? 'is-error':'' }}"
                   value="{{ old('name', $supplier->name ?? '') }}"
                   placeholder="e.g. Unilever Kenya"
                   required>
            @error('name')
                <span class="pf-error-msg"><i class="fas fa-circle-exclamation"></i>{{ $message }}</span>
            @enderror
        </div>
        <div class="pf-field">
            <label class="pf-label">Phone</label>
            <input type="text" name="phone"
                   class="pf-input {{ $errors->has('phone') ? 'is-error':'' }}"
                   value="{{ old('phone', $supplier->phone ?? '') }}"
                   placeholder="e.g. 0712 345 678">
            @error('phone')
                <span class="pf-error-msg"><i class="fas fa-circle-exclamation"></i>{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="pf-field">
        <label class="pf-label">Email</label>
        <input type="email" name="email"
               class="pf-input {{ $errors->has('email') ? 'is-error':'' }}"
               value="{{ old('email', $supplier->email ?? '') }}"
               placeholder="supplier@example.com">
        @error('email')
            <span class="pf-error-msg"><i class="fas fa-circle-exclamation"></i>{{ $message }}</span>
        @enderror
    </div>

    <div class="pf-field">
        <label class="pf-label">Address</label>
        <textarea name="address"
                  class="pf-textarea"
                  placeholder="Physical or postal address…"
                  style="min-height:90px">{{ old('address', $supplier->address ?? '') }}</textarea>
    </div>

</div>