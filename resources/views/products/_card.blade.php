<a href="{{ url('/products/' . $product->getRouteKey()) }}" class="group flex h-full min-w-0 flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 transition duration-300 hover:-translate-y-1 hover:shadow-lg">
    <div class="relative flex h-36 items-center justify-center bg-gradient-to-b from-sky-50 to-white p-3 sm:h-52 sm:p-4">
        @include('products._badges', ['product' => $product, 'only' => $only ?? null])
        @if ($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy" class="max-h-full max-w-full object-contain transition duration-500 group-hover:scale-105">
        @endif
    </div>
    <div class="flex flex-1 flex-col border-t border-gray-100 p-3 sm:p-4">
        <p class="truncate text-[11px] font-medium uppercase tracking-wide text-brand sm:text-xs">{{ $product->brandRecord?->name ?? $product->brand }}</p>
        <p class="mt-1 line-clamp-2 text-sm font-semibold text-brand-dark sm:text-base">{{ $product->name }}</p>
        @php $disc = (int) $product->discount_percent; @endphp
        <div class="mt-2 flex flex-wrap items-baseline gap-x-2">
            @if ($product->price === null)
                <span class="text-sm font-semibold text-brand-dark">Contact for price</span>
            @else
                <span class="text-base font-bold text-brand-dark">&#8377;{{ number_format($product->price * (100 - $disc) / 100, 2) }}</span>
                @if ($disc > 0)
                    <span class="text-xs text-gray-400 line-through">&#8377;{{ number_format($product->price, 2) }}</span>
                    <span class="rounded-full bg-green-100 px-2 py-0.5 text-[11px] font-semibold text-green-700">{{ $disc }}% off</span>
                @endif
                @if ($product->unit)
                    <span class="text-xs text-gray-500">/ {{ $product->unit }}</span>
                @endif
            @endif
        </div>
    </div>
</a>
