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
    @yield('content')
</main>

<footer class="bg-brand-dark text-sky-100 mt-12">
    <div class="max-w-6xl mx-auto px-4 py-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4 text-sm">
        <div>
            <p class="text-lg font-bold text-white">{{ config('business.name') }}</p>
            <p class="mt-2">{{ config('business.tagline') }}</p>
        </div>
        <div>
            <p class="font-semibold text-white mb-2">Quick links</p>
            @foreach ($links as [$label, $href, $pattern])
                <a href="{{ url($href) }}" class="block py-1 hover:underline">{{ $label }}</a>
            @endforeach
        </div>
        <div>
            <p class="font-semibold text-white mb-2">Contact</p>
            <p>Phone: <a href="tel:{{ $tel }}" class="hover:underline">{{ config('business.phone') }}</a></p>
            <p class="break-words">Email: {{ config('business.email') }}</p>
            <p>{{ config('business.address') }}</p>
        </div>
        <div>
            <p class="font-semibold text-white mb-2">Hours</p>
            <p>{{ config('business.hours') }}</p>
        </div>
    </div>
    <div class="border-t border-brand text-center text-xs py-3">
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
</body>
</html>
