@extends('layouts.app')
@section('header_title', 'Product Details')
@section('header_parent', 'Products|/products')
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
<div class="max-w-6xl mx-auto px-4 py-6 pb-24 md:py-10 md:pb-10">
    @php
        $wa = preg_replace('/\D/', '', (string) config('home.whatsapp'));
        $waUrl = $wa ? 'https://wa.me/' . $wa . '?text=' . rawurlencode('Hello, I am interested in ' . $product->name . ' - ' . url()->current()) : null;
        $rs = '&#8377;';
    @endphp

    <a href="{{ route('products.index', ['category' => $product->category->id]) }}" class="inline-flex min-h-11 items-center gap-1 text-sm font-medium text-brand hover:underline">&larr; Back to {{ $product->category->name }}</a>

    <div id="product-gallery" class="mt-2 grid gap-6 lg:grid-cols-2 lg:gap-10">
        <div class="min-w-0">
            <div class="lg:sticky lg:top-28">
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-b from-sky-50 to-white shadow-sm ring-1 ring-sky-100">
                    @include('products._badges', ['product' => $product])
                    @if ($gallery)
                        <button type="button" id="zoomOpen" class="relative block w-full cursor-zoom-in" aria-label="Open image full screen">
                            <img id="mainImg" src="{{ asset('storage/'.$gallery[0]) }}" alt="{{ $product->name }}" class="aspect-square w-full object-contain p-4 sm:p-8">
                            <span class="absolute bottom-3 right-3 rounded-full bg-brand-dark/80 px-3 py-1 text-xs font-medium text-white">Tap to zoom</span>
                        </button>
                    @else
                        <div class="flex aspect-square w-full items-center justify-center bg-gradient-to-br from-brand-dark to-brand text-white/80">
                            <svg class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke-width="1.3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085"/></svg>
                        </div>
                    @endif
                </div>
                @if (count($gallery) > 1)
                    <div class="mt-3 flex gap-2 overflow-x-auto pb-1">
                        @foreach ($gallery as $g)
                            <img src="{{ asset('storage/'.$g) }}" alt="" data-thumb
                                 onclick="document.getElementById('mainImg').src=this.src;document.querySelectorAll('[data-thumb]').forEach(t=>t.classList.remove('ring-2','ring-brand'));this.classList.add('ring-2','ring-brand')"
                                 class="h-16 w-16 shrink-0 cursor-pointer rounded-lg border border-gray-200 bg-white object-contain p-1 hover:border-brand {{ $loop->first ? 'ring-2 ring-brand' : '' }}">
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="min-w-0">
            <p class="text-sm font-medium text-brand">@if ($parent){{ $parent->name }} &rsaquo; @endif{{ $product->category->name }}</p>
            <h1 class="mt-1 break-words text-2xl font-bold text-brand-dark sm:text-4xl">{{ $product->name }}</h1>
            @if ($brandName)
                <p class="mt-2 text-sm text-gray-500">Brand: <span class="font-semibold text-gray-700">{{ $brandName }}</span></p>
            @endif

            <div class="mt-5 rounded-2xl bg-sky-50 p-4 ring-1 ring-sky-100 sm:p-5">
                @if ($product->price === null)
                    <p class="text-xl font-bold text-brand-dark sm:text-2xl">Contact for price</p>
                    <p class="mt-1 text-sm text-gray-600">Call us to get the best price.</p>
                @else
                    <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                        @if ($disc > 0)
                            <span class="text-2xl font-bold text-brand-dark sm:text-3xl">{!! $rs !!}{{ number_format($product->price * (100 - $disc) / 100, 2) }}</span>
                            <span class="text-base text-gray-400 line-through">{!! $rs !!}{{ number_format($product->price, 2) }}</span>
                            <span class="rounded-full bg-green-100 px-2.5 py-0.5 text-sm font-semibold text-green-700">{{ $disc }}% off</span>
                        @else
                            <span class="text-2xl font-bold text-brand-dark sm:text-3xl">{!! $rs !!}{{ number_format($product->price, 2) }}</span>
                        @endif
                        @if ($product->unit)<span class="text-sm text-gray-500">/ {{ $product->unit }}</span>@endif
                    </div>
                @endif
            </div>

            @if ($product->description)
                @if ($product->stock_quantity !== null)
                @if ($product->stock_quantity === 0)
                    <p class="mt-4 inline-block rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-700">Out of stock</p>
                @elseif ($product->stock_quantity <= 5)
                    <p class="mt-4 inline-block rounded-full bg-amber-100 px-3 py-1 text-sm font-semibold text-amber-700">Only {{ $product->stock_quantity }} left</p>
                @else
                    <p class="mt-4 inline-block rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-700">In stock</p>
                @endif
            @endif

            <div class="rich mt-5 leading-relaxed text-gray-700">{!! \App\Support\Html::display($product->description) !!}</div>
            @endif

            @if ($product->variants->isNotEmpty())
                <div class="mt-6">
                    <p class="text-sm font-semibold text-brand-dark">Available variants</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach ($product->variants as $v)
                            <span class="flex items-center gap-2 rounded-full border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-700">
                                @if ($v->hex) <span class="h-5 w-5 rounded-full border border-gray-300" style="background: {{ $v->hex }}"></span> @endif
                                @if ($v->image) <img src="{{ asset('storage/'.$v->image) }}" class="h-7 w-7 rounded-full object-cover" alt=""> @endif
                                {{ $v->name }}
                                @if ($v->price !== null) <span class="text-gray-500">&middot; {!! $rs !!}{{ number_format($v->price, 2) }}</span> @endif
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($brandName || $product->size || $product->unit || $product->warranty)
                <dl class="mt-6 divide-y divide-gray-100 overflow-hidden rounded-2xl bg-white text-sm shadow-sm ring-1 ring-gray-100">
                    @if ($brandName)<div class="flex justify-between gap-4 px-4 py-3"><dt class="text-gray-500">Brand</dt><dd class="text-right font-medium text-gray-800">{{ $brandName }}</dd></div>@endif
                    @if ($product->size)<div class="flex justify-between gap-4 px-4 py-3"><dt class="text-gray-500">Size</dt><dd class="text-right font-medium text-gray-800">{{ $product->size }}</dd></div>@endif
                    @if ($product->unit)<div class="flex justify-between gap-4 px-4 py-3"><dt class="text-gray-500">Unit</dt><dd class="text-right font-medium text-gray-800">{{ $product->unit }}</dd></div>@endif
                    @if ($product->warranty)<div class="flex justify-between gap-4 px-4 py-3"><dt class="text-gray-500">Warranty</dt><dd class="text-right font-medium text-gray-800">{{ $product->warranty }}</dd></div>@endif
                </dl>
            @endif

            @if ($product->details)
                <div class="mt-6">
                    <p class="text-sm font-semibold text-brand-dark">Product details</p>
                    <div class="rich mt-2 text-gray-700">{!! \App\Support\Html::display($product->details) !!}</div>
                </div>
            @endif

            <div class="mt-8 hidden flex-wrap gap-3 md:flex">
                <a href="tel:{{ $tel }}" class="inline-flex min-h-12 items-center justify-center rounded-lg bg-brand px-6 py-3 font-semibold text-white transition hover:bg-brand-dark">Call to enquire: {{ config('business.phone') }}</a>
                @if ($waUrl)
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="inline-flex min-h-12 items-center justify-center rounded-lg bg-[#25D366] px-6 py-3 font-semibold text-white transition hover:opacity-90">WhatsApp us</a>
                @endif
            </div>
        </div>
    </div>

    @if ($related->isNotEmpty())
        <div class="mt-12 text-center sm:mt-16">
        <span class="text-xs font-semibold uppercase tracking-widest text-brand">You may also like</span>
        <h2 class="mt-1 text-2xl font-bold text-brand-dark sm:text-3xl">Related products</h2>
    </div>
    <div class="mt-6 grid grid-cols-2 gap-3 sm:gap-6 lg:grid-cols-4">
        @foreach ($related as $item)
            @include('products._card', ['product' => $item])
        @endforeach
    </div>
    @endif
