@extends('layouts.app')
@section('title', ($current->name ?? 'Products') . ' | ' . config('business.name'))

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-brand-dark mb-6">{{ $current->name ?? 'All Products' }}</h1>

    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('products.index') }}"
           class="px-4 py-2 rounded-full text-sm border {{ !$current ? 'bg-brand text-white border-brand' : 'border-gray-300 hover:border-brand hover:text-brand' }}">All</a>
        @foreach ($categories as $c)
            <a href="{{ route('products.index', ['category' => $c->slug]) }}"
               class="px-4 py-2 rounded-full text-sm border {{ $current && $current->id === $c->id ? 'bg-brand text-white border-brand' : 'border-gray-300 hover:border-brand hover:text-brand' }}">{{ $c->name }}</a>
        @endforeach
    </div>

    <form method="GET" class="flex gap-2 mb-8">
        @if ($current) <input type="hidden" name="category" value="{{ $current->slug }}"> @endif
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products"
               class="flex-1 max-w-sm border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
        <button class="bg-brand hover:bg-brand-dark text-white px-5 py-2 rounded-lg text-sm font-semibold">Search</button>
    </form>

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @forelse ($products as $product)
            <a href="{{ route('products.show', $product) }}"
               class="block bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition">
                @if ($product->image)
                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="h-40 w-full object-contain bg-white p-2">
                @else
                    <div class="h-40 w-full bg-sky-50 flex items-center justify-center text-sm text-gray-400">No image</div>
                @endif
                <div class="p-4">
                    <p class="text-xs text-brand">{{ $product->category->name }}</p>
                    <h2 class="font-semibold text-gray-800 mt-1">{{ $product->name }}</h2>
                    <p class="mt-2 text-sm {{ $product->price !== null ? 'text-brand-dark font-semibold' : 'text-gray-500' }}">
                        {{ $product->price !== null ? '₹'.number_format($product->price, 2).($product->unit ? ' / '.$product->unit : '') : 'Contact for price' }}
                    </p>
                </div>
            </a>
        @empty
            <p class="col-span-full text-gray-500">No products found.</p>
        @endforelse
    </div>

    <div class="mt-8">{{ $products->links() }}</div>
</div>
@endsection
