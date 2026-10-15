<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index()
    {
        return view('admin.sizes.index', ['sizes' => Size::withCount('products')->orderBy('name')->get()]);
    }

    public function store(Request $r)
    {
        Size::create($r->validate(['name' => 'required|string|max:100|unique:sizes,name']) + ['is_active' => true]);
        return back()->with('success', 'Size added.');
    }

    public function update(Request $r, Size $size)
    {
        $data = $r->validate(['name' => 'required|string|max:100|unique:sizes,name,' . $size->id]);
        $size->update($data + ['is_active' => $r->boolean('is_active')]);
        return back()->with('success', 'Size saved.');
    }

    public function destroy(Size $size)
    {
        $size->delete(); // products keep existing, size becomes empty
        return back()->with('success', 'Size deleted.');
    }
}