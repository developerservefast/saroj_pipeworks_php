@extends('layouts.app')
@section('header_eyebrow', 'Get in touch')
@section('header_title', 'Contact Us')
@section('header_sub', 'Call us or visit our shop. We are happy to help.')
@section('title', 'Contact | ' . config('business.name'))
@section('meta_description', 'Contact ' . config('business.name') . ' for plumbing products and services.')

@section('content')
@php
    $tel = preg_replace('/[^0-9+]/', '', (string) config('business.phone'));
    $addr = (string) config('business.address');
    $hasAddr = $addr !== '' && ! str_contains(strtolower($addr), 'to be added');
    $map = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($addr);
@endphp

<section class="bg-white">
    <div class="mx-auto max-w-6xl px-4 py-10 sm:py-16">
        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
            <a href="tel:{{ $tel }}" class="group flex min-w-0 items-start gap-4 rounded-2xl bg-sky-50 p-5 ring-1 ring-sky-100 transition hover:shadow-lg">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-brand text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                </span>
                <span class="min-w-0">
                    <span class="block text-sm text-gray-500">Call us</span>
                    <span class="block break-words text-lg font-bold text-brand-dark">{{ config('business.phone') }}</span>
                    <span class="mt-1 block text-sm font-semibold text-brand">Tap to call &rarr;</span>
                </span>
            </a>

            <a href="mailto:{{ config('business.email') }}" class="group flex min-w-0 items-start gap-4 rounded-2xl bg-sky-50 p-5 ring-1 ring-sky-100 transition hover:shadow-lg">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-brand text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                </span>
                <span class="min-w-0">
                    <span class="block text-sm text-gray-500">Email</span>
                    <span class="block break-all text-lg font-bold text-brand-dark">{{ config('business.email') }}</span>
                    <span class="mt-1 block text-sm font-semibold text-brand">Send an email &rarr;</span>
                </span>
            </a>

            <div class="flex min-w-0 items-start gap-4 rounded-2xl bg-sky-50 p-5 ring-1 ring-sky-100">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-brand text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                </span>
                <span class="min-w-0">
                    <span class="block text-sm text-gray-500">Address</span>
                    <span class="block break-words font-semibold text-brand-dark">{{ $addr }}</span>
                    @if ($hasAddr)
                        <a href="{{ $map }}" target="_blank" rel="noopener" class="mt-2 inline-flex min-h-11 items-center text-sm font-semibold text-brand hover:text-brand-dark">Get directions &rarr;</a>
                    @endif
                </span>
            </div>

            <div class="flex min-w-0 items-start gap-4 rounded-2xl bg-sky-50 p-5 ring-1 ring-sky-100">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-brand text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </span>
                <span class="min-w-0">
                    <span class="block text-sm text-gray-500">Working hours</span>
                    <span class="block break-words font-semibold text-brand-dark">{{ config('business.hours') }}</span>
                </span>
            </div>
        </div>

        <div class="mt-8 rounded-2xl bg-gradient-to-br from-brand-dark to-brand p-6 text-center text-white sm:mt-10 sm:p-10">
            <h2 class="text-xl font-bold sm:text-3xl">Need a plumber or a product?</h2>
            <p class="mx-auto mt-2 max-w-xl text-sm text-sky-100 sm:text-base">Send a service request and we will call you back. No payment, no sign-up.</p>
            <a href="{{ url('/services') }}" class="mt-5 inline-flex min-h-12 w-full items-center justify-center rounded-lg bg-white px-6 py-3 font-semibold text-brand-dark transition hover:bg-sky-50 sm:w-auto">Book a service</a>
        </div>
    </div>
</section>
@endsection
