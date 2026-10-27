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

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($services as $service)
            <div class="services-card group flex min-w-0 flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-sky-100 transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                <div class="relative aspect-[16/10] overflow-hidden bg-gradient-to-br from-brand-dark to-brand">
                    @if ($service->image)
                        <img src="{{ asset('storage/'.$service->image) }}" alt="{{ $service->name }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                    @else
                        <div class="flex h-full w-full items-center justify-center text-white/80">
                            <svg class="h-14 w-14" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26"/></svg>
                        </div>
                    @endif
                    <span class="absolute left-3 top-3 rounded-full bg-white/90 px-2.5 py-1 text-xs font-semibold text-brand-dark">{{ str_pad($services->firstItem() + $loop->index, 2, '0', STR_PAD_LEFT) }}</span>
                </div>

                <div class="flex flex-1 flex-col p-5">
                    <h2 class="break-words text-lg font-bold text-brand-dark">{{ $service->name }}</h2>
                    @if ($service->description)
                        <p class="mt-2 flex-1 text-sm text-gray-600">{{ \Illuminate\Support\Str::limit(strip_tags($service->description), 120) }}</p>
                    @endif

                    <button type="button" data-book data-id="{{ $service->id }}"
                            class="mt-4 inline-flex min-h-11 w-full items-center justify-center rounded-lg bg-brand px-4 py-2 text-sm font-semibold text-white transition hover:bg-brand-dark">
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

