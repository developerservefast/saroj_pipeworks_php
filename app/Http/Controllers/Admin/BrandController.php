<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        return view('admin.brands.index', ['brands' => Brand::withCount('products')->orderBy('name')->get()]);
    }

    public function store(Request $r)
    {
        Brand::create($r->validate(['name' => 'required|string|max:100|unique:brands,name']));
        return back();
    }

    public function update(Request $r, Brand $brand)
    {
        $data = $r->validate(['name' => 'required|string|max:100|unique:brands,name,' . $brand->id]);
        $brand->update($data + ['is_active' => $r->boolean('is_active')]);
        return back();
    }

    public function destroy(Brand $brand)
    {
        $brand->delete(); // products keep existing, brand becomes empty
        return back();
    }
}
