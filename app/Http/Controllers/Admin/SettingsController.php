<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        $gateways = PaymentGateway::all();

        // Access Control data
        $roles       = Role::with('permissions')->orderBy('created_at')->get();
        $permissions = Permission::orderBy('group')->orderBy('display_name')->get()->groupBy('group');
        $staff       = User::whereIn('role', ['admin','manager','pos_operator','delivery'])
                           ->with('roles')
                           ->orderBy('name')
                           ->get();

        return view('admin.settings.index', compact('settings', 'gateways', 'roles', 'permissions', 'staff'));
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

    // ─── Access Control ───────────────────────────────────────────────────

    public function updateRolePermissions(Request $request, Role $role)
    {
        if ($role->name === 'super-admin') {
            return back()->withErrors(['role' => 'Cannot modify super-admin permissions.']);
        }

        $data = $request->validate([
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $role->syncPermissions($data['permissions'] ?? []);

        return back()->with('success', "Permissions updated for \"{$role->display_name}\".");
    }

    public function updateUserRoles(Request $request, User $user)
    {
        $data = $request->validate([
            'roles'   => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        $user->syncRoles($data['roles'] ?? []);

        return back()->with('success', "Roles updated for {$user->name}.");
    }
}