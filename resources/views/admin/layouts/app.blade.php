@php
    $newReqCount = \App\Models\ServiceRequest::where('status', 'new')->count();
    $nav = [
        'Overview' => [
            ['Dashboard', 'admin.dashboard', 'admin.dashboard', 'M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25'],
            ['Service Requests', 'admin.service-requests.index', 'admin.service-requests.*', 'M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z'],
        ],
        'Catalog' => [
            ['Products', 'admin.products.index', 'admin.products.*', 'M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9'],
            ['Categories', 'admin.categories.index', 'admin.categories.*', 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z'],
            ['Sub Categories', 'admin.subcategories.index', 'admin.subcategories.*', 'M6 6.878V6a2.25 2.25 0 012.25-2.25h7.5A2.25 2.25 0 0118 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 004.5 9v.878m13.5-3A2.25 2.25 0 0119.5 9v.878m0 0a2.246 2.246 0 00-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0121 12v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6c0-.98.626-1.813 1.5-2.122'],
            ['Brands', 'admin.brands.index', 'admin.brands.*', 'M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z'],
            ['Sizes', 'admin.sizes.index', 'admin.sizes.*', 'M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5'],
        ],
        'Business' => [
            ['Services', 'admin.services.index', 'admin.services.*', 'M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26'],
            ['Settings', 'admin.settings.edit', 'admin.settings.*', 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
            ['Change Password', 'admin.password.edit', 'admin.password.*', 'M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z'],
        ],
    ];
    $adminName = auth()->user()->name ?? 'Admin';
    $adminInitial = strtoupper(mb_substr($adminName, 0, 1));
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') | Saroj Pipe Works</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css">
</head>
<body class="min-h-screen bg-slate-50 text-gray-800 antialiased">

<div id="overlay" class="fixed inset-0 z-30 hidden bg-black/40 md:hidden"></div>

<aside id="sidebar" data-admin-v2 class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col border-r border-gray-200 bg-white transition-transform md:translate-x-0">
    <div class="flex h-16 items-center border-b border-gray-100 px-5">
        <a href="{{ route('admin.dashboard') }}" class="flex min-w-0 items-center gap-2">
            @if (file_exists(public_path('images/logo.png')))
                <img src="{{ asset('images/logo.png') }}" alt="{{ config('business.name') }}" class="h-10">
            @else
                <span class="truncate text-lg font-bold text-brand-dark">{{ config('business.name') }}</span>
            @endif
        </a>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-4 text-sm">
        @foreach ($nav as $group => $items)
            <p class="{{ $loop->first ? '' : 'mt-5' }} mb-2 px-3 text-[11px] font-semibold uppercase tracking-wider text-gray-400">{{ $group }}</p>
            <div class="space-y-1">
                @foreach ($items as [$label, $route, $pattern, $icon])
                    @php $active = request()->routeIs($pattern); @endphp
                    <a href="{{ route($route) }}" class="flex min-h-11 items-center gap-3 rounded-lg px-3 py-2 font-medium transition {{ $active ? 'bg-brand text-white shadow-sm' : 'text-gray-600 hover:bg-sky-50 hover:text-brand-dark' }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                        <span class="min-w-0 truncate">{{ $label }}</span>
                        @if ($route === 'admin.service-requests.index' && $newReqCount > 0)
                            <span class="ml-auto rounded-full {{ $active ? 'bg-white text-brand-dark' : 'bg-brand text-white' }} px-2 py-0.5 text-xs font-semibold">{{ $newReqCount }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        @endforeach
    </nav>

    <div class="border-t border-gray-100 p-3">
        <div class="flex items-center gap-3 rounded-lg bg-sky-50 p-2.5">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-dark text-sm font-bold text-white">{{ $adminInitial }}</span>
            <span class="min-w-0 flex-1">
                <span class="block truncate text-sm font-semibold text-gray-800">{{ $adminName }}</span>
                <span class="block text-xs text-gray-500">Administrator</span>
            </span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" aria-label="Logout" title="Logout" class="flex h-10 w-10 items-center justify-center rounded-lg text-gray-500 hover:bg-red-50 hover:text-red-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/></svg>
                </button>
            </form>
        </div>
    </div>
</aside>

<div class="md:pl-64">
    <header class="sticky top-0 z-20 flex h-16 items-center gap-3 border-b border-gray-200 bg-white/90 px-4 backdrop-blur md:px-8">
        <button id="menuBtn" type="button" class="flex h-11 w-11 items-center justify-center rounded-lg text-gray-600 hover:bg-gray-100 md:hidden" aria-label="Open menu">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
        </button>
        <h1 class="min-w-0 truncate text-base font-semibold text-gray-800 sm:text-lg">@yield('title', 'Admin')</h1>
        <div class="ml-auto flex items-center gap-2">
            <a href="{{ route('home') }}" target="_blank" rel="noopener" class="inline-flex min-h-10 items-center gap-1 rounded-lg border border-gray-200 bg-white px-3 text-sm font-medium text-brand hover:bg-sky-50">
                <span class="hidden sm:inline">View website</span><span class="sm:hidden">Site</span> &nearr;
            </a>
            <span class="hidden h-10 w-10 items-center justify-center rounded-full bg-brand-dark text-sm font-bold text-white sm:flex" title="{{ $adminName }}">{{ $adminInitial }}</span>
        </div>
    </header>

    <main class="mx-auto max-w-6xl p-4 md:p-8">
        @if (session('success'))
            <div data-flash class="mb-4 flex items-start justify-between rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.closest('[data-flash]').remove()" class="ml-4 text-green-700">&times;</button>
            </div>
        @endif
        @if (session('error'))
            <div data-flash class="mb-4 flex items-start justify-between rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <span>{{ session('error') }}</span>
                <button type="button" onclick="this.closest('[data-flash]').remove()" class="ml-4 text-red-700">&times;</button>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script>
    const sb = document.getElementById('sidebar'), ov = document.getElementById('overlay');
    const toggle = () => { sb.classList.toggle('-translate-x-full'); ov.classList.toggle('hidden'); };
    document.getElementById('menuBtn').onclick = toggle;
    ov.onclick = toggle;
</script>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script>
document.querySelectorAll('textarea[data-rich]').forEach(ta => {
    if (ta.dataset.ready) return;
    ta.dataset.ready = '1';
    const ed = document.createElement('div');
    ed.className = 'bg-white';
    const wrap = document.createElement('div'); wrap.className = 'quill-wrap'; wrap.appendChild(ed); ta.insertAdjacentElement('afterend', wrap);
    ta.style.display = 'none';
    const q = new Quill(ed, { theme: 'snow', modules: { toolbar: [[{ header: [2, 3, false] }], ['bold', 'italic', 'underline'], [{ list: 'ordered' }, { list: 'bullet' }], ['link', 'blockquote'], ['clean']] } });
    const raw = ta.value;
    if (raw) q.clipboard.dangerouslyPasteHTML(raw.includes('<') ? raw : raw.replace(/\n/g, '<br>'));
    ta.form.addEventListener('submit', () => { ta.value = q.getText().trim() === '' ? '' : q.root.innerHTML; });
});
</script>
<script>
document.querySelectorAll('input[data-preview]').forEach(inp => {
    const img = document.createElement('img');
    img.className = 'mt-2 hidden h-28 w-28 rounded-lg border object-cover';
    img.alt = 'Preview';
    inp.insertAdjacentElement('afterend', img);
    inp.addEventListener('change', () => {
        const f = inp.files[0];
        if (f && f.type.startsWith('image/')) { img.src = URL.createObjectURL(f); img.classList.remove('hidden'); }
        else { img.classList.add('hidden'); }
    });
});
</script>
</body>
</html>
