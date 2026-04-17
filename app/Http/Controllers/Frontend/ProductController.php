<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)->with(['category', 'brand']);

        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $ids = $category->children->pluck('id')->push($category->id);
                $query->whereIn('category_id', $ids);
            }
        }

        if ($request->filled('brand')) {
            $brand = Brand::where('slug', $request->brand)->first();
            if ($brand) $query->where('brand_id', $brand->id);
        }

        if ($request->filled('filter')) {
            match($request->filter) {
                'new'        => $query->where('is_new_arrival', true),
                'sale'       => $query->whereNotNull('sale_price'),
                'featured'   => $query->where('is_featured', true),
                'bestseller' => $query->where('is_best_seller', true),
                default      => null,
            };
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->where('name', 'like', "%{$q}%")
                   ->orWhere('short_description', 'like', "%{$q}%");
            });
        }

        if ($request->filled('min_price')) $query->where('price', '>=', $request->min_price);
        if ($request->filled('max_price')) $query->where('price', '<=', $request->max_price);

        $sort = $request->get('sort', 'latest');
        match($sort) {
            'price_low'  => $query->orderByRaw('COALESCE(sale_price, price) ASC'),
            'price_high' => $query->orderByRaw('COALESCE(sale_price, price) DESC'),
            'name'       => $query->orderBy('name'),
            default      => $query->latest(),
        };

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::whereNull('parent_id')->where('is_active', true)->with('children')->get();
        $brands     = Brand::where('is_active', true)->get();

        return view('frontend.products.index', compact('products', 'categories', 'brands'));
    }

    public function show(string $slug)
    {
        $product  = Product::where('slug', $slug)->where('is_active', true)->with(['category', 'brand', 'images', 'reviews.user'])->firstOrFail();
        $related  = Product::where('category_id', $product->category_id)
                        ->where('id', '!=', $product->id)
                        ->where('is_active', true)
                        ->take(4)->get();

        return view('frontend.products.show', compact('product', 'related'));
    }
}