</div>

<div class="fixed inset-x-0 bottom-0 z-40 border-t border-sky-100 bg-white/95 px-3 pt-3 backdrop-blur md:hidden" style="padding-bottom: calc(env(safe-area-inset-bottom, 0px) + 0.75rem)">
    <div class="flex gap-3">
        <a href="tel:{{ $tel }}" class="flex min-h-12 flex-1 items-center justify-center rounded-lg bg-brand font-semibold text-white">Call</a>
        @if ($waUrl)
            <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="flex min-h-12 flex-1 items-center justify-center rounded-lg bg-[#25D366] font-semibold text-white">WhatsApp</a>
        @endif
    </div>
</div>
<style>@media (max-width: 767px) { #floatActions { display: none; } }</style>

@if ($gallery)
@php $zoomImgs = array_values(array_map(fn ($g) => asset('storage/' . $g), $gallery)); @endphp
<div id="lightbox" class="fixed inset-0 z-[60] hidden items-center justify-center overflow-hidden bg-black/90" role="dialog" aria-modal="true" aria-label="Product image">
    <button id="lbClose" type="button" aria-label="Close" class="absolute right-3 top-3 z-10 flex h-11 w-11 items-center justify-center rounded-full bg-white/20 text-2xl text-white hover:bg-white/30">&times;</button>
    <button id="lbPrev" type="button" aria-label="Previous image" class="absolute left-2 top-1/2 z-10 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/20 text-2xl text-white hover:bg-white/30">&lsaquo;</button>
    <button id="lbNext" type="button" aria-label="Next image" class="absolute right-2 top-1/2 z-10 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/20 text-2xl text-white hover:bg-white/30">&rsaquo;</button>
    <img id="lbImg" src="" alt="" class="max-h-[85vh] max-w-full cursor-zoom-in select-none object-contain transition-transform duration-200">
</div>
<script>
(function () {
    const G = @json($zoomImgs);
    const box = document.getElementById('lightbox'), img = document.getElementById('lbImg'), main = document.getElementById('mainImg');
    const prev = document.getElementById('lbPrev'), next = document.getElementById('lbNext');
    let i = 0, x0 = null, zoomed = false;
    if (G.length < 2) { prev.style.display = 'none'; next.style.display = 'none'; }
    function reset() { zoomed = false; img.style.transform = ''; img.classList.add('cursor-zoom-in'); }
    function show(n) { i = (n + G.length) % G.length; img.src = G[i]; reset(); }
    function openBox() { show(Math.max(0, G.indexOf(main.src))); box.classList.remove('hidden'); box.classList.add('flex'); document.body.style.overflow = 'hidden'; }
    function closeBox() { box.classList.add('hidden'); box.classList.remove('flex'); document.body.style.overflow = ''; reset(); }
    document.getElementById('zoomOpen').onclick = openBox;
    document.getElementById('lbClose').onclick = closeBox;
    prev.onclick = () => show(i - 1);
    next.onclick = () => show(i + 1);
    box.onclick = e => { if (e.target === box) closeBox(); };
    img.onclick = e => {
        if (zoomed) { reset(); return; }
        const r = img.getBoundingClientRect();
        img.style.transformOrigin = ((e.clientX - r.left) / r.width * 100) + '% ' + ((e.clientY - r.top) / r.height * 100) + '%';
        img.style.transform = 'scale(2)';
        img.classList.remove('cursor-zoom-in');
        zoomed = true;
    };
    box.addEventListener('touchstart', e => { x0 = e.touches[0].clientX; }, { passive: true });
    box.addEventListener('touchend', e => {
        if (x0 === null || zoomed || G.length < 2) { x0 = null; return; }
        const dx = e.changedTouches[0].clientX - x0;
        if (Math.abs(dx) > 50) show(dx < 0 ? i + 1 : i - 1);
        x0 = null;
    });
    document.addEventListener('keydown', e => {
        if (box.classList.contains('hidden')) return;
        if (e.key === 'Escape') closeBox();
        if (e.key === 'ArrowLeft') show(i - 1);
        if (e.key === 'ArrowRight') show(i + 1);
    });
})();
</script>
@endif
@endsection