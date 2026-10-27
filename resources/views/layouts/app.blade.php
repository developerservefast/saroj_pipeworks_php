<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('business.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @hasSection('meta_description')
        <meta name="description" content="@yield('meta_description')">
    @endif
@php
    $seoTitle = trim($__env->yieldContent('title', config('business.name')));
    $seoDesc = $__env->hasSection('meta_description') ? trim($__env->yieldContent('meta_description')) : config('business.name') . ': ' . config('business.tagline') . '.';
    $seoImg = file_exists(public_path('images/og.jpg')) ? asset('images/og.jpg') : null;
    $seoPhone = (string) config('business.phone');
    $seoAddr = (string) config('business.address');
    $ld = ['@context' => 'https://schema.org', '@type' => 'Plumber', 'name' => config('business.name'), 'description' => config('business.tagline'), 'url' => url('/')];
    if ($seoImg) $ld['image'] = $seoImg;
    if (! str_contains($seoPhone, '00000')) $ld['telephone'] = $seoPhone;
    if (! str_contains((string) config('business.email'), 'example.com')) $ld['email'] = config('business.email');
    if ($seoAddr !== '' && ! str_contains(strtolower($seoAddr), 'to be added')) $ld['address'] = ['@type' => 'PostalAddress', 'streetAddress' => $seoAddr, 'addressCountry' => 'IN'];
@endphp
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="canonical" href="{{ url()->current() }}">
    @unless ($__env->hasSection('meta_description'))
        <meta name="description" content="{{ $seoDesc }}">
    @endunless
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('business.name') }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDesc }}">
    <meta property="og:url" content="{{ url()->current() }}">
    @if ($seoImg)
        <meta property="og:image" content="{{ $seoImg }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:image" content="{{ $seoImg }}">
    @endif
    <script type="application/ld+json">{!! json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
</head>
<body class="min-h-screen flex flex-col bg-white text-gray-800">

@php
    $tel = preg_replace('/[^0-9+]/', '', (string) config('business.phone'));
    $links = [['Home', '/', '/'], ['Services', '/services', 'services*'], ['Products', '/products', 'products*'], ['About', '/about', 'about'], ['Contact', '/contact', 'contact']];
@endphp

<header id="site-header" class="sticky top-0 z-50">
    <div id="header-bar" class="bg-white w-full mx-auto shadow-sm transition-all duration-300">
        <div class="max-w-6xl mx-auto px-4 py-3 md:py-4 flex items-center justify-between gap-3">
            <a href="{{ url('/') }}" class="flex items-center">
                @if (file_exists(public_path('images/logo.png')))
                    <img src="{{ asset('images/logo.png') }}" alt="{{ config('business.name') }}" class="h-12 md:h-16">
                @else
                    <span class="text-xl font-bold text-brand-dark">{{ config('business.name') }}</span>
                @endif
            </a>

            <nav class="hidden md:flex items-center gap-5 text-sm font-medium text-gray-700">
                @foreach ($links as [$label, $href, $pattern])
                    <a href="{{ url($href) }}" class="hover:text-brand {{ request()->is($pattern) ? 'text-brand' : '' }}">{{ $label }}</a>
                @endforeach
                <a href="tel:{{ $tel }}" class="bg-brand hover:bg-brand-dark text-white px-4 py-2 rounded-lg">Call Us</a>
            </nav>

            <div class="flex items-center gap-2 md:hidden">
                <a href="tel:{{ $tel }}" class="bg-brand text-white text-sm font-medium px-3 py-2 rounded-lg">Call</a>
                <button id="navBtn" type="button" aria-label="Menu" aria-expanded="false" class="p-2 rounded-lg text-gray-700 hover:bg-gray-100">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                </button>
            </div>
        </div>

        <nav id="mobileNav" class="hidden md:hidden border-t border-gray-100 px-4 py-2 text-sm font-medium text-gray-700">
            @foreach ($links as [$label, $href, $pattern])
                <a href="{{ url($href) }}" class="block py-3 border-b border-gray-50 last:border-0 {{ request()->is($pattern) ? 'text-brand' : '' }}">{{ $label }}</a>
            @endforeach
        </nav>
    </div>
</header>

<main class="flex-1">
    @hasSection('header_title')
        @include('partials.page-header')
    @endif
    @yield('content')
</main>

<section class="bg-brand">
    <div class="mx-auto flex max-w-6xl flex-col items-center gap-4 px-4 py-8 text-center text-white sm:flex-row sm:justify-between sm:text-left">
        <div class="min-w-0">
            <h2 class="text-xl font-bold sm:text-2xl">Need help choosing the right fitting?</h2>
            <p class="mt-1 text-sm text-sky-50">Call us and we will guide you.</p>
        </div>
        <a href="tel:{{ $tel }}" class="inline-flex min-h-12 w-full items-center justify-center rounded-lg bg-white px-6 py-3 font-semibold text-brand-dark transition hover:bg-sky-50 sm:w-auto">{{ config('business.phone') }}</a>
    </div>
</section>

<footer id="site-footer" class="bg-brand-dark text-sky-100">
    <div class="mx-auto grid max-w-6xl gap-8 px-4 py-10 text-sm sm:grid-cols-2 lg:grid-cols-4">
        <div class="min-w-0">
            @if (file_exists(public_path('images/logo.png')))
                <span class="inline-block rounded-lg bg-white p-2"><img src="{{ asset('images/logo.png') }}" alt="{{ config('business.name') }}" class="h-12"></span>
            @else
                <p class="text-lg font-bold text-white">{{ config('business.name') }}</p>
            @endif
            <p class="mt-3 text-sky-200">{{ config('business.tagline') }}</p>
        </div>
        <div>
            <p class="mb-2 font-semibold text-white">Quick links</p>
            @foreach ($links as [$label, $href, $pattern])
                <a href="{{ url($href) }}" class="flex min-h-11 items-center hover:text-white hover:underline">{{ $label }}</a>
            @endforeach
        </div>
        <div class="min-w-0">
            <p class="mb-2 font-semibold text-white">Contact</p>
            <a href="tel:{{ $tel }}" class="flex min-h-11 items-center hover:text-white">{{ config('business.phone') }}</a>
            <a href="mailto:{{ config('business.email') }}" class="flex min-h-11 items-center break-all hover:text-white">{{ config('business.email') }}</a>
            <p class="py-2">{{ config('business.address') }}</p>
        </div>
        <div>
            <p class="mb-2 font-semibold text-white">Working hours</p>
            <p>{{ config('business.hours') }}</p>
        </div>
    </div>
    <div class="border-t border-white/10 px-4 py-4 pb-20 text-center text-xs text-sky-200 sm:pb-4">
        &copy; {{ date('Y') }} {{ config('business.name') }}. All rights reserved.
    </div>
</footer>

<script>
    const headerBar = document.getElementById('header-bar');
    function updateHeader() {
        const scrolled = window.scrollY > 10;
        headerBar.classList.toggle('w-full', !scrolled);
        headerBar.classList.toggle('shadow-sm', !scrolled);
        headerBar.classList.toggle('w-[calc(100%-2rem)]', scrolled);
        headerBar.classList.toggle('translate-y-3', scrolled);
        headerBar.classList.toggle('rounded-2xl', scrolled);
        headerBar.classList.toggle('border', scrolled);
        headerBar.classList.toggle('border-gray-200', scrolled);
        headerBar.classList.toggle('shadow-lg', scrolled);
    }
    window.addEventListener('scroll', updateHeader, { passive: true });
    updateHeader();

    const navBtn = document.getElementById('navBtn'), mobileNav = document.getElementById('mobileNav');
    navBtn.onclick = () => {
        const open = mobileNav.classList.toggle('hidden') === false;
        navBtn.setAttribute('aria-expanded', open);
    };
</script>
<div id="floatActions" class="fixed right-3 z-40 flex flex-col gap-3" style="bottom: calc(env(safe-area-inset-bottom, 0px) + 0.75rem)">
    @if (config('home.whatsapp'))
        <a href="https://wa.me/{{ preg_replace('/\D/', '', config('home.whatsapp')) }}?text={{ rawurlencode('Hello, I need help with plumbing products/services.') }}" target="_blank" rel="noopener" aria-label="Chat on WhatsApp" class="flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] text-white shadow-lg transition hover:scale-105">
            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor"><path d="M20.5 3.5A11.8 11.8 0 0 0 12 0C5.4 0 .1 5.3.1 11.9c0 2.1.6 4.1 1.6 5.9L0 24l6.4-1.7a11.9 11.9 0 0 0 5.6 1.4c6.6 0 11.9-5.3 11.9-11.9 0-3.2-1.2-6.2-3.4-8.3ZM12 21.7c-1.8 0-3.5-.5-5-1.4l-.4-.2-3.800 1 1-3.700-.2-.4a9.800 9.800 0 0 1-1.500-5.200c0-5.400 4.400-9.800 9.900-9.800 2.600 0 5.100 1 6.900 2.900a9.700 9.700 0 0 1 2.900 6.900c0 5.400-4.400 9.900-9.800 9.900Zm5.400-7.400c-.3-.1-1.700-.8-2-.9-.3-.1-.5-.1-.7.1l-.9 1.100c-.2.200-.3.200-.6.100a8 8 0 0 1-4-3.500c-.3-.5.300-.5.800-1.600.1-.2 0-.4 0-.5l-.9-2.200c-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.5.100-.8.400-.3.300-1 1-1 2.500s1.100 2.900 1.200 3.100c.1.200 2.100 3.200 5.100 4.500 1.900.8 2.600.9 3.600.7.600-.1 1.700-.7 2-1.400.2-.7.200-1.300.2-1.400-.1-.1-.3-.2-.6-.3Z"/></svg>
        </a>
    @endif
    <a href="tel:{{ $tel }}" aria-label="Call us" class="flex h-14 w-14 items-center justify-center rounded-full bg-brand text-white shadow-lg transition hover:scale-105 hover:bg-brand-dark">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
    </a>
</div>

<script>
    (function () {
        const els = document.querySelectorAll('main > section:not(#hero)');
        if (!('IntersectionObserver' in window) || matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        const io = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('is-in'); io.unobserve(e.target); } });
        }, { threshold: 0.08 });
        els.forEach(el => { el.classList.add('js-reveal'); io.observe(el); });
    })();
</script>
</body>
</html>
