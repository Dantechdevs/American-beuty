<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $promotions = Promotion::query()
            ->when($request->search, fn($q) =>
                $q->where('name', 'like', '%'.$request->search.'%')
            )
            ->when($request->type, fn($q) =>
                $q->where('type', $request->type)
            )
            ->when($request->status === 'active', fn($q) =>
                $q->running()
            )
            ->when($request->status === 'scheduled', fn($q) =>
                $q->where('is_active', true)
                  ->where('starts_at', '>', now())
            )
            ->when($request->status === 'expired', fn($q) =>
                $q->whereNotNull('ends_at')
                  ->where('ends_at', '<', now())
            )
            ->when($request->status === 'inactive', fn($q) =>
                $q->where('is_active', false)
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total'     => Promotion::count(),
            'active'    => Promotion::running()->count(),  // fixed: was 'running'
            'scheduled' => Promotion::where('is_active', true)
                            ->whereNotNull('starts_at')
                            ->where('starts_at', '>', now())->count(),
            'expired'   => Promotion::whereNotNull('ends_at')
                            ->where('ends_at', '<', now())->count(),
        ];

        $categories = Category::orderBy('name')->get();
        $products   = Product::where('is_active', true)->orderBy('name')->get();

        return view('admin.promotions.index', compact('promotions', 'stats', 'categories', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'description'   => 'nullable|string|max:500',
            'type'          => 'required|in:fixed,percent',
            'value'         => 'required|numeric|min:0.01',
            'applies_to'    => 'required|in:all,category,product',
            'applies_to_id' => 'nullable|required_if:applies_to,category,product|integer',
            'minimum_order' => 'nullable|numeric|min:0',
            'starts_at'     => 'nullable|date',
            'ends_at'       => 'nullable|date|after_or_equal:starts_at',
            'is_active'     => 'nullable|boolean',
        ]);

        Promotion::create([
            'name'          => $request->name,
            'description'   => $request->description,
            'type'          => $request->type,
            'value'         => $request->value,
            'applies_to'    => $request->applies_to,
            'applies_to_id' => in_array($request->applies_to, ['category', 'product'])
                                ? $request->applies_to_id : null,
            'minimum_order' => $request->minimum_order ?? 0,
            'starts_at'     => $request->starts_at,
            'ends_at'       => $request->ends_at,
            'is_active'     => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Promotion "' . $request->name . '" created successfully.');
    }

    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'description'   => 'nullable|string|max:500',
            'type'          => 'required|in:fixed,percent',
            'value'         => 'required|numeric|min:0.01',
            'applies_to'    => 'required|in:all,category,product',
            'applies_to_id' => 'nullable|required_if:applies_to,category,product|integer',
            'minimum_order' => 'nullable|numeric|min:0',
            'starts_at'     => 'nullable|date',
            'ends_at'       => 'nullable|date|after_or_equal:starts_at',
            'is_active'     => 'nullable|boolean',
        ]);

        $promotion->update([
            'name'          => $request->name,
            'description'   => $request->description,
            'type'          => $request->type,
            'value'         => $request->value,
            'applies_to'    => $request->applies_to,
            'applies_to_id' => in_array($request->applies_to, ['category', 'product'])
                                ? $request->applies_to_id : null,
            'minimum_order' => $request->minimum_order ?? 0,
            'starts_at'     => $request->starts_at,
            'ends_at'       => $request->ends_at,
            'is_active'     => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Promotion "' . $promotion->name . '" updated.');
    }

    public function toggle(Promotion $promotion)
    {
        $promotion->update(['is_active' => !$promotion->is_active]);
        $status = $promotion->is_active ? 'activated' : 'deactivated';
        return back()->with('success', 'Promotion "' . $promotion->name . '" ' . $status . '.');
    }

    public function destroy(Promotion $promotion)
    {
        $name = $promotion->name;
        $promotion->delete();
        return back()->with('success', 'Promotion "' . $name . '" deleted.');
    }
}