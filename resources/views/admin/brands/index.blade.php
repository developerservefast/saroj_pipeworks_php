@extends('admin.layouts.app')
@section('title', 'Brands')

@section('content')
<div class="flex flex-wrap items-end justify-between gap-2">
    <div class="min-w-0">
        <h2 class="text-xl font-bold text-brand-dark sm:text-2xl">Brands</h2>
        <p class="mt-1 text-sm text-gray-500">Brands you can pick on each product.</p>
    </div>
    <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-brand-dark">{{ $brands->count() }} {{ $brands->count() === 1 ? 'brand' : 'brands' }}</span>
</div>

@if ($errors->any())
    <div role="alert" class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('admin.brands.store') }}" class="card mt-5 p-4 sm:p-5">
    @csrf
    <label for="newBrand" class="block text-sm font-semibold text-gray-700">Add a new brand</label>
    <div class="mt-2 flex flex-col gap-3 sm:flex-row">
        <input id="newBrand" name="name" required maxlength="255" placeholder="Brand name" autocomplete="off" class="input !py-3 !text-base sm:flex-1">
        <button type="submit" class="btn-primary min-h-12 !py-3 sm:!px-8">Add Brand</button>
    </div>
</form>

<div class="mt-6 grid gap-4 md:grid-cols-2">
@forelse ($brands as $brand)
    <div class="card min-w-0 p-4 sm:p-5">
        <form id="brand-update-{{ $brand->id }}" method="POST" action="{{ route('admin.brands.update', $brand) }}">
            @csrf @method('PUT')
            <label for="brand-name-{{ $brand->id }}" class="sr-only">Brand name</label>
            <input id="brand-name-{{ $brand->id }}" name="name" value="{{ $brand->name }}" required maxlength="255" class="input !py-3 !text-base font-semibold">
        </form>

        <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
            <label class="inline-flex min-h-11 cursor-pointer items-center gap-3 text-sm font-medium text-gray-700">
                <input form="brand-update-{{ $brand->id }}" type="checkbox" name="is_active" value="1" class="peer sr-only" @checked($brand->is_active)>
                <span class="relative h-6 w-11 shrink-0 rounded-full bg-gray-300 transition peer-checked:bg-brand peer-focus-visible:ring-2 peer-focus-visible:ring-brand/40 after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-all after:content-[''] peer-checked:after:translate-x-5"></span>
                Active
            </label>
            <span class="rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-brand-dark ring-1 ring-sky-100">{{ $brand->products_count }} {{ $brand->products_count === 1 ? 'product' : 'products' }}</span>
        </div>

        <div class="mt-4 flex gap-3 border-t border-gray-100 pt-4">
            <button type="submit" form="brand-update-{{ $brand->id }}" class="btn-primary min-h-11 flex-1">Save</button>
            <form method="POST" action="{{ route('admin.brands.destroy', $brand) }}" onsubmit="return confirm('Delete this brand?')">
                @csrf @method('DELETE')
                <button type="submit" class="inline-flex min-h-11 items-center justify-center gap-1.5 rounded-lg border border-red-200 px-4 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                    Delete
                </button>
            </form>
        </div>
    </div>
@empty
    <div class="card col-span-full p-8 text-center">
        <p class="font-semibold text-brand-dark">No brands yet</p>
        <p class="mt-1 text-sm text-gray-500">Add your first brand above.</p>
    </div>
@endforelse
</div>
@endsection
