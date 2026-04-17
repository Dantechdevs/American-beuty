@extends('layouts.admin')
@section('title','Settings')

@section('content')
<h2 style="font-size:1.3rem;font-weight:700;margin-bottom:1.5rem">Store Settings</h2>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;align-items:start">
    <!-- GENERAL SETTINGS -->
    <div class="card">
        <div class="card-header"><h3>General Settings</h3></div>
        <div class="card-body">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Store Name</label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'American Beauty' }}">
                </div>
                <div class="form-group">
                    <label>Tagline</label>
                    <input type="text" name="site_tagline" value="{{ $settings['site_tagline'] ?? '' }}">
                </div>
                <div class="form-group">
                    <label>Contact Email</label>
                    <input type="email" name="site_email" value="{{ $settings['site_email'] ?? '' }}">
                </div>
                <div class="form-group">
                    <label>Contact Phone</label>
                    <input type="text" name="site_phone" value="{{ $settings['site_phone'] ?? '' }}">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="site_address" value="{{ $settings['site_address'] ?? '' }}">
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div class="form-group">
                        <label>Currency Symbol</label>
                        <input type="text" name="currency_symbol" value="{{ $settings['currency_symbol'] ?? 'KSh' }}">
                    </div>
                    <div class="form-group">
                        <label>Currency Code</label>
                        <input type="text" name="currency_code" value="{{ $settings['currency_code'] ?? 'KES' }}">
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div class="form-group">
                        <label>Shipping Fee (KSh)</label>
                        <input type="number" name="shipping_fee" value="{{ $settings['shipping_fee'] ?? 200 }}">
                    </div>
                    <div class="form-group">
                        <label>Free Shipping Minimum (KSh)</label>
                        <input type="number" name="free_shipping_min" value="{{ $settings['free_shipping_min'] ?? 3000 }}">
                    </div>
                </div>
                <div class="form-group">
                    <label>VAT / Tax Rate (%)</label>
                    <input type="number" name="tax_rate" value="{{ $settings['tax_rate'] ?? 16 }}" step="0.01">
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Settings</button>
            </form>
        </div>
    </div>

    <!-- PAYMENT GATEWAYS -->
    <div class="card">
        <div class="card-header"><h3>Payment Gateways</h3></div>
        <div class="card-body">
            @foreach($gateways as $gateway)
            <div style="border:1.5px solid var(--border);border-radius:12px;padding:1.2rem;margin-bottom:1rem;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.8rem">
                    <div style="font-weight:700;font-size:.95rem">
                        @if($gateway->slug=='mpesa') 📱 @elseif($gateway->slug=='stripe') 💳 @else 💵 @endif
                        {{ $gateway->name }}
                    </div>
                    <span class="badge {{ $gateway->is_active?'badge-success':'badge-secondary' }}">
                        {{ $gateway->is_active?'Active':'Inactive' }}
                    </span>
                </div>
                <form action="{{ route('admin.settings.gateway',$gateway) }}" method="POST">
                    @csrf @method('PATCH')
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem;margin-bottom:.8rem">
                        <div class="form-group" style="margin:0">
                            <label>Status</label>
                            <select name="is_active">
                                <option value="1" {{ $gateway->is_active?'selected':'' }}>Active</option>
                                <option value="0" {{ !$gateway->is_active?'selected':'' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin:0">
                            <label>Mode</label>
                            <select name="mode">
                                <option value="sandbox" {{ $gateway->mode=='sandbox'?'selected':'' }}>Sandbox</option>
                                <option value="live" {{ $gateway->mode=='live'?'selected':'' }}>Live</option>
                            </select>
                        </div>
                    </div>
                    @if($gateway->slug === 'mpesa')
                    <p style="font-size:.78rem;color:#888;background:#f8f8f8;padding:.6rem .8rem;border-radius:8px;margin-bottom:.8rem;line-height:1.6">
                        M-PESA credentials are set via <strong>.env</strong> file:<br>
                        <code>MPESA_CONSUMER_KEY, MPESA_CONSUMER_SECRET,<br>MPESA_SHORTCODE, MPESA_PASSKEY, MPESA_CALLBACK_URL</code>
                    </p>
                    @endif
                    <button type="submit" class="btn btn-outline btn-sm">Save</button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
