@php
    $crumbs = [['Home', url('/')]];
    if ($__env->hasSection('header_parent')) {
        [$pl, $pu] = array_pad(explode('|', trim($__env->yieldContent('header_parent'))), 2, '/');
        $crumbs[] = [$pl, url($pu)];
    }
    $crumbs[] = [trim($__env->yieldContent('header_title')), null];
@endphp
<section id="page-header" class="relative overflow-hidden bg-gradient-to-br from-brand via-brand-dark to-brand text-white">
    <div class="pointer-events-none absolute -left-24 -top-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-32 -right-16 h-80 w-80 rounded-full bg-sky-300/30 blur-3xl"></div>
    <div class="pointer-events-none absolute inset-0 opacity-[0.07]" style="background-image: radial-gradient(circle, #ffffff 1px, transparent 1px); background-size: 22px 22px"></div>

    <div class="relative mx-auto max-w-6xl px-4 pb-14 pt-10 text-center sm:px-6 sm:pb-20 sm:pt-14">
        <nav aria-label="Breadcrumb" class="mb-5 inline-flex max-w-full items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-xs font-medium backdrop-blur-sm sm:mb-6">
            @foreach ($crumbs as [$l, $u])
                @if ($u)
                    <a href="{{ $u }}" class="shrink-0 text-white/70 transition-colors hover:text-white">{{ $l }}</a>
                    <span class="shrink-0 text-white/40">/</span>
                @else
                    <span class="min-w-0 truncate text-white">{{ $l }}</span>
                @endif
            @endforeach
        </nav>

        @hasSection('header_eyebrow')
            <div class="mb-3 flex items-center justify-center gap-2">
                <span class="h-px w-8 bg-white/40"></span>
                <span class="text-xs font-semibold uppercase tracking-widest text-white/80 sm:text-sm">@yield('header_eyebrow')</span>
                <span class="h-px w-8 bg-white/40"></span>
            </div>
        @endif

        <h1 class="break-words text-3xl font-bold tracking-tight sm:text-5xl">@yield('header_title')</h1>

        @hasSection('header_sub')
            <p class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-white/85 sm:text-base">@yield('header_sub')</p>
        @endif
    </div>

    <svg class="relative block h-10 w-full text-white sm:h-14" viewBox="0 0 1440 60" preserveAspectRatio="none" fill="currentColor" aria-hidden="true">
        <path d="M0,32 C240,60 480,60 720,36 C960,12 1200,12 1440,32 L1440,60 L0,60 Z"/>
    </svg>
</section>
