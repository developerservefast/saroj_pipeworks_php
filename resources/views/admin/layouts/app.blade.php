@php
    $newReqCount = \App\Models\ServiceRequest::where('status', 'new')->count();
    $nav = [
        ['Dashboard', 'admin.dashboard', 'admin.dashboard', 'M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25'],
        ['Products', 'admin.products.index', 'admin.products.*', 'M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9'],
        ['Categories', 'admin.categories.index', 'admin.categories.*', 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z'],
        ['Sub Categories', 'admin.subcategories.index', 'admin.subcategories.*', 'M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5'],
        ['Brands', 'admin.brands.index', 'admin.brands.*', 'M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z'],
        ['Sizes', 'admin.sizes.index', 'admin.sizes.*', 'M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5'],
        ['Services', 'admin.services.index', 'admin.services.*', 'M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5'],
        ['Settings', 'admin.settings.edit', 'admin.settings.*', 'M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5'],
        ['Service Requests', 'admin.service-requests.index', 'admin.service-requests.*', 'M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z'],
    ];
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

<aside id="sidebar" class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col border-r border-gray-200 bg-white transition-transform md:translate-x-0">
    <div class="flex h-14 items-center border-b border-gray-100 px-5">
        <span class="text-lg font-bold text-brand-dark">Saroj Pipe Works</span>
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto p-3 text-sm">
        @foreach ($nav as [$label, $route, $pattern, $icon])
            @php $active = request()->routeIs($pattern); @endphp
            <a href="{{ route($route) }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium transition {{ $active ? 'bg-brand text-white shadow-sm' : 'text-gray-600 hover:bg-sky-50 hover:text-brand-dark' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                {{ $label }}
            @if ($route === 'admin.service-requests.index' && $newReqCount > 0)<span class="ml-auto rounded-full {{ $active ? 'bg-white text-brand-dark' : 'bg-brand text-white' }} px-2 py-0.5 text-xs font-semibold">{{ $newReqCount }}</span>@endif
            </a>
        @endforeach
    </nav>

    <a href="{{ route('admin.password.edit') }}" class="mb-1 block rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('admin.password.*') ? 'bg-brand text-white' : 'text-gray-500 hover:bg-sky-50 hover:text-brand-dark' }}">Change password</a>
        <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100 p-3">
        @csrf
        <button class="w-full rounded-lg px-3 py-2.5 text-left text-sm font-medium text-gray-500 hover:bg-red-50 hover:text-red-600">Logout</button>
    </form>
</aside>

<div class="md:pl-64">
    <header class="sticky top-0 z-20 flex h-14 items-center gap-3 border-b border-gray-200 bg-white/90 px-4 backdrop-blur md:px-8">
        <button id="menuBtn" type="button" class="rounded-lg p-2 text-gray-600 hover:bg-gray-100 md:hidden" aria-label="Open menu">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
        </button>
        <h1 class="text-base font-semibold text-gray-800">@yield('title', 'Admin')</h1>
        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="ml-auto text-sm text-brand hover:underline">View website &nearr;</a>
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
