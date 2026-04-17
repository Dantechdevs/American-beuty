<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        $gateways = PaymentGateway::all();
        return view('admin.settings.index', compact('settings', 'gateways'));
    }

    public function update(Request $request)
    {
        $keys = ['site_name','site_tagline','site_email','site_phone','site_address',
                 'currency_symbol','currency_code','shipping_fee','free_shipping_min','tax_rate'];
        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key));
            }
        }
        return back()->with('success', 'Settings saved.');
    }

    public function updateGateway(Request $request, PaymentGateway $gateway)
    {
        $gateway->update([
            'is_active'   => $request->boolean('is_active'),
            'mode'        => $request->get('mode', 'sandbox'),
            'credentials' => $request->get('credentials', []),
        ]);
        return back()->with('success', $gateway->name . ' gateway updated.');
    }
}
