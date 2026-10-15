@extends('admin.layouts.app')
@section('content')
<h1 class="text-2xl font-bold text-brand-dark">Brands</h1>

@if ($errors->any()) <p class="mt-3 text-sm text-red-600">{{ $errors->first() }}</p> @endif

<form method="POST" action="{{ route('admin.brands.store') }}" class="mt-4 flex gap-2">
    @csrf
    <input name="name" required placeholder="New brand name" class="w-64 rounded border px-3 py-2">
    <button class="rounded bg-brand px-4 py-2 text-white">Add Brand</button>
</form>

<div class="mt-6 space-y-2">
@foreach ($brands as $brand)
    <div class="flex items-center gap-2">
        <form method="POST" action="{{ route('admin.brands.update', $brand) }}" class="flex items-center gap-2">
            @csrf @method('PUT')
            <input name="name" value="{{ $brand->name }}" class="w-64 rounded border px-3 py-2">
            <label class="text-sm"><input type="checkbox" name="is_active" value="1" @checked($brand->is_active)> Active</label>
            <button class="rounded border px-3 py-2 text-sm">Save</button>
        </form>
        <span class="text-sm text-gray-500">{{ $brand->products_count }} products</span>
        <form method="POST" action="{{ route('admin.brands.destroy', $brand) }}" onsubmit="return confirm('Delete this brand?')">
            @csrf @method('DELETE')
            <button class="text-sm text-red-600">Delete</button>
        </form>
    </div>
@endforeach
</div>
@endsection
