@extends('layouts.app')
@section('title', 'Products | ' . config('business.name'))

@section('content')
<section class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-6 md:py-10 lg:flex-row lg:gap-8">

    <button id="filterBtn" type="button" class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm font-medium shadow-sm lg:hidden">
        <span>Categories{{ $active ? ': ' . $active->name : '' }}</span>
        <span aria-hidden="true">&#9662;</span>
    </button>

    <aside id="filterBox" class="hidden w-full shrink-0 bg-white p-4 shadow-sm sm:p-6 lg:block lg:w-80">
        <form method="GET" class="mb-4">
            <input name="q" value="{{ $search }}" placeholder="Search products" class="w-full rounded border px-3 py-2.5">
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
                    <a href="{{ url('/products?category=' . $cat->id) }}" class="bg-white shadow-sm">
                        <div class="flex h-36 items-center justify-center p-3 sm:h-64 sm:p-4">
                            @if ($cat->image)
                                <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}" class="max-h-full object-contain">
                            @endif
                        </div>
                        <div class="bg-gray-200 px-2 py-4 text-center text-base text-brand sm:py-8 sm:text-xl">{{ $cat->name }}</div>
                    </a>
                @endforeach
            </div>
        @else
            @if ($active) <h1 class="mb-4 text-xl font-bold text-brand-dark sm:mb-6 sm:text-2xl">{{ $active->name }}</h1> @endif
            <div class="grid grid-cols-2 gap-3 sm:gap-6 xl:grid-cols-3">
                @forelse ($products as $product)
                    <a href="{{ url('/products/' . $product->getRouteKey()) }}" class="bg-white shadow-sm">
                        <div class="relative flex h-36 items-center justify-center p-3 sm:h-56 sm:p-4">
                            @include('products._badges', ['product' => $product])
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="max-h-full object-contain">
                            @endif
                        </div>
                        <div class="bg-gray-100 p-3 sm:p-4">
                            <p class="text-sm font-semibold text-brand-dark sm:text-base">{{ $product->name }}</p>
                            <p class="text-xs text-gray-600 sm:text-sm">{{ $product->brandRecord?->name ?? $product->brand }}</p>
                            <div class="mt-2 flex flex-wrap gap-1">
                                @foreach ($product->variants->whereNotNull('hex') as $v)
                                    <span title="{{ $v->name }}" class="h-4 w-4 rounded-full border" style="background: {{ $v->hex }}"></span>
                                @endforeach
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="col-span-full text-gray-600">No products found.</p>
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
