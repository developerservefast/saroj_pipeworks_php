@extends('layouts.app')
@section('title', $product->name . ' | ' . config('business.name'))

@section('content')
@php $tel = preg_replace('/[^0-9+]/', '', config('business.phone')); @endphp
<div class="max-w-6xl mx-auto px-4 py-10">
    <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="text-sm text-brand hover:underline">&larr; Back to {{ $product->category->name }}</a>

    <div class="mt-6 grid gap-8 md:grid-cols-2">
        <div>
            @if ($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="w-full rounded-xl border border-gray-200 object-contain bg-white p-4">
            @else
                <div class="h-72 w-full rounded-xl bg-sky-50 flex items-center justify-center text-gray-400">No image</div>
            @endif
        </div>

        <div>
            <p class="text-sm text-brand">{{ $product->category->name }}</p>
            <h1 class="text-3xl font-bold text-brand-dark mt-1">{{ $product->name }}</h1>

            <p class="mt-4 text-2xl font-semibold {{ $product->price !== null ? 'text-brand-dark' : 'text-gray-500' }}">
                {{ $product->price !== null ? '₹'.number_format($product->price, 2).($product->unit ? ' / '.$product->unit : '') : 'Contact for price' }}
            </p>

            @if ($product->description)
                <p class="mt-4 text-gray-700 leading-relaxed">{{ $product->description }}</p>
            @endif

            <dl class="mt-6 grid grid-cols-2 gap-y-2 text-sm max-w-sm">
                @if ($product->brand) <dt class="text-gray-500">Brand</dt><dd>{{ $product->brand }}</dd> @endif
                @if ($product->size) <dt class="text-gray-500">Size</dt><dd>{{ $product->size }}</dd> @endif
                @if ($product->unit) <dt class="text-gray-500">Unit</dt><dd>{{ $product->unit }}</dd> @endif
                @if ($product->warranty) <dt class="text-gray-500">Warranty</dt><dd>{{ $product->warranty }}</dd> @endif
            </dl>

            <a href="tel:{{ $tel }}" class="inline-block mt-8 bg-brand hover:bg-brand-dark text-white font-semibold px-6 py-3 rounded-lg">
                Call to enquire: {{ config('business.phone') }}
            </a>
        </div>
    </div>

    @if ($related->isNotEmpty())
        <h2 class="text-xl font-bold text-brand-dark mt-12 mb-4">Related products</h2>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($related as $item)
                <a href="{{ route('products.show', $item) }}" class="block border border-gray-200 rounded-xl p-4 hover:shadow">
                    <p class="font-medium">{{ $item->name }}</p>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
