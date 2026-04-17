<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;

class HomeController extends Controller
{
    public function index()
    {
        $sliders      = Slider::where('is_active', true)->orderBy('sort_order')->get();
        $categories   = Category::whereNull('parent_id')->where('is_active', true)->withCount('products')->get();
        $featured     = Product::where(['is_active'=>true,'is_featured'=>true])->with(['category','brand'])->take(8)->get();
        $newArrivals  = Product::where(['is_active'=>true,'is_new_arrival'=>true])->with(['category','brand'])->take(8)->get();
        $bestSellers  = Product::where(['is_active'=>true,'is_best_seller'=>true])->with(['category','brand'])->take(8)->get();

        return view('frontend.home.index', compact('sliders','categories','featured','newArrivals','bestSellers'));
    }
}
