@php $only = $only ?? null; @endphp
<div class="absolute left-2 top-2 z-10 flex flex-col items-start gap-1">
    @if ($product->is_bestseller && (! $only || $only === 'is_bestseller'))<span class="rounded bg-amber-500 px-2 py-0.5 text-[10px] font-semibold text-white sm:text-xs">Best Seller</span>@endif
    @if ($product->is_trending && (! $only || $only === 'is_trending'))<span class="rounded bg-rose-500 px-2 py-0.5 text-[10px] font-semibold text-white sm:text-xs">Trending</span>@endif
    @if ($product->is_latest && (! $only || $only === 'is_latest'))<span class="rounded bg-emerald-500 px-2 py-0.5 text-[10px] font-semibold text-white sm:text-xs">Latest</span>@endif
</div>