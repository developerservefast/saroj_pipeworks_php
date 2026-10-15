@extends('admin.layouts.app')
@section('title', 'Sub Categories')

@section('content')
@if ($errors->any())
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</div>
@endif

<div class="grid gap-6 lg:grid-cols-3">
    <form method="POST" action="{{ route('admin.subcategories.store') }}" class="card h-fit space-y-4 p-5">
        @csrf
        <h2 class="font-semibold text-gray-800">Add Sub Category</h2>
        <div>
            <label class="mb-1 block text-xs font-semibold uppercase text-gray-500">Category *</label>
            <select name="parent_id" required class="input">
                <option value="">Select Category</option>
                @foreach ($parents as $p)
                    <option value="{{ $p->id }}" @selected(old('parent_id', request('category')) == $p->id)>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-semibold uppercase text-gray-500">Name *</label>
            <input name="name" required value="{{ old('name') }}" class="input">
        </div>
        <div>
            <label class="mb-1 block text-xs font-semibold uppercase text-gray-500">Sort order</label>
            <input type="number" name="sort_order" min="0" value="0" class="input">
        </div>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" checked> Active</label>
        <button class="btn-primary w-full">Add Sub Category</button>
    </form>

    <div class="lg:col-span-2">
        <form method="GET" class="mb-4 flex flex-wrap gap-2">
            <select name="category" class="input w-52">
                <option value="">All Categories</option>
                @foreach ($parents as $p)
                    <option value="{{ $p->id }}" @selected(request('category') == $p->id)>{{ $p->name }}</option>
                @endforeach
            </select>
            <input name="q" value="{{ request('q') }}" placeholder="Search sub category…" class="input w-56">
            <button class="btn-outline">Search</button>
        </form>

        <div class="card overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Category</th><th class="px-4 py-3">Sub category</th>
                        <th class="px-4 py-3">Products</th><th class="px-4 py-3">Order</th>
                        <th class="px-4 py-3">Active</th><th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($subs as $s)
                    <tr class="border-t border-gray-100">
                        <td class="px-4 py-2">
                            <form id="s{{ $s->id }}" method="POST" action="{{ route('admin.subcategories.update', $s) }}">@csrf @method('PUT')</form>
                            <select form="s{{ $s->id }}" name="parent_id" class="input w-40">
                                @foreach ($parents as $p)
                                    <option value="{{ $p->id }}" @selected($s->parent_id == $p->id)>{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-4 py-2"><input form="s{{ $s->id }}" name="name" value="{{ $s->name }}" class="input w-48 font-medium"></td>
                        <td class="px-4 py-2 text-gray-600">{{ $s->products_count }}</td>
                        <td class="px-4 py-2"><input form="s{{ $s->id }}" type="number" name="sort_order" min="0" value="{{ $s->sort_order }}" class="input w-20"></td>
                        <td class="px-4 py-2"><input form="s{{ $s->id }}" type="checkbox" name="is_active" value="1" @checked($s->is_active)></td>
                        <td class="whitespace-nowrap px-4 py-2 text-right">
                            <button form="s{{ $s->id }}" class="btn-outline">Save</button>
                            <form method="POST" action="{{ route('admin.subcategories.destroy', $s) }}" class="inline" onsubmit="return confirm('Delete {{ addslashes($s->name) }}?')">
                                @csrf @method('DELETE')
                                <button class="btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">No sub categories found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $subs->links() }}</div>
    </div>
</div>
@endsection
