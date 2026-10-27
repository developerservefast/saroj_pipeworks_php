@php $only = $only ?? null; @endphp
<div class="absolute left-2 top-2 z-10 flex flex-col items-start gap-1.5">
    @if ($product->is_bestseller && (! $only || $only === 'is_bestseller'))
        <span class="coupon coupon-gold"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.9 6.3 6.9.7-5.2 4.6 1.5 6.8L12 17l-6.1 3.4 1.5-6.8L2.2 9l6.9-.7z"/></svg><span class="coupon-text">Best Seller</span></span>
    @endif
    @if ($product->is_trending && (! $only || $only === 'is_trending'))
        <span class="coupon coupon-rose"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 0s.9 4-2 6.9C9 9.4 6 11 6 15a6 6 0 0 0 12 0c0-2.4-1.200-4.200-2.400-5.500-.1 1.800-.9 2.700-1.700 3C14.500 9 14.200 3.800 13.500 0z"/></svg><span class="coupon-text">Trending</span></span>
    @endif
    @if ($product->is_latest && (! $only || $only === 'is_latest'))
        <span class="coupon coupon-green"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 1l2.2 5.300 5.800-.3-3.200 4.800 3.700 4.500-5.600-1.200L12 20l-2.900-5.900-5.600 1.200 3.700-4.500L4 6l5.800.3z"/></svg><span class="coupon-text">New</span></span>
    @endif
</div>
