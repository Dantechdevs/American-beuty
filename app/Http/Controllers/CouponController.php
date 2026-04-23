<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $coupons = Coupon::query()
            ->when($request->search, fn($q) =>
                $q->where('code', 'like', '%'.$request->search.'%')
            )
            ->when($request->type, fn($q) =>
                $q->where('type', $request->type)
            )
            ->when($request->status === 'active',   fn($q) => $q->where('is_active', true))
            ->when($request->status === 'inactive', fn($q) => $q->where('is_active', false))
            ->when($request->status === 'expired',  fn($q) =>
                $q->whereNotNull('expires_at')->whereDate('expires_at', '<', today())
            )
            ->latest()
            ->paginate(20);

        $stats = [
            'total'    => Coupon::count(),
            'active'   => Coupon::where('is_active', true)->count(),
            'expired'  => Coupon::whereNotNull('expires_at')->whereDate('expires_at', '<', today())->count(),
            'used'     => Coupon::sum('used_count'),
        ];

        return view('admin.coupons.index', compact('coupons', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'          => 'required|string|max:50|unique:coupons,code',
            'type'          => 'required|in:fixed,percent',
            'value'         => 'required|numeric|min:0.01',
            'minimum_order' => 'nullable|numeric|min:0',
            'usage_limit'   => 'nullable|integer|min:1',
            'expires_at'    => 'nullable|date|after:today',
            'is_active'     => 'nullable|boolean',
        ]);

        Coupon::create([
            'code'          => strtoupper(trim($request->code)),
            'type'          => $request->type,
            'value'         => $request->value,
            'minimum_order' => $request->minimum_order ?? 0,
            'usage_limit'   => $request->usage_limit,
            'expires_at'    => $request->expires_at,
            'is_active'     => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Coupon "' . strtoupper($request->code) . '" created successfully.');
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code'          => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type'          => 'required|in:fixed,percent',
            'value'         => 'required|numeric|min:0.01',
            'minimum_order' => 'nullable|numeric|min:0',
            'usage_limit'   => 'nullable|integer|min:1',
            'expires_at'    => 'nullable|date',
            'is_active'     => 'nullable|boolean',
        ]);

        $coupon->update([
            'code'          => strtoupper(trim($request->code)),
            'type'          => $request->type,
            'value'         => $request->value,
            'minimum_order' => $request->minimum_order ?? 0,
            'usage_limit'   => $request->usage_limit,
            'expires_at'    => $request->expires_at,
            'is_active'     => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Coupon "' . $coupon->code . '" updated.');
    }

    public function toggle(Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);
        $status = $coupon->is_active ? 'activated' : 'deactivated';
        return back()->with('success', 'Coupon "' . $coupon->code . '" ' . $status . '.');
    }

    public function destroy(Coupon $coupon)
    {
        $code = $coupon->code;
        $coupon->delete();
        return back()->with('success', 'Coupon "' . $code . '" deleted.');
    }

    public function generate()
    {
        return response()->json([
            'code' => strtoupper(Str::random(4) . '-' . Str::random(4)),
        ]);
    }
}