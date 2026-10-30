@extends('admin.layouts.app')
@section('title', 'Products')

@section('content')
@php
    $opts = collect();
    foreach ($categories as $p) { $opts->push([$p->id, $p->name]); foreach ($p->children as $ch) $opts->push([$ch->id, $p->name.' › '.$ch->name]); }
@endphp

<div class="mb-4 flex flex-wrap items-center justify-between gap-3">
    <p class="text-sm text-gray-500">{{ $products->total() }} products</p>
    <div class="flex gap-2">
        <a href="{{ route('admin.products.import') }}" class="btn-outline">Import Products</a>
        <a href="{{ route('admin.products.create') }}" class="btn-primary">+ Add Product</a>
    </div>
</div>

<form method="GET" class="card mb-4 grid gap-2 p-3 sm:grid-cols-2 lg:grid-cols-4">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products" class="input">
    <select name="category" class="input">
        <option value="">All categories</option>
        @foreach ($opts as [$id, $label]) <option value="{{ $id }}" @selected(request('category') == $id)>{{ $label }}</option> @endforeach
    </select>
    <select name="brand" class="input">
        <option value="">All brands</option>
        @foreach ($brands as $b) <option value="{{ $b->id }}" @selected(request('brand') == $b->id)>{{ $b->name }}</option> @endforeach
    </select>
    <select name="status" class="input">
        <option value="">Any status</option>
        <option value="active" @selected(request('status') == 'active')>Active</option>
        <option value="hidden" @selected(request('status') == 'hidden')>Hidden</option>
    </select>
    <select name="badge" class="input">
        <option value="">Any label</option>
        <option value="is_bestseller" @selected(request('badge') == 'is_bestseller')>Best Seller</option>
        <option value="is_trending" @selected(request('badge') == 'is_trending')>Trending</option>
        <option value="is_latest" @selected(request('badge') == 'is_latest')>Latest</option>
    </select>
    <select name="sort" class="input">
        @foreach (['newest' => 'Newest first', 'oldest' => 'Oldest first', 'name' => 'Name A-Z', 'category' => 'Category'] as $k => $l)
            <option value="{{ $k }}" @selected(request('sort', 'newest') == $k)>{{ $l }}</option>
        @endforeach
    </select>
    <button class="btn-primary">Filter</button>
    <a href="{{ route('admin.products.index') }}" class="btn-outline">Reset</a>
</form>

<form id="bulk" method="POST" action="{{ route('admin.products.bulk') }}" class="card mb-3 flex flex-wrap items-center gap-2 p-3" onsubmit="return bulkOk(this)">
    @csrf
    <label class="flex items-center gap-2 text-sm"><input type="checkbox" id="selAll"> Select all on this page</label>
    <select name="action" id="act" class="input w-full sm:w-44" required>
        <option value="">Bulk action…</option>
        <option value="show">Show on website</option>
        <option value="hide">Hide</option>
        <option value="move">Move to category</option>
        <option value="delete">Delete</option>
    </select>
    <select name="target_category" id="tgt" class="input hidden w-full sm:w-64">
        <option value="">Choose category…</option>
        @foreach ($opts as [$id, $label]) <option value="{{ $id }}">{{ $label }}</option> @endforeach
    </select>
    <button class="btn-outline">Apply</button>
</form>

<div class="space-y-2">
@forelse ($products as $product)
    <div class="card flex flex-wrap items-center gap-3 p-3">
        <input type="checkbox" name="ids[]" value="{{ $product->id }}" form="bulk" class="row-cb h-4 w-4">
        @if ($product->image)
            <img src="{{ asset('storage/'.$product->image) }}" class="h-14 w-14 shrink-0 rounded object-cover" alt="">
        @else
            <div class="h-14 w-14 shrink-0 rounded bg-sky-100"></div>
        @endif
        <div class="min-w-0 flex-1 basis-40">
            <p class="truncate font-medium">{{ $product->name }}</p>
            <p class="truncate text-xs text-gray-500">{{ $product->category->parent ? $product->category->parent->name.' › ' : '' }}{{ $product->category->name }}{{ $product->brandRecord ? ' · '.$product->brandRecord->name : '' }}</p>
            <div class="mt-1 flex flex-wrap gap-1">
                @if ($product->is_bestseller)<span class="rounded bg-amber-100 px-1.5 text-[11px] text-amber-700">Best Seller</span>@endif
                @if ($product->is_trending)<span class="rounded bg-rose-100 px-1.5 text-[11px] text-rose-700">Trending</span>@endif
                @if ($product->is_latest)<span class="rounded bg-emerald-100 px-1.5 text-[11px] text-emerald-700">Latest</span>@endif
            </div>
        </div>
        <div class="text-sm">{{ $product->price !== null ? '₹'.number_format($product->price, 2) : '-' }}</div>
        <span class="rounded-full px-2 py-0.5 text-xs {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $product->is_active ? 'Active' : 'Hidden' }}</span>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.products.edit', $product) }}" class="btn-outline !px-3 !py-1.5">Edit</a>
            <form method="POST" action="{{ route('admin.products.duplicate', $product) }}">@csrf<button class="btn-outline !px-3 !py-1.5">Duplicate</button></form>
            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">@csrf @method('DELETE')<button class="btn-danger !px-3 !py-1.5">Delete</button></form>
        </div>
    </div>
@empty
    <p class="card p-6 text-center text-gray-500">No products found.</p>
@endforelse
</div>

<div class="mt-4">{{ $products->links() }}</div>

<script>
    const act = document.getElementById('act'), tgt = document.getElementById('tgt');
    act.onchange = () => tgt.classList.toggle('hidden', act.value !== 'move');
    document.getElementById('selAll').onchange = e => document.querySelectorAll('.row-cb').forEach(c => c.checked = e.target.checked);
    function bulkOk(f) {
        const n = document.querySelectorAll('.row-cb:checked').length;
        if (!n) { alert('Tick at least one product first.'); return false; }
        if (act.value === 'move' && !tgt.value) { alert('Choose the category to move to.'); return false; }
        return act.value === 'delete' ? confirm('Delete ' + n + ' product(s)? This cannot be undone.') : true;
    }
</script>
@endsection