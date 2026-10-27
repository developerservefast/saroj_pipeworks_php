@extends('layouts.app')
@section('title', config('business.name') . ' | Plumbing & Bathroom Fittings')
@section('meta_description', config('business.name') . ': CPVC fittings, drainage fittings and sanitary ware for your home, shop or office.')

@section('content')
    @php $tel = preg_replace('/[^0-9+]/', '', (string) config('business.phone')); @endphp

    @php
    $slides = [
        ['Quality Pipes, Fittings & Bathroom Solutions', 'CPVC, drainage fittings and sanitary ware for your home, shop or office.', 'from-brand-dark via-sky-800 to-brand', 'Explore Products', '/products'],
        ['Expert Plumbing Services', 'Installation, repair and maintenance by trained professionals, booked in minutes.', 'from-sky-900 via-brand-dark to-sky-600', 'Book a Service', '/services'],
        ['Not Sure What You Need?', 'Describe the problem and our team will call you back with the right solution.', 'from-brand-dark via-brand to-sky-400', 'Contact Us', '/contact'],
    ];
@endphp
<section id="hero" class="relative overflow-hidden bg-brand-dark text-white">
    <div class="relative h-[calc(100svh-4.5rem)] min-h-[480px] md:h-[calc(100svh-6rem)] md:min-h-[520px]">
        @foreach ($slides as [$h, $p, $g, $btn, $href])
            <div data-slide class="absolute inset-0 bg-gradient-to-br {{ $g }} transition-opacity duration-700 {{ $loop->first ? 'opacity-100' : 'opacity-0 pointer-events-none' }}">
                @php $img = 'images/hero/slide-' . $loop->iteration . '.webp'; @endphp
                @if (file_exists(public_path($img)))
                    <img src="{{ asset($img) }}" alt="" class="absolute inset-0 h-full w-full object-cover" @if (! $loop->first) loading="lazy" @endif>
                    <div class="absolute inset-0 bg-brand-dark/70 sm:bg-transparent sm:bg-gradient-to-r sm:from-brand-dark/90 sm:via-brand-dark/60 sm:to-brand-dark/20"></div>
                @endif
                
                
                <div class="relative mx-auto flex h-full max-w-6xl items-center px-5 sm:px-8">
                    <div class="max-w-2xl pb-8">
                        <span class="inline-block rounded-full bg-white/15 px-3 py-1 text-xs font-medium tracking-wide">{{ config('business.name') }}</span>
                        <h1 class="mt-4 text-3xl font-bold leading-tight sm:text-5xl">{{ $h }}</h1>
                        <p class="mt-4 text-sm text-sky-100 sm:text-lg">{{ $p }}</p>
                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            <a href="{{ url($href) }}" class="rounded-lg bg-white px-6 py-3 text-center font-semibold text-brand-dark transition hover:bg-sky-50">{{ $btn }}</a>
                            <a href="tel:{{ $tel }}" class="rounded-lg border border-white/60 px-6 py-3 text-center font-semibold text-white transition hover:bg-white/10">Call {{ config('business.phone') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <button id="heroPrev" type="button" aria-label="Previous slide" class="absolute left-2 top-1/2 hidden h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-white/15 text-xl hover:bg-white/30 sm:flex sm:left-4">&lsaquo;</button>
    <button id="heroNext" type="button" aria-label="Next slide" class="absolute right-2 top-1/2 hidden h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-white/15 text-xl hover:bg-white/30 sm:flex sm:right-4">&rsaquo;</button>

    <div class="absolute bottom-5 left-0 right-0 flex justify-center gap-2">
        @foreach ($slides as $i)
            <button data-dot type="button" aria-label="Slide {{ $loop->iteration }}" class="relative h-2.5 w-2.5 rounded-full transition before:absolute before:-inset-3 before:content-[''] {{ $loop->first ? 'bg-white' : 'bg-white/40' }}"></button>
        @endforeach
    </div>

    <script>
    (function () {
        const r = document.getElementById('hero');
        const s = [...r.querySelectorAll('[data-slide]')], d = [...r.querySelectorAll('[data-dot]')];
        let i = 0, t, x0 = null;
        function go(n) {
            i = (n + s.length) % s.length;
            s.forEach((el, k) => {
                el.classList.toggle('opacity-100', k === i);
                el.classList.toggle('opacity-0', k !== i);
                el.classList.toggle('pointer-events-none', k !== i);
            });
            d.forEach((el, k) => {
                el.classList.toggle('bg-white', k === i);
                el.classList.toggle('bg-white/40', k !== i);
            });
        }
        function play() { clearInterval(t); t = setInterval(() => go(i + 1), 6000); }
        document.getElementById('heroPrev').onclick = () => { go(i - 1); play(); };
        document.getElementById('heroNext').onclick = () => { go(i + 1); play(); };
        d.forEach((el, k) => el.onclick = () => { go(k); play(); });
        r.addEventListener('touchstart', e => { x0 = e.touches[0].clientX; }, { passive: true });
        r.addEventListener('touchend', e => {
            if (x0 === null) return;
            const dx = e.changedTouches[0].clientX - x0;
            if (Math.abs(dx) > 50) { go(dx < 0 ? i + 1 : i - 1); play(); }
            x0 = null;
        });
        play();
    })();
    </script>
</section>
    <section id="trust-strip" class="relative z-10 -mt-8 px-4 sm:-mt-12">
        <div class="mx-auto grid max-w-6xl grid-cols-2 gap-px overflow-hidden rounded-2xl bg-sky-100 shadow-xl ring-1 ring-sky-100 lg:grid-cols-4">
            @foreach (['Genuine Products', 'Free Call-back', 'Expert Plumbers', 'Home & Office Service'] as $t)
                <div class="flex min-w-0 items-center gap-3 bg-white p-4 sm:p-5">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand text-white">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                    </span>
                    <span class="text-xs font-semibold text-brand-dark sm:text-sm">{{ $t }}</span>
                </div>
            @endforeach
        </div>
    </section>

    <section id="about-strip" class="bg-white">
        <div class="mx-auto grid max-w-6xl items-center gap-8 px-4 py-12 sm:py-16 lg:grid-cols-2 lg:gap-12">
            <div class="min-w-0">
                <span class="text-xs font-semibold uppercase tracking-widest text-brand">About us</span>
                <h2 class="mt-2 text-2xl font-bold text-brand-dark sm:text-4xl">Your trusted partner for plumbing and pipe solutions</h2>
                <p class="mt-4 text-sm text-gray-600 sm:text-base">{{ config('business.name') }} supplies quality pipes, fittings and sanitary ware, and provides reliable plumbing services for homes, shops, offices and hotels.</p>
                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ url('/about') }}" class="btn-primary !py-3 sm:!px-8">Know more</a>
                    <a href="{{ url('/contact') }}" class="btn-outline !py-3 sm:!px-8">Contact us</a>
                </div>
            </div>
            <div class="aspect-[4/3] overflow-hidden rounded-2xl bg-gradient-to-br from-brand-dark to-brand shadow-lg">
                @if (file_exists(public_path('images/about.webp')))
                    <img src="{{ asset('images/about.webp') }}" alt="{{ config('business.name') }}" loading="lazy" class="h-full w-full object-cover">
                @endif
            </div>
        </div>
    </section>

    @if ($services->isNotEmpty())
    <section id="services-highlight" class="bg-sky-50">
        <div class="mx-auto max-w-6xl px-4 py-12 sm:py-16">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-xs font-semibold uppercase tracking-widest text-brand">What we do</span>
                <h2 class="mt-2 text-2xl font-bold text-brand-dark sm:text-4xl">Our Plumbing Services</h2>
                <p class="mt-3 text-sm text-gray-600 sm:text-base">Reliable, professional work for your home, shop or office. Pick a service and we will call you back.</p>
            </div>

            <div class="mt-8 grid grid-cols-2 gap-3 sm:mt-10 sm:gap-6 lg:grid-cols-3">
                @foreach ($services as $service)
                    <div class="group flex min-w-0 flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-sky-100 transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                        <div class="relative aspect-[16/10] overflow-hidden bg-gradient-to-br from-brand-dark to-brand">
                            @if ($service->image)
                                <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            @else
                                <div class="flex h-full w-full items-center justify-center text-white/80">
                                    <svg class="h-9 w-9 sm:h-14 sm:w-14" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26"/></svg>
                                </div>
                            @endif
                            <span class="absolute left-2 top-2 rounded-full bg-white/90 px-2 py-0.5 text-[10px] font-semibold text-brand-dark sm:left-3 sm:top-3 sm:px-2.5 sm:py-1 sm:text-xs">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="flex flex-1 flex-col p-3 sm:p-5">
                            <h3 class="break-words text-sm font-bold text-brand-dark sm:text-lg">{{ $service->name }}</h3>
                            @if ($service->description)
                                <p class="mt-1 line-clamp-2 text-xs text-gray-600 sm:mt-2 sm:line-clamp-3 sm:text-sm">{{ \Illuminate\Support\Str::limit(strip_tags($service->description), 140) }}</p>
                            @endif
                            <div class="mt-auto pt-2 sm:pt-3">
                                <p class="text-[13px] font-bold text-brand sm:text-base sm:text-brand-dark">{{ $service->priceLabel() }}</p>
                                <button type="button" data-book data-id="{{ $service->id }}" class="mt-2 inline-flex min-h-10 w-full sm:mt-3 sm:min-h-11 items-center justify-center rounded-lg bg-brand px-2 py-2 text-xs font-semibold text-white transition hover:bg-brand-dark sm:px-4 sm:text-sm">Book this service</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 text-center sm:mt-10">
                <a href="{{ route('services.index') }}" class="btn-primary block w-full !py-3 sm:inline-flex sm:w-auto sm:!px-8">View all services</a>
            </div>
        </div>
    </section>
    @endif

    @if ($categories->isNotEmpty())
    <section id="categories-highlight" class="mx-auto max-w-6xl px-4 py-12 sm:py-16">
        <div class="mx-auto max-w-2xl text-center">
            <span class="text-xs font-semibold uppercase tracking-widest text-brand">Browse</span>
            <h2 class="mt-2 text-2xl font-bold text-brand-dark sm:text-4xl">Shop by Category</h2>
        </div>
        <div class="mt-8 grid grid-cols-2 gap-3 sm:mt-10 sm:grid-cols-3 sm:gap-5 lg:grid-cols-4">
            @foreach ($categories as $category)
                <a href="{{ url('/products?category=' . $category->id) }}" class="group flex min-w-0 flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-sky-100 transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex h-28 items-center justify-center bg-gradient-to-b from-sky-50 to-white p-3 sm:h-40">
                        @if ($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" loading="lazy" class="max-h-full max-w-full object-contain transition duration-500 group-hover:scale-110">
                        @else
                            <span class="flex h-14 w-14 items-center justify-center rounded-full bg-brand text-xl font-bold text-white">{{ strtoupper(mb_substr($category->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <h3 class="break-words border-t border-sky-50 p-3 text-center text-sm font-semibold text-brand-dark sm:p-4 sm:text-base">{{ $category->name }}</h3>
                </a>
            @endforeach
        </div>
    </section>
    @endif

    @foreach ($rows as $title => $items)
        @if ($items->isNotEmpty())
        <section class="{{ $loop->odd ? 'bg-sky-50' : 'bg-white' }}">
            <div class="mx-auto max-w-6xl px-4 py-12 sm:py-16">
                <div class="flex items-end justify-between gap-3">
                    <div class="min-w-0">
                        <span class="text-xs font-semibold uppercase tracking-widest text-brand">Our picks</span>
                        <h2 class="mt-1 text-2xl font-bold text-brand-dark sm:text-3xl">{{ $title }}</h2>
                    </div>
                    <a href="{{ url('/products') }}" class="inline-flex min-h-11 shrink-0 items-center text-sm font-semibold text-brand hover:text-brand-dark">View all &rarr;</a>
                </div>
                <div class="mt-6 grid grid-cols-2 gap-3 sm:gap-6 lg:grid-cols-4">
                    @foreach ($items as $product)
                        @include('products._card', ['product' => $product, 'only' => ['Best Sellers' => 'is_bestseller', 'Trending' => 'is_trending', 'Latest Products' => 'is_latest'][$title] ?? null])
                    @endforeach
                </div>
            </div>
        </section>
        @endif
    @endforeach

    <section id="how-it-works" class="bg-white">
        <div class="mx-auto max-w-6xl px-4 py-12 sm:py-16">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-xs font-semibold uppercase tracking-widest text-brand">Simple process</span>
                <h2 class="mt-2 text-2xl font-bold text-brand-dark sm:text-4xl">How It Works</h2>
            </div>
            <div class="mt-8 grid grid-cols-1 gap-6 sm:mt-10 md:grid-cols-3">
                @foreach ([['Choose', 'Browse our products or pick a plumbing service.'], ['Send a request', 'Fill in a short form. No payment, no sign-up.'], ['We call you back', 'Our team calls you, discusses your need and the price.']] as [$t, $d])
                    <div class="relative min-w-0 rounded-2xl bg-sky-50 p-6 text-center ring-1 ring-sky-100">
                        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-brand-dark text-lg font-bold text-white">{{ $loop->iteration }}</span>
                        <h3 class="mt-4 text-lg font-bold text-brand-dark">{{ $t }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ $d }}</p>
                    </div>
                @endforeach
            </div>
            <div class="mt-8 text-center">
                <a href="{{ url('/products') }}" class="btn-primary block w-full !py-3 sm:inline-flex sm:w-auto sm:!px-8">Explore Products</a>
            </div>
        </div>
    </section>

    <section id="why-choose" class="bg-white">
        <div class="mx-auto max-w-6xl px-4 py-12 sm:py-16">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-xs font-semibold uppercase tracking-widest text-brand">Why us</span>
                <h2 class="mt-2 text-2xl font-bold text-brand-dark sm:text-4xl">Why Choose {{ config('business.name') }}</h2>
            </div>

            @if (config('home.stats'))
                <div class="mt-8 grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-5">
                    @foreach (config('home.stats') as $s)
                        <div class="rounded-2xl bg-brand-dark p-4 text-center text-white">
                            <p class="text-2xl font-bold sm:text-3xl">{{ $s['value'] }}</p>
                            <p class="mt-1 text-xs text-sky-100 sm:text-sm">{{ $s['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-8 grid grid-cols-1 gap-4 sm:mt-10 sm:grid-cols-2 sm:gap-6 lg:grid-cols-4">
                @php $icons = [
    'M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z',
    'M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085',
    'M15 8.25H9m6 3H9m3 6-3-3h1.5a3 3 0 1 0 0-6M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
    'm3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z',
]; @endphp
                @foreach (config('home.reasons') as [$t, $d])
                    <div class="min-w-0 rounded-2xl bg-sky-50 p-5 ring-1 ring-sky-100">
                        <span class="flex h-11 w-11 items-center justify-center rounded-full bg-brand text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$loop->index] ?? $icons[0] }}"/></svg>
                        </span>
                        <h3 class="mt-4 text-lg font-bold text-brand-dark">{{ $t }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ $d }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    

    @if (config('home.testimonials'))
    <section id="testimonials" class="bg-sky-50">
        <div class="mx-auto max-w-6xl px-4 py-12 sm:py-16">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-xs font-semibold uppercase tracking-widest text-brand">Testimonials</span>
                <h2 class="mt-2 text-2xl font-bold text-brand-dark sm:text-4xl">What Our Customers Say</h2>
            </div>
            <div id="testiStrip" class="-mx-4 mt-8 flex snap-x snap-mandatory gap-4 overflow-x-auto px-4 pb-4 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden sm:mt-10">
                @foreach (config('home.testimonials') as $t)
                    <figure class="flex w-[85%] shrink-0 snap-center flex-col rounded-2xl bg-white p-5 shadow-sm ring-1 ring-sky-100 sm:w-[48%] lg:w-[32%]">
                        <p class="text-lg tracking-widest text-amber-400" aria-label="5 stars">&#9733;&#9733;&#9733;&#9733;&#9733;</p>
                        <blockquote class="mt-3 flex-1 break-words text-sm text-gray-700">&ldquo;{{ $t['text'] }}&rdquo;</blockquote>
                        <figcaption class="mt-4 flex items-center gap-3 border-t border-gray-100 pt-4">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand font-bold text-white">{{ strtoupper(mb_substr($t['name'], 0, 1)) }}</span>
                            <span class="min-w-0">
                                <span class="block truncate text-sm font-semibold text-brand-dark">{{ $t['name'] }}</span>
                                <span class="block truncate text-xs text-gray-500">{{ $t['area'] }}</span>
                            </span>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
        <div class="mx-auto -mt-2 flex max-w-6xl justify-center gap-3 px-4 pb-10">
        <button id="testiPrev" type="button" aria-label="Previous review" class="flex h-11 w-11 items-center justify-center rounded-full bg-white text-xl text-brand-dark shadow ring-1 ring-sky-100 hover:bg-sky-100">&lsaquo;</button>
        <button id="testiNext" type="button" aria-label="Next review" class="flex h-11 w-11 items-center justify-center rounded-full bg-white text-xl text-brand-dark shadow ring-1 ring-sky-100 hover:bg-sky-100">&rsaquo;</button>
    </div>
    <script>
    (function () {
        const s = document.getElementById('testiStrip');
        if (!s) return;
        let paused = false, rt;
        const w = () => (s.firstElementChild ? s.firstElementChild.getBoundingClientRect().width + 16 : 300);
        const atEnd = () => s.scrollLeft + s.clientWidth >= s.scrollWidth - 4;
        const next = () => atEnd() ? s.scrollTo({ left: 0, behavior: 'smooth' }) : s.scrollBy({ left: w(), behavior: 'smooth' });
        const prev = () => s.scrollLeft <= 4 ? s.scrollTo({ left: s.scrollWidth, behavior: 'smooth' }) : s.scrollBy({ left: -w(), behavior: 'smooth' });
        const hold = () => { paused = true; clearTimeout(rt); rt = setTimeout(() => { paused = false; }, 6000); };
        ['touchstart', 'wheel', 'pointerdown', 'mouseenter'].forEach(ev => s.addEventListener(ev, hold, { passive: true }));
        s.addEventListener('mouseleave', () => { clearTimeout(rt); paused = false; });
        document.getElementById('testiNext').onclick = () => { hold(); next(); };
        document.getElementById('testiPrev').onclick = () => { hold(); prev(); };
        if (!matchMedia('(prefers-reduced-motion: reduce)').matches) setInterval(() => { if (!paused) next(); }, 3500);
    })();
    </script>
    </section>
    @endif
@include('partials.booking-modal')
@endsection