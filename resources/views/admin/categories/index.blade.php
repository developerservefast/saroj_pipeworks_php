@extends('admin.layouts.app')
@section('title', 'Categories')

@section('content')
@if ($errors->any())
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</div>
@endif

<div class="grid gap-6 lg:grid-cols-3">
    <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="card h-fit space-y-4 p-5">
        @csrf
        <h2 class="font-semibold text-gray-800">Add Category</h2>
        <div>
            <label class="mb-1 block text-xs font-semibold uppercase text-gray-500">Name *</label>
            <input name="name" required class="input">
        </div>
        <div>
            <label class="mb-1 block text-xs font-semibold uppercase text-gray-500">Image</label>
            <input type="file" name="image" accept="image/*" class="file-input">
            <p class="mt-1 text-xs text-gray-500">Shown on the public Products page card.</p>
        </div>
        <div>
            <label class="mb-1 block text-xs font-semibold uppercase text-gray-500">Sort order</label>
            <input type="number" name="sort_order" min="0" value="0" class="input">
            <p class="mt-1 text-xs text-gray-500">Lower numbers appear first.</p>
        </div>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" checked> Active</label>
        <button class="btn-primary w-full">Add Category</button>
    </form>

    <div class="lg:col-span-2">
        @php $st = request('status'); @endphp
        <div class="mb-4 flex flex-wrap items-center gap-2">
            @foreach ([null => 'All', 'active' => 'Active', 'inactive' => 'Inactive'] as $k => $label)
                <a href="{{ route('admin.categories.index', array_filter(['status' => $k, 'q' => request('q')])) }}"
                   class="rounded-lg px-4 py-2 text-sm font-medium {{ $st == $k ? 'bg-gray-900 text-white' : 'border border-gray-200 bg-white text-gray-600 hover:bg-gray-50' }}">{{ $label }}</a>
            @endforeach
            <form method="GET" class="ml-auto flex gap-2">
                <input type="hidden" name="status" value="{{ $st }}">
                <input name="q" value="{{ request('q') }}" placeholder="Search name…" class="input w-48">
                <button class="btn-outline">Search</button>
            </form>
        </div>

        <div class="card overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Image</th><th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Sub cats</th><th class="px-4 py-3">Products</th>
                        <th class="px-4 py-3">Order</th><th class="px-4 py-3">Active</th><th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($categories as $c)
                    <tr class="border-t border-gray-100">
                        <td class="px-4 py-2">
                            @if ($c->image)
                                <img src="{{ asset('storage/'.$c->image) }}" class="h-12 w-12 rounded border object-cover" alt="">
                            @else
                                <div class="h-12 w-12 rounded bg-sky-100"></div>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <form id="u{{ $c->id }}" method="POST" action="{{ route('admin.categories.update', $c) }}" enctype="multipart/form-data">@csrf @method('PUT')</form>
                            <input form="u{{ $c->id }}" name="name" value="{{ $c->name }}" class="input w-44 font-medium">
                            <input form="u{{ $c->id }}" type="file" name="image" accept="image/*" class="file-input mt-1">
                        </td>
                        <td class="px-4 py-2 text-gray-600">{{ $c->children_count }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ $c->products_count }}</td>
                        <td class="px-4 py-2"><input form="u{{ $c->id }}" type="number" name="sort_order" min="0" value="{{ $c->sort_order }}" class="input w-20"></td>
                        <td class="px-4 py-2"><input form="u{{ $c->id }}" type="checkbox" name="is_active" value="1" @checked($c->is_active)></td>
                        <td class="whitespace-nowrap px-4 py-2 text-right">
                            <button form="u{{ $c->id }}" class="btn-outline">Save</button>
                            <form method="POST" action="{{ route('admin.categories.destroy', $c) }}" class="inline" onsubmit="return confirm('Delete {{ addslashes($c->name) }}?')">
                                @csrf @method('DELETE')
                                <button class="btn-danger {{ ($c->children_count || $c->products_count) ? 'opacity-40 cursor-not-allowed' : '' }}" @disabled($c->children_count || $c->products_count) title="{{ $c->children_count ? 'Has sub categories' : ($c->products_count ? 'Has products' : 'Delete') }}">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">No categories found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
