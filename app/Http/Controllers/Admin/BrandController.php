<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:brands,name']);

        $brand = Brand::create([
            'name'      => $request->name,
            'slug'      => Str::slug($request->name),
            'is_active' => true,
        ]);

        return response()->json(['id' => $brand->id, 'name' => $brand->name]);
    }
}
