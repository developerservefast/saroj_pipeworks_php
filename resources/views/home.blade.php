@extends('layouts.app')
@section('title', config('business.name') . ' | Plumbing & Bathroom Fittings')

@section('content')
    @php $tel = preg_replace('/[^0-9+]/', '', config('business.phone')); @endphp

    {{-- Hero --}}
    <section class="bg-gradient-to-b from-sky-100 to-white">
        <div class="max-w-6xl mx-auto px-4 py-16 sm:py-24 text-center">
            <h1 class="text-3xl sm:text-5xl font-bold text-sky-700">Quality Pipes, Fittings and Bathroom Solutions</h1>
            <p class="mt-4 text-gray-600 max-w-2xl mx-auto">
                CPVC fittings, drainage fittings and sanitary ware for your home, shop or office.
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ url('/products') }}" class="bg-sky-500 hover:bg-sky-600 text-white font-semibold px-6 py-3 rounded-lg">Explore Products</a>
                <a href="tel:{{ $tel }}" class="bg-white border border-sky-500 text-sky-600 hover:bg-sky-50 font-semibold px-6 py-3 rounded-lg">Call {{ config('business.phone') }}</a>
            </div>
        </div>
    </section>

    {{-- Categories --}}
    <section class="max-w-6xl mx-auto px-4 py-12">
        <h2 class="text-2xl font-bold text-sky-700 mb-6">Shop by Category</h2>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($categories as $category)
                <a href="{{ url('/products') }}?category={{ $category->slug }}"
                   class="block rounded-xl border border-sky-100 bg-sky-50 hover:bg-sky-100 hover:shadow p-6 transition">
                    <h3 class="text-lg font-semibold text-sky-800">{{ $category->name }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $category->products_count }} products</p>
                </a>
            @endforeach
        </div>
    </section>
        {{-- Services --}}
    @if ($services->isNotEmpty())
    <section class="bg-sky-50">
        <div class="max-w-6xl mx-auto px-4 py-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-brand-dark">Our Services</h2>
                <a href="{{ route('services.index') }}" class="text-sm text-brand hover:underline">View all &rarr;</a>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($services as $service)
                    <a href="{{ route('services.index', ['service' => $service->id]) }}#book"
                       class="block bg-white rounded-xl border border-sky-100 hover:shadow p-6 transition">
                        <h3 class="text-lg font-semibold text-brand-dark">{{ $service->name }}</h3>
                        @if ($service->description)
                            <p class="text-sm text-gray-600 mt-1">{{ \Illuminate\Support\Str::limit($service->description, 90) }}</p>
                        @endif
                        <p class="text-sm text-brand mt-3">Book this service &rarr;</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Call to action --}}
    <section class="bg-sky-500 text-white">
        <div class="max-w-6xl mx-auto px-4 py-10 text-center">
            <h2 class="text-2xl font-bold">Need help choosing the right fitting?</h2>
            <p class="mt-2 text-sky-50">Call us and we will guide you.</p>
            <a href="tel:{{ $tel }}" class="inline-block mt-5 bg-white text-sky-600 font-semibold px-6 py-3 rounded-lg hover:bg-sky-50">
                {{ config('business.phone') }}
            </a>
        </div>
    </section>
@endsection
