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

        <div id="contact-form" class="mt-8 scroll-mt-28 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-sky-100 sm:mt-10 sm:p-8">
            <h2 class="text-xl font-bold text-brand-dark sm:text-2xl">Send us a message</h2>
            <p class="mt-1 text-sm text-gray-600">Fill in the form and we will call you back.</p>

            @if (session('success'))
                <div class="mt-4 rounded-lg bg-green-100 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
            @endif

            <form novalidate method="POST" action="{{ route('contact.send') }}" class="mt-5 grid gap-4 sm:grid-cols-2">
                @csrf
                <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">

                <div>
                    <label for="ct-name" class="mb-1 block text-sm font-medium text-gray-700">Your name *</label>
                    <input type="text" id="ct-name" name="name" value="{{ old('name') }}" required minlength="2" maxlength="60" autocomplete="name" class="w-full rounded-lg border border-gray-300 px-3 py-3 text-base focus:outline-none focus:ring-2 focus:ring-brand">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="ct-phone" class="mb-1 block text-sm font-medium text-gray-700">Mobile number *</label>
                    <input type="tel" id="ct-phone" name="phone" value="{{ old('phone') }}" required inputmode="numeric" autocomplete="tel-national" placeholder="98765 43210" class="w-full rounded-lg border border-gray-300 px-3 py-3 text-base focus:outline-none focus:ring-2 focus:ring-brand">
                    @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="ct-email" class="mb-1 block text-sm font-medium text-gray-700">Email (optional)</label>
                    <input type="email" id="ct-email" name="email" value="{{ old('email') }}" maxlength="255" autocomplete="email" class="w-full rounded-lg border border-gray-300 px-3 py-3 text-base focus:outline-none focus:ring-2 focus:ring-brand">
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="ct-subject" class="mb-1 block text-sm font-medium text-gray-700">Subject (optional)</label>
                    <input type="text" id="ct-subject" name="subject" value="{{ old('subject') }}" maxlength="120" class="w-full rounded-lg border border-gray-300 px-3 py-3 text-base focus:outline-none focus:ring-2 focus:ring-brand">
                    @error('subject')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="ct-message" class="mb-1 block text-sm font-medium text-gray-700">Message *</label>
                    <textarea id="ct-message" name="message" rows="4" required minlength="10" maxlength="2000" class="w-full rounded-lg border border-gray-300 px-3 py-3 text-base focus:outline-none focus:ring-2 focus:ring-brand">{{ old('message') }}</textarea>
                    @error('message')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <button type="submit" class="min-h-12 w-full rounded-lg bg-brand px-6 py-3 font-semibold text-white transition hover:bg-brand-dark sm:w-auto">Send message</button>
                </div>
            </form>
            <script>
                (function () {
                    var box = document.getElementById('contact-form');
                    var form = box.querySelector('form');
                    var submitBtn = form.querySelector('button[type=submit]');
                    var F = {
                        name: document.getElementById('ct-name'),
                        phone: document.getElementById('ct-phone'),
                        email: document.getElementById('ct-email'),
                        subject: document.getElementById('ct-subject'),
                        message: document.getElementById('ct-message')
                    };

                    function errEl(key) {
                        var el = F[key], p = el.parentNode.querySelector('p.text-red-600');
                        if (!p) { p = document.createElement('p'); p.className = 'mt-1 text-sm text-red-600 hidden'; el.parentNode.appendChild(p); }
                        return p;
                    }
                    function show(key, msg) {
                        var p = errEl(key);
                        p.textContent = msg;
                        p.classList.toggle('hidden', msg === '');
                        if (msg) { F[key].setAttribute('aria-invalid', 'true'); F[key].classList.add('border-red-500'); }
                        else { F[key].removeAttribute('aria-invalid'); F[key].classList.remove('border-red-500'); }
                    }
                    function counter(key, text) {
                        var label = F[key].parentNode.querySelector('label');
                        label.classList.add('flex', 'justify-between');
                        var s = document.createElement('span');
                        s.className = 'text-xs font-normal text-gray-400';
                        s.textContent = text;
                        label.appendChild(s);
                        return s;
                    }
                    var phoneCount = counter('phone', '0/10');
                    var msgCount = counter('message', '0/2000');

                    function normPhone(v) {
                        var d = String(v).replace(/\D/g, '');
                        if (d.length === 12 && d.indexOf('91') === 0) { d = d.slice(2); }
                        else if (d.length === 11 && d.indexOf('0') === 0) { d = d.slice(1); }
                        return d;
                    }

                    var rules = {
                        name: function (v) {
                            v = v.trim().replace(/\s+/g, ' ');
                            if (v === '') return 'Please enter your name.';
                            if (v.length < 2) return 'Name must be at least 2 characters.';
                            if (v.length > 60) return 'Name must be 60 characters or fewer.';
                            if (!/^[\p{L}][\p{L}\p{M}\s.'\-]*$/u.test(v)) return 'Use letters only. Numbers and symbols are not allowed.';
                            return '';
                        },
                        phone: function (v) {
                            var d = normPhone(v);
                            if (d === '') return 'Please enter your mobile number.';
                            if (!/^[6-9]\d{9}$/.test(d)) return 'Enter a valid 10-digit mobile number starting with 6, 7, 8 or 9.';
                            if (/^(\d)\1{9}$/.test(d)) return 'Please enter a real mobile number.';
                            return '';
                        },
                        email: function (v) {
                            v = v.trim();
                            if (v === '') return '';
                            return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v) ? '' : 'Please enter a valid email address.';
                        },
                        subject: function (v) { return v.trim().length > 120 ? 'Subject must be 120 characters or fewer.' : ''; },
                        message: function (v) {
                            v = v.trim();
                            if (v === '') return 'Please write your message.';
                            if (v.length < 10) return 'Please write a little more (at least 10 characters).';
                            if (v.length > 2000) return 'Message must be 2000 characters or fewer.';
                            return '';
                        }
                    };
                    function check(key) { var m = rules[key](F[key].value); show(key, m); return m === ''; }

                    F.name.addEventListener('input', function () {
                        var before = F.name.value;
                        var after = before.replace(/[^\p{L}\p{M}\s.'\-]/gu, '').replace(/^[^\p{L}]+/u, '');
                        if (after !== before) { F.name.value = after; show('name', 'Only letters are allowed. Numbers and symbols are not accepted.'); }
                        else if (F.name.hasAttribute('aria-invalid')) { check('name'); }
                    });
                    F.phone.addEventListener('input', function () {
                        var d = normPhone(F.phone.value).slice(0, 10), bad = false;
                        if (d.length > 0 && !/^[6-9]/.test(d)) { d = d.replace(/^[^6-9]+/, ''); bad = true; }
                        F.phone.value = d;
                        phoneCount.textContent = d.length + '/10';
                        if (bad) { show('phone', 'Mobile number must start with 6, 7, 8 or 9.'); }
                        else if (F.phone.hasAttribute('aria-invalid')) { check('phone'); }
                    });
                    F.message.addEventListener('input', function () {
                        msgCount.textContent = F.message.value.length + '/2000';
                        if (F.message.hasAttribute('aria-invalid')) { check('message'); }
                    });
                    ['email', 'subject'].forEach(function (k) {
                        F[k].addEventListener('input', function () { if (F[k].hasAttribute('aria-invalid')) { check(k); } });
                    });
                    Object.keys(F).forEach(function (k) { F[k].addEventListener('blur', function () { check(k); }); });

                    form.addEventListener('submit', function (e) {
                        F.phone.value = normPhone(F.phone.value);
                        var first = null;
                        ['name', 'phone', 'email', 'subject', 'message'].forEach(function (k) {
                            if (!check(k) && !first) { first = F[k]; }
                        });
                        if (first) { e.preventDefault(); first.focus(); return; }
                        submitBtn.disabled = true;
                        submitBtn.textContent = 'Sending...';
                    });
                    window.addEventListener('pageshow', function () { submitBtn.disabled = false; submitBtn.textContent = 'Send message'; });

                    phoneCount.textContent = normPhone(F.phone.value).length + '/10';
                    msgCount.textContent = F.message.value.length + '/2000';
                    @if ($errors->any() || session('success'))
                        box.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    @endif
                })();
            </script>
        </div>

        <div class="mt-8 rounded-2xl bg-gradient-to-br from-brand-dark to-brand p-6 text-center text-white sm:mt-10 sm:p-10">
            <h2 class="text-xl font-bold sm:text-3xl">Need a plumber or a product?</h2>
            <p class="mx-auto mt-2 max-w-xl text-sm text-sky-100 sm:text-base">Send a service request and we will call you back. No payment, no sign-up.</p>
            <a href="{{ url('/services') }}" class="mt-5 inline-flex min-h-12 w-full items-center justify-center rounded-lg bg-white px-6 py-3 font-semibold text-brand-dark transition hover:bg-sky-50 sm:w-auto">Book a service</a>
        </div>
    </div>
</section>
@endsection
