@extends('layouts.app')
@section('header_eyebrow', 'Who we are')
@section('header_title', 'About Us')
@section('header_sub', 'Quality pipes, fittings and plumbing services you can trust.')
@section('title', 'About Us | ' . config('business.name'))
@section('meta_description', 'About ' . config('business.name') . ': quality pipes, fittings, sanitary ware and plumbing services.')

@section('content')
@php $tel = preg_replace('/[^0-9+]/', '', (string) config('business.phone')); @endphp

<section class="bg-white">
    <div class="mx-auto grid max-w-6xl items-center gap-8 px-4 py-10 sm:py-16 lg:grid-cols-2 lg:gap-12">
        <div class="min-w-0">
            <span class="text-xs font-semibold uppercase tracking-widest text-brand">Our story</span>
            <h2 class="mt-2 text-2xl font-bold text-brand-dark sm:text-4xl">Your trusted partner for plumbing and pipe solutions</h2>
            <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">{{ config('business.name') }} supplies quality pipes, fittings and bathroom ware, and provides reliable plumbing services for homes, shops, offices and hotels.</p>
            <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">Not sure which product you need? Describe the problem and our team will call you back with the right solution and a fair price.</p>
            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                <a href="{{ url('/products') }}" class="btn-primary !py-3 sm:!px-8">Explore products</a>
                <a href="{{ url('/services') }}" class="btn-outline !py-3 sm:!px-8">Book a service</a>
            </div>
        </div>
        <div class="aspect-[4/3] overflow-hidden rounded-2xl bg-gradient-to-br from-brand-dark to-brand shadow-lg">
            @if (file_exists(public_path('images/about.webp')))
                <img src="{{ asset('images/about.webp') }}" alt="{{ config('business.name') }}" loading="lazy" class="h-full w-full object-cover">
            @endif
        </div>
    </div>
</section>

<section class="bg-sky-50">
    <div class="mx-auto max-w-6xl px-4 py-10 sm:py-16">
        <div class="mx-auto max-w-2xl text-center">
            <span class="text-xs font-semibold uppercase tracking-widest text-brand">What we offer</span>
            <h2 class="mt-2 text-2xl font-bold text-brand-dark sm:text-4xl">Products and services</h2>
        </div>
        <div class="mt-8 grid gap-4 sm:grid-cols-2 sm:gap-6">
            @foreach ([
                ['Products', 'CPVC and drainage fittings, taps, valves and sanitary ware from trusted brands.', '/products', 'Browse products'],
                ['Plumbing services', 'Installation, repair and maintenance by trained plumbers.', '/services', 'View services'],
            ] as [$t, $d, $u, $b])
                <div class="flex min-w-0 flex-col rounded-2xl bg-white p-6 shadow-sm ring-1 ring-sky-100">
                    <h3 class="text-lg font-bold text-brand-dark">{{ $t }}</h3>
                    <p class="mt-2 flex-1 text-sm text-gray-600">{{ $d }}</p>
                    <a href="{{ url($u) }}" class="mt-4 inline-flex min-h-11 items-center text-sm font-semibold text-brand hover:text-brand-dark">{{ $b }} &rarr;</a>
                </div>
            @endforeach
        </div>
    </div>
</section>

@if (config('home.reasons'))
<section class="bg-white">
    <div class="mx-auto max-w-6xl px-4 py-10 sm:py-16">
        <div class="mx-auto max-w-2xl text-center">
            <span class="text-xs font-semibold uppercase tracking-widest text-brand">Why us</span>
            <h2 class="mt-2 text-2xl font-bold text-brand-dark sm:text-4xl">Why customers choose us</h2>
        </div>
        <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-6 lg:grid-cols-4">
            @foreach (config('home.reasons') as [$t, $d])
                <div class="min-w-0 rounded-2xl bg-sky-50 p-5 ring-1 ring-sky-100">
                    <span class="flex h-11 w-11 items-center justify-center rounded-full bg-brand text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                    </span>
                    <h3 class="mt-4 text-lg font-bold text-brand-dark">{{ $t }}</h3>
                    <p class="mt-1 text-sm text-gray-600">{{ $d }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
