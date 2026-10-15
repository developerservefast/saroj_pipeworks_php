<a href="{{ url('/products/' . $product->getRouteKey()) }}" class="block bg-white shadow-sm">
    <div class="relative flex h-36 items-center justify-center p-3 sm:h-52 sm:p-4">
        @include('products._badges', ['product' => $product, 'only' => $only ?? null])
        @if ($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="max-h-full object-contain">
        @endif
    </div>
    <div class="bg-gray-100 p-3 sm:p-4">
        <p class="text-sm font-semibold text-brand-dark sm:text-base">{{ $product->name }}</p>
        <p class="text-xs text-gray-600 sm:text-sm">{{ $product->brandRecord?->name ?? $product->brand }}</p>
    </div>
</a>