{{-- Booking modal --}}
<dialog id="booking-modal" class="m-auto w-[calc(100%-2rem)] max-w-md max-h-[90vh] overflow-y-auto rounded-2xl p-0 shadow-2xl backdrop:bg-black/50">
    <form id="booking-form" novalidate method="POST" action="{{ route('services.request') }}" class="p-6 space-y-4">
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
                    @if ($allServices->count() > 6)
                        <div class="p-2 border-b border-gray-100">
                            <input type="text" id="picker-search" placeholder="Search services"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
                        </div>
                    @endif
                    <div class="max-h-48 overflow-y-auto p-2 space-y-1">
                        @foreach ($allServices as $service)
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
            <label for="bk-name" class="mb-1 block text-sm font-medium text-gray-700">Your name *</label>
            <input type="text" id="bk-name" name="name" value="{{ old('name') }}" required minlength="2" maxlength="60" autocomplete="name" autocapitalize="words" placeholder="e.g. Ramesh Kumar" @if ($errors->has('name')) aria-invalid="true" @endif
                   class="w-full rounded-lg border border-gray-300 px-3 py-3 text-base focus:outline-none focus:ring-2 focus:ring-brand aria-[invalid=true]:border-red-500">
            <p data-err="name" class="mt-1 text-sm text-red-600 {{ $errors->has('name') ? '' : 'hidden' }}">{{ $errors->first('name') }}</p>
        </div>

        <div>
            <div class="mb-1 flex items-center justify-between">
                <label for="bk-phone" class="block text-sm font-medium text-gray-700">Mobile number *</label>
                <span id="bk-phone-count" class="text-xs text-gray-400">0/10</span>
            </div>
            <input type="tel" id="bk-phone" name="phone" value="{{ old('phone') }}" required inputmode="numeric" autocomplete="tel-national" placeholder="98765 43210" @if ($errors->has('phone')) aria-invalid="true" @endif
                   class="w-full rounded-lg border border-gray-300 px-3 py-3 text-base focus:outline-none focus:ring-2 focus:ring-brand aria-[invalid=true]:border-red-500">
            <p data-err="phone" class="mt-1 text-sm text-red-600 {{ $errors->has('phone') ? '' : 'hidden' }}">{{ $errors->first('phone') }}</p>
        </div>

        <div>
            <label for="bk-email" class="mb-1 block text-sm font-medium text-gray-700">Email (optional)</label>
            <input type="email" id="bk-email" name="email" value="{{ old('email') }}" maxlength="255" autocomplete="email" inputmode="email" placeholder="name@example.com" @if ($errors->has('email')) aria-invalid="true" @endif
                   class="w-full rounded-lg border border-gray-300 px-3 py-3 text-base focus:outline-none focus:ring-2 focus:ring-brand aria-[invalid=true]:border-red-500">
            <p data-err="email" class="mt-1 text-sm text-red-600 {{ $errors->has('email') ? '' : 'hidden' }}">{{ $errors->first('email') }}</p>
        </div>

        <div>
            <label for="bk-address" class="mb-1 block text-sm font-medium text-gray-700">Address *</label>
            <textarea id="bk-address" name="address" rows="3" required minlength="10" maxlength="500" autocomplete="street-address" placeholder="House no., street, area, city" @if ($errors->has('address')) aria-invalid="true" @endif
                      class="w-full rounded-lg border border-gray-300 px-3 py-3 text-base focus:outline-none focus:ring-2 focus:ring-brand aria-[invalid=true]:border-red-500">{{ old('address') }}</textarea>
            <p data-err="address" class="mt-1 text-sm text-red-600 {{ $errors->has('address') ? '' : 'hidden' }}">{{ $errors->first('address') }}</p>
        </div>

        <button type="submit" id="bk-submit" class="min-h-12 w-full rounded-lg bg-brand px-4 py-3 font-semibold text-white hover:bg-brand-dark disabled:cursor-not-allowed disabled:opacity-60">Send Request</button>
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

        var fields = {
            name: document.getElementById('bk-name'),
            phone: document.getElementById('bk-phone'),
            email: document.getElementById('bk-email'),
            address: document.getElementById('bk-address')
        };
        var submitBtn = document.getElementById('bk-submit');
        var phoneCount = document.getElementById('bk-phone-count');

        function normPhone(v) {
            var d = String(v).replace(/\D/g, '');
            if (d.length === 12 && d.indexOf('91') === 0) { d = d.slice(2); }
            else if (d.length === 11 && d.indexOf('0') === 0) { d = d.slice(1); }
            return d;
        }
        function updateCount() { phoneCount.textContent = Math.min(normPhone(fields.phone.value).length, 10) + '/10'; }

        var rules = {
            name: function (v) {
                v = v.trim();
                if (v === '') return 'Please enter your name.';
                if (v.length < 2) return 'Name must be at least 2 characters.';
                if (!/^[\p{L}][\p{L}\p{M}\s.'\-]*$/u.test(v)) return 'Use letters only. Numbers and symbols are not allowed.';
                return '';
            },
            phone: function (v) {
                var d = normPhone(v);
                if (d === '') return 'Please enter your mobile number.';
                if (!/^[6-9]\d{9}$/.test(d)) return 'Enter a valid 10-digit mobile number starting with 6, 7, 8 or 9.';
                return '';
            },
            email: function (v) {
                v = v.trim();
                if (v === '') return '';
                return /^[^\s\x40]+\x40[^\s\x40]+\.[^\s\x40]{2,}$/.test(v) ? '' : 'Please enter a valid email address.';
            },
            address: function (v) {
                v = v.trim();
                if (v === '') return 'Please enter your address.';
                if (v.length < 10) return 'Please enter your full address (at least 10 characters).';
                return '';
            }
        };

        function show(key, msg) {
            var el = fields[key], p = form.querySelector('[data-err="' + key + '"]');
            if (!p) return;
            p.textContent = msg;
            p.classList.toggle('hidden', msg === '');
            if (msg) { el.setAttribute('aria-invalid', 'true'); } else { el.removeAttribute('aria-invalid'); }
        }
        function check(key) { var m = rules[key](fields[key].value); show(key, m); return m === ''; }

        Object.keys(fields).forEach(function (k) {
            fields[k].addEventListener('input', function () {
                if (k === 'phone') {
                    fields.phone.value = fields.phone.value.replace(/\D/g, '').slice(0, 12);
                    updateCount();
                }
                if (fields[k].hasAttribute('aria-invalid')) { check(k); }
            });
            fields[k].addEventListener('blur', function () {
                if (k === 'phone') { fields.phone.value = normPhone(fields.phone.value); updateCount(); }
                check(k);
            });
        });

        form.addEventListener('submit', function (e) {
            var first = null;
            if (!boxes.some(function (b) { return b.checked; })) {
                error.textContent = 'Please select at least one service.';
                error.classList.remove('hidden');
                first = button;
            }
            fields.phone.value = normPhone(fields.phone.value);
            ['name', 'phone', 'email', 'address'].forEach(function (k) {
                if (!check(k) && !first) { first = fields[k]; }
            });
            if (first) {
                e.preventDefault();
                first.focus();
                if (first.scrollIntoView) { first.scrollIntoView({ block: 'center', behavior: 'smooth' }); }
                return;
            }
            submitBtn.disabled = true;
            submitBtn.textContent = 'Sending...';
        });
        /* only-letters-guard */
        fields.name.addEventListener('input', function () {
            var before = fields.name.value;
            var after = before.replace(/[^\p{L}\p{M}\s.'\-]/gu, '').replace(/^[^\p{L}]+/u, '');
            if (after !== before) {
                fields.name.value = after;
                show('name', 'Only letters are allowed. Numbers and symbols are not accepted.');
            }
        });
        fields.phone.addEventListener('input', function () {
            var d = fields.phone.value.replace(/\D/g, '');
            if (d.length === 12 && d.indexOf('91') === 0) { d = d.slice(2); }
            else if (d.length === 11 && d.indexOf('0') === 0) { d = d.slice(1); }
            d = d.slice(0, 10);
            var bad = false;
            if (d.length > 0 && !/^[6-9]/.test(d)) { d = d.replace(/^[^6-9]+/, ''); bad = true; }
            fields.phone.value = d;
            updateCount();
            if (bad) { show('phone', 'Mobile number must start with 6, 7, 8 or 9.'); }
        });
        window.addEventListener('pageshow', function () {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Send Request';
        });
        updateCount();
        updateLabel();

        @if ($shouldOpen)
            modal.showModal();
        @endif
    })();
</script>
@endsection
