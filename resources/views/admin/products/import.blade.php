@extends('admin.layouts.app')
@section('title', 'Import Products')

@section('content')
<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Import products</h1>
        <p class="text-sm text-gray-500">Add or update many products at once from an Excel file.</p>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn-outline">&larr; Back to products</a>
</div>

@if (session('import_errors'))
    <div class="mb-5 max-w-4xl rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
        <p class="mb-2 font-semibold">Import problem</p>
        <ul class="list-disc space-y-1 pl-5">
            @foreach (session('import_errors') as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
@endif
@if ($errors->any())
    <div class="mb-5 max-w-4xl rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">{{ $errors->first() }}</div>
@endif

<div class="grid max-w-4xl gap-5 lg:grid-cols-2">
    {{-- Step 1 --}}
    <div class="card p-5">
        <div class="mb-3 flex items-center gap-3">
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-sky-100 text-sm font-bold text-sky-700">1</span>
            <h2 class="font-semibold text-gray-800">Download the template</h2>
        </div>
        <p class="text-sm text-gray-600">The Excel file has dropdowns for category, subcategory, brands and yes/no columns. Download a fresh one after adding categories or brands.</p>
        <a href="{{ route('admin.products.import.template') }}" class="btn-outline mt-4 inline-flex items-center gap-2">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12M12 16.5V3"/></svg>
            Download template (Excel)
        </a>
        <p class="mt-3 text-xs text-gray-500">Only <b>name</b> and <b>category</b> are required.</p>
    </div>

    {{-- Step 2 --}}
    <form method="POST" action="{{ route('admin.products.import.store') }}" enctype="multipart/form-data" id="importForm" class="card p-5">
        @csrf
        <div class="mb-3 flex items-center gap-3">
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-sky-100 text-sm font-bold text-sky-700">2</span>
            <h2 class="font-semibold text-gray-800">Upload your file</h2>
        </div>

        <label id="drop" for="file" class="flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 px-4 py-8 text-center transition hover:border-sky-400 hover:bg-sky-50">
            <svg class="h-9 w-9 text-sky-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg>
            <span id="fileName" class="mt-2 text-sm font-medium text-gray-700">Click to choose a file, or drop it here</span>
            <span class="mt-1 text-xs text-gray-500">.xlsx or .csv, up to 2 MB and 2000 products</span>
            <input type="file" id="file" name="file" accept=".xlsx,.csv,.txt" required class="sr-only">
        </label>

        <button id="importBtn" class="btn-primary mt-4 w-full disabled:cursor-not-allowed disabled:opacity-50" disabled>Import products</button>
        <p class="mt-2 text-xs text-gray-500">Every row is checked first. If any row has an error, nothing is saved.</p>
    </form>
</div>

{{-- Column guide --}}
<div class="card mt-5 max-w-4xl p-5">
    <h2 class="mb-3 font-semibold text-gray-800">How to fill the columns</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <tbody class="divide-y divide-gray-100">
                <tr><td class="whitespace-nowrap py-2 pr-4 font-semibold text-gray-700">category / subcategory</td><td class="py-2 text-gray-600">Pick from the dropdown. Choose the category first. New names are created automatically.</td></tr>
                <tr><td class="whitespace-nowrap py-2 pr-4 font-semibold text-gray-700">brands</td><td class="py-2 text-gray-600">One or more, separated by <code class="rounded bg-gray-100 px-1">|</code>, for example <code class="rounded bg-gray-100 px-1">Astral|Ashirvad</code>.</td></tr>
                <tr><td class="whitespace-nowrap py-2 pr-4 font-semibold text-gray-700">sizes</td><td class="py-2 text-gray-600">Separate with <code class="rounded bg-gray-100 px-1">|</code>, for example <code class="rounded bg-gray-100 px-1">1/2"|3/4"</code>.</td></tr>
                <tr><td class="whitespace-nowrap py-2 pr-4 font-semibold text-gray-700">is_active, is_bestseller, is_trending, is_latest</td><td class="py-2 text-gray-600">yes or no. A blank <b>is_active</b> means the product is shown.</td></tr>
                <tr><td class="whitespace-nowrap py-2 pr-4 font-semibold text-gray-700">variants</td><td class="py-2 text-gray-600"><code class="rounded bg-gray-100 px-1">Red:#ff0000:120|Blue:#0000ff</code> (name:colour:price, colour and price optional).</td></tr>
                <tr><td class="whitespace-nowrap py-2 pr-4 font-semibold text-gray-700">Existing products</td><td class="py-2 text-gray-600">Same name in the same category is <b>updated</b>. Blank cells keep the old value.</td></tr>
                <tr><td class="whitespace-nowrap py-2 pr-4 font-semibold text-gray-700">Images</td><td class="py-2 text-gray-600">Add them afterwards on each product's edit page.</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    (function () {
        var input = document.getElementById('file'), label = document.getElementById('fileName');
        var btn = document.getElementById('importBtn'), drop = document.getElementById('drop');
        var form = document.getElementById('importForm');

        function show() {
            var f = input.files[0];
            label.textContent = f ? f.name : 'Click to choose a file, or drop it here';
            btn.disabled = !f;
        }
        input.addEventListener('change', show);
        ['dragenter', 'dragover'].forEach(function (ev) {
            drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.add('border-sky-400', 'bg-sky-50'); });
        });
        ['dragleave', 'drop'].forEach(function (ev) {
            drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.remove('border-sky-400', 'bg-sky-50'); });
        });
        drop.addEventListener('drop', function (e) {
            if (e.dataTransfer.files.length) { input.files = e.dataTransfer.files; show(); }
        });
        form.addEventListener('submit', function () { btn.disabled = true; btn.textContent = 'Importing...'; });
        window.addEventListener('pageshow', function () { btn.textContent = 'Import products'; show(); });
        show();
    })();
</script>
@endsection