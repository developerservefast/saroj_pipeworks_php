@extends('layouts.app')
@section('header_eyebrow', 'What we do')
@section('header_title', 'Our Services')
@section('header_sub', 'Choose a service and we will call you back.')
@section('title', 'Services | ' . config('business.name'))

@section('content')
@php
    $checked = collect(old('service_ids', $selected ? [$selected] : []))->map(fn ($v) => (int) $v)->all();
    $shouldOpen = $errors->any() || $selected;
@endphp

<div class="max-w-6xl mx-auto px-4 py-10">
    
    

    @if (session('success'))
        <div class="mb-6 rounded-lg bg-green-100 text-green-800 px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-2 gap-3 sm:gap-5 lg:grid-cols-3">
        @forelse ($services as $service)
            <div class="services-card group flex min-w-0 flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-sky-100 transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                <div class="relative aspect-[16/10] overflow-hidden bg-gradient-to-br from-brand-dark to-brand">
                    @if ($service->image)
                        <img src="{{ asset('storage/'.$service->image) }}" alt="{{ $service->name }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                    @else
                        <div class="flex h-full w-full items-center justify-center text-white/80">
                            <svg class="h-9 w-9 sm:h-14 sm:w-14" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26"/></svg>
                        </div>
                    @endif
                    <span class="absolute left-2 top-2 rounded-full bg-white/90 px-2 py-0.5 text-[10px] font-semibold text-brand-dark sm:left-3 sm:top-3 sm:px-2.5 sm:py-1 sm:text-xs">{{ str_pad($services->firstItem() + $loop->index, 2, '0', STR_PAD_LEFT) }}</span>
                </div>

                <div class="flex flex-1 flex-col p-3 sm:p-5">
                    <h2 class="break-words text-sm font-bold text-brand-dark sm:text-lg">{{ $service->name }}</h2>
                    @if ($service->description)
                        <p class="mt-1 line-clamp-2 flex-1 text-xs text-gray-600 sm:mt-2 sm:line-clamp-none sm:text-sm">{{ \Illuminate\Support\Str::limit(strip_tags($service->description), 120) }}</p>
                    @endif

                    <p class="mt-2 text-[13px] font-bold text-brand sm:mt-3 sm:text-base sm:text-brand-dark">{{ $service->priceLabel() }}</p>

                    <button type="button" data-book data-id="{{ $service->id }}"
                            class="mt-2 inline-flex min-h-10 w-full sm:mt-4 sm:min-h-11 items-center justify-center rounded-lg bg-brand px-2 py-2 text-xs font-semibold text-white transition hover:bg-brand-dark sm:px-4 sm:text-sm">
                        Book this service
                    </button>
                </div>
            </div>
        @empty
            <p class="col-span-full text-gray-500">No services available right now. Please call us.</p>
        @endforelse
    </div>

    @if ($services->hasPages())
        <div class="mt-8">{{ $services->links() }}</div>
    @endif
</div>

@include('partials.booking-modal')
@endsection
