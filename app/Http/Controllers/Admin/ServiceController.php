<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{

    // ── Frontend ──────────────────────────────────────────────

    public function frontend()
    {
        $categories = ServiceCategory::with(["services" => function($q) {
            $q->where("is_active", true)->orderBy("sort_order");
        }])
        ->where("is_active", true)
        ->orderBy("sort_order")
        ->get();

        $totalServices = $categories->sum(fn($c) => $c->services->count());

        return view("pages.services", compact("categories", "totalServices"));
    }

    // ── Categories ────────────────────────────────────────────────

    public function categories()
    {
        $categories = ServiceCategory::withCount('services')->orderBy('sort_order')->get();
        return view('admin.services.categories', compact('categories'));
    }

    public function categoryStore(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'icon'        => 'nullable|string|max:20',
            'color_class' => 'nullable|string|max:50',
            'sort_order'  => 'nullable|integer',
        ]);

        ServiceCategory::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'icon'        => $request->icon,
            'color_class' => $request->color_class,
            'sort_order'  => $request->sort_order ?? 0,
            'is_active'   => $request->has('is_active'),
        ]);

        return back()->with('success', 'Category created successfully.');
    }

    public function categoryUpdate(Request $request, ServiceCategory $category)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'icon'        => 'nullable|string|max:20',
            'color_class' => 'nullable|string|max:50',
            'sort_order'  => 'nullable|integer',
        ]);

        $category->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'icon'        => $request->icon,
            'color_class' => $request->color_class,
            'sort_order'  => $request->sort_order ?? 0,
            'is_active'   => $request->has('is_active'),
        ]);

        return back()->with('success', 'Category updated successfully.');
    }

    public function categoryDestroy(ServiceCategory $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }

    // ── Services ──────────────────────────────────────────────────

    public function index()
    {
        $services   = Service::with('category')->orderBy('service_category_id')->orderBy('sort_order')->get();
        $categories = ServiceCategory::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.services.index', compact('services', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_category_id' => 'required|exists:service_categories,id',
            'name'                => 'required|string|max:150',
            'description'         => 'nullable|string',
            'price_display'       => 'nullable|string|max:100',
            'price_from'          => 'nullable|numeric|min:0',
            'price_to'            => 'nullable|numeric|min:0',
            'duration'            => 'nullable|string|max:50',
            'sort_order'          => 'nullable|integer',
        ]);

        Service::create([
            'service_category_id' => $request->service_category_id,
            'name'                => $request->name,
            'description'         => $request->description,
            'price_display'       => $request->price_display,
            'price_from'          => $request->price_from,
            'price_to'            => $request->price_to,
            'duration'            => $request->duration,
            'sort_order'          => $request->sort_order ?? 0,
            'is_active'           => $request->has('is_active'),
        ]);

        return back()->with('success', 'Service added successfully.');
    }

    public function edit(Service $service)
    {
        $categories = ServiceCategory::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.services.edit', compact('service', 'categories'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'service_category_id' => 'required|exists:service_categories,id',
            'name'                => 'required|string|max:150',
            'description'         => 'nullable|string',
            'price_display'       => 'nullable|string|max:100',
            'price_from'          => 'nullable|numeric|min:0',
            'price_to'            => 'nullable|numeric|min:0',
            'duration'            => 'nullable|string|max:50',
            'sort_order'          => 'nullable|integer',
        ]);

        $service->update([
            'service_category_id' => $request->service_category_id,
            'name'                => $request->name,
            'description'         => $request->description,
            'price_display'       => $request->price_display,
            'price_from'          => $request->price_from,
            'price_to'            => $request->price_to,
            'duration'            => $request->duration,
            'sort_order'          => $request->sort_order ?? 0,
            'is_active'           => $request->has('is_active'),
        ]);

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return back()->with('success', 'Service deleted.');
    }
}
