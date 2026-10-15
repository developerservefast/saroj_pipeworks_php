@extends('layouts.app')
@section('title', $product->name . ' | ' . config('business.name'))
@section('meta_description', $product->meta_description ?: \Illuminate\Support\Str::limit(strip_tags((string) $product->description), 155))

@section('content')
@php
    $tel = preg_replace('/[^0-9+]/', '', (string) config('business.phone'));
    $brandName = $product->brandRecord?->name ?? $product->brand;
    $parent = $product->category->parent;
    $gallery = $product->images->pluck('path')->all() ?: ($product->image ? [$product->image] : []);
    $fmt = fn ($n) => '₹'.number_format($n, 2);
    $disc = (int) $product->discount_percent;
@endphp
<div class="max-w-6xl mx-auto px-4 py-6 md:py-10">
    <a href="{{ route('products.index', ['category' => $product->category->id]) }}" class="text-sm text-brand hover:underline">&larr; Back to {{ $product->category->name }}</a>

    <div class="mt-6 grid gap-6 md:grid-cols-2 md:gap-8">
        <div class="min-w-0">
            @if ($gallery)
                <img id="mainImg" src="{{ asset('storage/'.$gallery[0]) }}" alt="{{ $product->name }}" class="w-full rounded-xl border border-gray-200 object-contain bg-white p-4">
                @if (count($gallery) > 1)
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach ($gallery as $g)
                            <img src="{{ asset('storage/'.$g) }}" alt="" onclick="document.getElementById('mainImg').src=this.src"
                                 class="h-16 w-16 cursor-pointer rounded border border-gray-200 bg-white object-contain p-1 hover:border-brand">
                        @endforeach
                    </div>
                @endif
            @else
                <div class="h-72 w-full rounded-xl bg-sky-50 flex items-center justify-center text-gray-400">No image</div>
            @endif
        </div>

        <div class="min-w-0">
            <p class="text-sm text-brand">@if ($parent) {{ $parent->name }} &rsaquo; @endif{{ $product->category->name }}</p>
            <div class="mt-2 flex flex-wrap gap-2">
                @if ($product->is_bestseller)<span class="rounded bg-amber-500 px-2 py-0.5 text-xs font-semibold text-white">Best Seller</span>@endif
                @if ($product->is_trending)<span class="rounded bg-rose-500 px-2 py-0.5 text-xs font-semibold text-white">Trending</span>@endif
                @if ($product->is_latest)<span class="rounded bg-emerald-500 px-2 py-0.5 text-xs font-semibold text-white">Latest</span>@endif
            </div>
            <h1 class="text-2xl font-bold text-brand-dark mt-1 sm:text-3xl">{{ $product->name }}</h1>

            <p class="mt-4 text-xl font-semibold sm:text-2xl {{ $product->price !== null ? 'text-brand-dark' : 'text-gray-500' }}">
                @if ($product->price === null)
                    Contact for price
                @elseif ($disc > 0)
                    {{ $fmt($product->price * (100 - $disc) / 100) }}
                    <span class="ml-2 text-base font-normal text-gray-400 line-through">{{ $fmt($product->price) }}</span>
                    <span class="ml-2 rounded bg-green-100 px-2 py-0.5 text-sm text-green-700">{{ $disc }}% off</span>
                @else
                    {{ $fmt($product->price) }}
                @endif
                @if ($product->price !== null && $product->unit) <span class="text-base font-normal text-gray-500">/ {{ $product->unit }}</span> @endif
            </p>

            @if ($product->description)
                <div class="rich mt-4 text-gray-700 leading-relaxed">{!! \App\Support\Html::display($product->description) !!}</div>
            @endif

            @if ($product->variants->isNotEmpty())
                <div class="mt-6">
                    <p class="text-sm text-gray-500">Available variants</p>
                    <div class="mt-2 flex flex-wrap gap-3">
                        @foreach ($product->variants as $v)
                            <span class="flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700">
                                @if ($v->hex) <span class="h-5 w-5 rounded-full border border-gray-300" style="background: {{ $v->hex }}"></span> @endif
                                @if ($v->image) <img src="{{ asset('storage/'.$v->image) }}" class="h-8 w-8 rounded object-cover" alt=""> @endif
                                {{ $v->name }}
                                @if ($v->price !== null) <span class="text-gray-500">· {{ $fmt($v->price) }}</span> @endif
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <dl class="mt-6 grid grid-cols-2 gap-y-2 text-sm max-w-sm">
                @if ($brandName) <dt class="text-gray-500">Brand</dt><dd>{{ $brandName }}</dd> @endif
                @if ($product->size) <dt class="text-gray-500">Size</dt><dd>{{ $product->size }}</dd> @endif
                @if ($product->unit) <dt class="text-gray-500">Unit</dt><dd>{{ $product->unit }}</dd> @endif
                @if ($product->warranty) <dt class="text-gray-500">Warranty</dt><dd>{{ $product->warranty }}</dd> @endif
            </dl>

            @if ($product->details)
                <div class="mt-6">
                    <p class="text-sm text-gray-500">Product details</p>
                    <div class="rich mt-1 text-gray-700">{!! \App\Support\Html::display($product->details) !!}</div>
                </div>
            @endif

            <a href="tel:{{ $tel }}" class="mt-8 block rounded-lg bg-brand px-6 py-3 text-center font-semibold text-white hover:bg-brand-dark sm:inline-block">
                Call to enquire: {{ config('business.phone') }}
            </a>
        </div>
    </div>

    @if ($related->isNotEmpty())
        <h2 class="text-xl font-bold text-brand-dark mt-12 mb-4">Related products</h2>
        <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
            @foreach ($related as $item)
                <a href="{{ route('products.show', $item) }}" class="block border border-gray-200 rounded-xl p-3 sm:p-4 hover:shadow">
                    @if ($item->image)
                        <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->name }}" class="mb-3 h-28 w-full object-contain sm:h-32">
                    @endif
                    <p class="font-medium text-sm sm:text-base">{{ $item->name }}</p>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection