<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('business.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-white text-gray-800">

@php $tel = preg_replace('/[^0-9+]/', '', config('business.phone')); @endphp

<header id="site-header" class="sticky top-0 z-50">
    <div id="header-bar" class="bg-white w-full mx-auto shadow-sm transition-all duration-300">
          <div class="max-w-6xl mx-auto px-4 py-4 flex flex-wrap items-center justify-between gap-3">
            <a href="{{ url('/') }}" class="flex items-center">
                @if (file_exists(public_path('images/logo.png')))
                      <img src="{{ asset('images/logo.png') }}" alt="{{ config('business.name') }}" class="h-16">
                @else
                    <span class="text-xl font-bold text-brand-dark">{{ config('business.name') }}</span>
                @endif
            </a>

            <nav class="flex flex-wrap items-center gap-5 text-sm font-medium text-gray-700">
                <a href="{{ url('/') }}" class="hover:text-brand {{ request()->is('/') ? 'text-brand' : '' }}">Home</a>
                <a href="{{ url('/services') }}" class="hover:text-brand {{ request()->is('services*') ? 'text-brand' : '' }}">Services</a>
                <a href="{{ url('/products') }}" class="hover:text-brand {{ request()->is('products*') ? 'text-brand' : '' }}">Products</a>
                <a href="tel:{{ $tel }}" class="bg-brand hover:bg-brand-dark text-white px-4 py-2 rounded-lg">Call Us</a>
            </nav>
        </div>
    </div>
</header>

<main class="flex-1">
    @yield('content')
</main>

<footer class="bg-brand-dark text-sky-100 mt-12">
    <div class="max-w-6xl mx-auto px-4 py-8 grid gap-6 sm:grid-cols-3 text-sm">
        <div>
            <p class="text-lg font-bold text-white">{{ config('business.name') }}</p>
            <p class="mt-2">{{ config('business.tagline') }}</p>
        </div>
        <div>
            <p class="font-semibold text-white mb-2">Contact</p>
            <p>Phone: <a href="tel:{{ $tel }}" class="hover:underline">{{ config('business.phone') }}</a></p>
            <p>Email: {{ config('business.email') }}</p>
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
</script>
</body>
</html>
