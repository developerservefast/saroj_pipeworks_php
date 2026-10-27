@extends('layouts.app')
@section('title', 'Too many attempts | ' . config('business.name'))

@section('content')
@php
    $wait = (int) (method_exists($exception, 'getHeaders') ? ($exception->getHeaders()['Retry-After'] ?? 0) : 0);
    $mins = $wait > 0 ? (int) ceil($wait / 60) : null;
    $tel = preg_replace('/[^0-9+]/', '', (string) config('business.phone'));
@endphp
<section class="bg-sky-50">
    <div class="mx-auto flex min-h-[60vh] max-w-xl items-center px-4 py-12">
        <div class="w-full rounded-2xl bg-white p-6 text-center shadow-sm ring-1 ring-sky-100 sm:p-10">
            <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            </span>
            <h1 class="mt-4 text-2xl font-bold text-brand-dark sm:text-3xl">Too many attempts</h1>
            <p class="mt-2 text-sm text-gray-600 sm:text-base">
                For your security, we have paused this action for a while.
                @if ($mins)
                    Please try again in about {{ $mins }} {{ $mins === 1 ? 'minute' : 'minutes' }}.
                @else
                    Please wait a little and try again.
                @endif
            </p>
            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-center">
                <a href="{{ url('/') }}" class="btn-primary !py-3 sm:!px-8">Go to home</a>
                <a href="tel:{{ $tel }}" class="btn-outline !py-3 sm:!px-8">Call us</a>
            </div>
            <p class="mt-5 text-xs text-gray-500">Admin? <a href="{{ url('/admin/login') }}" class="font-semibold text-brand hover:underline">Back to login</a> once the wait is over.</p>
        </div>
    </div>
</section>
@endsection
