@extends('layouts.app')
@section('title', 'Services | ' . config('business.name'))

@section('content')
@php
    $checked = collect(old('service_ids', $selected ? [$selected] : []))->map(fn ($v) => (int) $v)->all();
    $shouldOpen = $errors->any() || $selected;
@endphp

<div class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-brand-dark mb-2">Our Services</h1>
    <p class="text-gray-600 mb-6">Choose a service, fill in your details and we will call you back.</p>

    @if (session('success'))
        <div class="mb-6 rounded-lg bg-green-100 text-green-800 px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($services as $service)
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden flex flex-col">
                @if ($service->image)
                    <img src="{{ asset('storage/'.$service->image) }}" alt="{{ $service->name }}" class="h-40 w-full object-contain bg-white p-2">
                @else
                    <div class="h-40 w-full bg-sky-50 flex items-center justify-center text-sm text-gray-400">No image</div>
                @endif

                <div class="p-5 flex flex-col flex-1">
                    <h2 class="font-semibold text-lg text-brand-dark">{{ $service->name }}</h2>
                    @if ($service->description)
                        <p class="text-sm text-gray-600 mt-2 flex-1">{{ \Illuminate\Support\Str::limit($service->description, 120) }}</p>
                    @endif

                    <button type="button" data-book data-id="{{ $service->id }}"
                            class="mt-4 w-full bg-brand hover:bg-brand-dark text-white text-sm font-semibold px-4 py-2 rounded-lg">
                        Book this service
                    </button>
                </div>
            </div>
        @empty
            <p class="col-span-full text-gray-500">No services available right now. Please call us.</p>
        @endforelse
    </div>
</div>

{{-- Booking modal --}}
<dialog id="booking-modal" class="m-auto w-[calc(100%-2rem)] max-w-md max-h-[90vh] overflow-y-auto rounded-2xl p-0 shadow-2xl backdrop:bg-black/50">
    <form id="booking-form" method="POST" action="{{ route('services.request') }}" class="p-6 space-y-4">
        @csrf

        <div class="flex items-start justify-between gap-4">
            <h2 class="text-xl font-bold text-brand-dark">Book a Service</h2>
            <button type="button" data-close class="text-gray-400 hover:text-gray-700 text-2xl leading-none" aria-label="Close">&times;</button>
        </div>

        {{-- Multi-select dropdown --}}
        <div>
            <label class="block text-sm text-gray-600 mb-1">Services you want *</label>
            <div id="service-picker" class="relative">
                <button type="button" id="picker-button"
                        class="w-full flex items-center justify-between gap-2 border border-gray-300 rounded-lg px-3 py-2 text-left focus:outline-none focus:ring-2 focus:ring-brand">
                    <span id="picker-label" class="truncate text-gray-500">Select services</span>
                    <svg class="h-4 w-4 shrink-0 text-gray-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                </button>

                <div id="picker-panel" class="hidden absolute z-20 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg">
                    @if ($services->count() > 6)
                        <div class="p-2 border-b border-gray-100">
                            <input type="text" id="picker-search" placeholder="Search services"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
                        </div>
                    @endif
                    <div class="max-h-48 overflow-y-auto p-2 space-y-1">
                        @foreach ($services as $service)
                            <label data-row data-name="{{ strtolower($service->name) }}"
                                   class="flex items-center gap-2 px-2 py-1.5 rounded hover:bg-sky-50 text-sm text-gray-700 cursor-pointer">
                                <input type="checkbox" name="service_ids[]" value="{{ $service->id }}"
                                       data-label="{{ $service->name }}" class="h-4 w-4 accent-brand"
                                       @checked(in_array($service->id, $checked))>
                                {{ $service->name }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <p id="service-error" class="{{ $errors->has('service_ids') || $errors->has('service_ids.*') ? '' : 'hidden' }} text-sm text-red-600 mt-1">
                {{ $errors->first('service_ids') ?: ($errors->first('service_ids.*') ?: 'Please select at least one service.') }}
            </p>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Your name *</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand">
            @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Phone number *</label>
            <input type="tel" name="phone" value="{{ old('phone') }}" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand">
            @error('phone') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Email (optional)</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand">
            @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Address *</label>
            <textarea name="address" rows="3" required
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand">{{ old('address') }}</textarea>
            @error('address') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <button class="w-full bg-brand hover:bg-brand-dark text-white font-semibold px-4 py-2.5 rounded-lg">Send Request</button>
    </form>
</dialog>

<script>
    (function () {
        var modal = document.getElementById('booking-modal');
        var form = document.getElementById('booking-form');
        var picker = document.getElementById('service-picker');
        var button = document.getElementById('picker-button');
        var panel = document.getElementById('picker-panel');
        var label = document.getElementById('picker-label');
        var search = document.getElementById('picker-search');
        var error = document.getElementById('service-error');
        var boxes = Array.prototype.slice.call(form.querySelectorAll('input[name="service_ids[]"]'));
        var rows = Array.prototype.slice.call(form.querySelectorAll('[data-row]'));

        function updateLabel() {
            var picked = boxes.filter(function (b) { return b.checked; });
            if (picked.length === 0) {
                label.textContent = 'Select services';
                label.className = 'truncate text-gray-500';
            } else if (picked.length === 1) {
                label.textContent = picked[0].dataset.label;
                label.className = 'truncate text-gray-800';
            } else {
                label.textContent = picked.length + ' services selected';
                label.className = 'truncate text-gray-800';
            }
        }

        function closePanel() { panel.classList.add('hidden'); }

        button.addEventListener('click', function () {
            panel.classList.toggle('hidden');
            if (!panel.classList.contains('hidden') && search) { search.focus(); }
        });

        boxes.forEach(function (b) {
            b.addEventListener('change', function () {
                updateLabel();
                if (boxes.some(function (x) { return x.checked; })) { error.classList.add('hidden'); }
            });
        });

        if (search) {
            search.addEventListener('input', function () {
                var q = search.value.toLowerCase();
                rows.forEach(function (r) {
                    r.classList.toggle('hidden', r.dataset.name.indexOf(q) === -1);
                });
            });
        }

        document.addEventListener('click', function (e) {
            if (!picker.contains(e.target)) { closePanel(); }
        });

        document.querySelectorAll('[data-book]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                boxes.forEach(function (b) { b.checked = (b.value === btn.dataset.id); });
                updateLabel();
                closePanel();
                error.classList.add('hidden');
                modal.showModal();
            });
        });

        document.querySelectorAll('[data-close]').forEach(function (btn) {
            btn.addEventListener('click', function () { modal.close(); });
        });

        modal.addEventListener('click', function (e) {
            if (e.target === modal) { modal.close(); }
        });

        modal.addEventListener('close', closePanel);

        form.addEventListener('submit', function (e) {
            if (!boxes.some(function (b) { return b.checked; })) {
                e.preventDefault();
                error.textContent = 'Please select at least one service.';
                error.classList.remove('hidden');
            }
        });

        updateLabel();

        @if ($shouldOpen)
            modal.showModal();
        @endif
    })();
</script>
@endsection
