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
    </div>
</a>
