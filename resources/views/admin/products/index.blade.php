
@extends('admin.layouts.app')
@section('title', 'Products')
@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <h2 class="text-2xl font-bold text-sky-700">Products</h2>
        <a href="{{ route('admin.products.create') }}"
           class="bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded-lg text-sm font-semibold">+ Add Product</a>
    </div>

    <form method="GET" class="flex flex-wrap gap-2 mb-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products"
               class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <select name="category" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">All categories</option>
            @foreach ($categories as $c)
                <option value="{{ $c->id }}" @selected(request('category') == $c->id)>{{ $c->name }}</option>
            @endforeach
        </select>
        <button class="bg-sky-100 text-sky-700 px-4 py-2 rounded-lg text-sm">Filter</button>
    </form>

    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-sky-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3">Image</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Price</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr class="border-t border-gray-100">
                        <td class="px-4 py-2">
                            @if ($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}" class="h-10 w-10 rounded object-cover" alt="">
                            @else
                                <div class="h-10 w-10 rounded bg-sky-100"></div>
                            @endif
                        </td>
                        <td class="px-4 py-2 font-medium">{{ $product->name }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ $product->category->name }}</td>
                        <td class="px-4 py-2">{{ $product->price !== null ? '₹'.number_format($product->price, 2) : '-' }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded-full text-xs {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $product->is_active ? 'Active' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-right whitespace-nowrap">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-sky-600 hover:underline mr-3">Edit</a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline"
                                  onsubmit="return confirm('Delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $products->links() }}</div>
@endsection
