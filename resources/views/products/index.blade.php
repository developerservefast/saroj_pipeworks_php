@extends('layouts.app')
@section('header_eyebrow', 'Catalog')
@section('header_title', 'Our Products')
@section('header_sub', 'Pipes, fittings, taps and sanitary ware.')
@section('title', 'Products | ' . config('business.name'))

@section('content')

<section class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-6 md:py-10 lg:flex-row lg:gap-8">

    <form method="GET" action="{{ url('/products') }}" role="search" class="relative lg:hidden">
        <label for="qMob" class="sr-only">Search products</label>
        <svg class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.2-5.2m0 0A7.5 7.5 0 1 0 5.2 5.2a7.5 7.5 0 0 0 10.6 10.6Z"/></svg>
        <input id="qMob" type="text" name="q" value="{{ $search }}" placeholder="Search products" enterkeyhint="search" autocomplete="off" class="input !py-3 !pl-10 !pr-20">
        @if ($search !== '')
            <a href="{{ url('/products') . ($active ? '?category=' . $active->id : '') }}" aria-label="Clear search" class="absolute right-12 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full text-xl text-gray-500 hover:bg-gray-100">&times;</a>
        @endif
        <button type="submit" aria-label="Search" class="absolute right-1.5 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-lg bg-brand text-white hover:bg-brand-dark">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.2-5.2m0 0A7.5 7.5 0 1 0 5.2 5.2a7.5 7.5 0 0 0 10.6 10.6Z"/></svg>
        </button>
    </form>

    <button id="filterBtn" type="button" class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm font-medium shadow-sm lg:hidden">
        <span>Categories{{ $active ? ': ' . $active->name : '' }}</span>
        <span aria-hidden="true">&#9662;</span>
    </button>

    <aside id="filterBox" class="hidden w-full shrink-0 bg-white p-4 shadow-sm sm:p-6 lg:block lg:w-80">
        <form method="GET" action="{{ url('/products') }}" role="search" class="relative mb-4 hidden lg:block">
        <label for="qDesk" class="sr-only">Search products</label>
        <svg class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.2-5.2m0 0A7.5 7.5 0 1 0 5.2 5.2a7.5 7.5 0 0 0 10.6 10.6Z"/></svg>
        <input id="qDesk" type="text" name="q" value="{{ $search }}" placeholder="Search products" enterkeyhint="search" autocomplete="off" class="input !py-3 !pl-10 !pr-20">
        @if ($search !== '')
            <a href="{{ url('/products') . ($active ? '?category=' . $active->id : '') }}" aria-label="Clear search" class="absolute right-12 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full text-xl text-gray-500 hover:bg-gray-100">&times;</a>
        @endif
        <button type="submit" aria-label="Search" class="absolute right-1.5 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-lg bg-brand text-white hover:bg-brand-dark">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.2-5.2m0 0A7.5 7.5 0 1 0 5.2 5.2a7.5 7.5 0 0 0 10.6 10.6Z"/></svg>
        </button>
    </form>
        <a href="{{ url('/products') }}" class="mb-3 block text-sm text-brand">All categories</a>
        @foreach ($tree as $cat)
            @php $open = $active && ($active->id === $cat->id || $active->parent_id === $cat->id); @endphp
            @if ($cat->children->isEmpty())
                <a href="{{ url('/products?category=' . $cat->id) }}"
                   class="block border-b border-dotted py-3 font-medium {{ $active?->id === $cat->id ? 'text-brand' : '' }}">{{ $cat->name }}</a>
            @else
                <details @if($open) open @endif class="border-b border-dotted">
                    <summary class="cursor-pointer list-none py-3 font-medium">{{ $cat->name }}</summary>
                    <div class="pb-2 pl-3">
                        <a href="{{ url('/products?category=' . $cat->id) }}" class="block py-2 text-sm text-gray-600">All {{ $cat->name }}</a>
                        @foreach ($cat->children as $sub)
                            <a href="{{ url('/products?category=' . $sub->id) }}"
                               class="block border-t border-dotted py-2.5 text-sm {{ $active?->id === $sub->id ? 'text-brand font-semibold' : 'text-gray-600' }}">{{ $sub->name }}</a>
                        @endforeach
                    </div>
                </details>
            @endif
        @endforeach
    </aside>

    <div class="min-w-0 flex-1">
        @if ($products === null)
            <div class="grid grid-cols-2 gap-3 sm:gap-6 xl:grid-cols-3">
                @foreach ($tree as $cat)
                    <a href="{{ url('/products?category=' . $cat->id) }}" class="group flex min-w-0 flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-sky-100 transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                        <div class="flex h-36 items-center justify-center bg-gradient-to-b from-sky-50 to-white p-3 sm:h-56 sm:p-4">
                            @if ($cat->image)
                                <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}" loading="lazy" class="max-h-full max-w-full object-contain transition duration-500 group-hover:scale-105">
                            @else
                                <span class="flex h-16 w-16 items-center justify-center rounded-full bg-brand text-2xl font-bold text-white">{{ strtoupper(mb_substr($cat->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="break-words border-t border-sky-50 px-2 py-4 text-center text-base font-semibold text-brand-dark sm:py-6 sm:text-lg">{{ $cat->name }}</div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3 sm:mb-6">
                <h2 class="min-w-0 break-words text-xl font-bold text-brand-dark sm:text-2xl">{{ $active ? $active->name : ($search !== '' ? 'Results for "' . $search . '"' : 'All products') }}</h2>
                <form method="GET" class="flex items-center gap-2">
                    @if ($active)<input type="hidden" name="category" value="{{ $active->id }}">@endif
                    @if ($search !== '')<input type="hidden" name="q" value="{{ $search }}">@endif
                    <label for="sortSel" class="text-sm text-gray-600">Sort</label>
                    <select id="sortSel" name="sort" onchange="this.form.submit()" class="input !w-auto">
                        <option value="" @selected($sort === '')>Featured</option>
                        <option value="new" @selected($sort === 'new')>Newest</option>
                        <option value="az" @selected($sort === 'az')>Name A-Z</option>
                        <option value="za" @selected($sort === 'za')>Name Z-A</option>
                    </select>
                </form>
            </div>
            <div class="grid grid-cols-2 gap-3 sm:gap-6 xl:grid-cols-3">
                @forelse ($products as $product)
                    <a href="{{ url('/products/' . $product->getRouteKey()) }}" class="group flex h-full min-w-0 flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                        <div class="relative flex h-36 items-center justify-center bg-gradient-to-b from-sky-50 to-white p-3 sm:h-52 sm:p-4">
                            @include('products._badges', ['product' => $product])
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy" class="max-h-full max-w-full object-contain transition duration-500 group-hover:scale-105">
                            @endif
                        </div>
                        <div class="flex flex-1 flex-col border-t border-gray-100 p-3 sm:p-4">
                            <p class="truncate text-[11px] font-medium uppercase tracking-wide text-brand sm:text-xs">{{ $product->brandRecord?->name ?? $product->brand }}</p>
                            <p class="mt-1 line-clamp-2 text-sm font-semibold text-brand-dark sm:text-base">{{ $product->name }}</p>
                            <div class="mt-auto flex flex-wrap gap-1 pt-2">
                                @foreach ($product->variants->whereNotNull('hex') as $v)
                                    <span title="{{ $v->name }}" class="h-4 w-4 rounded-full border" style="background: {{ $v->hex }}"></span>
                                @endforeach
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full rounded-2xl bg-sky-50 p-8 text-center ring-1 ring-sky-100">
                        <p class="font-semibold text-brand-dark">No products found{{ $search !== '' ? ' for "' . $search . '"' : '' }}.</p>
                        <p class="mt-1 text-sm text-gray-600">Check the spelling, try fewer words, or call us and we will help.</p>
                        <a href="{{ url('/products') }}" class="btn-primary mt-4 !py-3">Clear search</a>
                    </div>
                @endforelse
            </div>
            <div class="mt-8">{{ $products->links() }}</div>
        @endif
    </div>
</section>

<script>
    document.getElementById('filterBtn').onclick = () => document.getElementById('filterBox').classList.toggle('hidden');
</script>
@endsection
