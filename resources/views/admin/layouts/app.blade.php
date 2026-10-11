<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') | Saroj Pipe Works</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-sky-50 text-gray-800">
<div class="flex min-h-screen flex-col md:flex-row">
    <aside class="md:w-60 bg-white shadow md:min-h-screen p-4">
        <h1 class="text-xl font-bold text-sky-600 mb-6">Saroj Pipe Works</h1>
        <nav class="flex md:flex-col gap-2 text-sm">
            <a href="{{ route('admin.dashboard') }}"
               class="px-3 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-sky-500 text-white' : 'text-gray-700 hover:bg-sky-50' }}">Dashboard</a>
            <a href="{{ route('admin.products.index') }}"
               class="px-3 py-2 rounded-lg {{ request()->routeIs('admin.products.*') ? 'bg-sky-500 text-white' : 'text-gray-700 hover:bg-sky-50' }}">Products</a>
               <a href="{{ route('admin.services.index') }}"
   class="px-3 py-2 rounded-lg {{ request()->routeIs('admin.services.*') ? 'bg-sky-500 text-white' : 'text-gray-700 hover:bg-sky-50' }}">Services</a>
<a href="{{ route('admin.service-requests.index') }}"
   class="px-3 py-2 rounded-lg {{ request()->routeIs('admin.service-requests.*') ? 'bg-sky-500 text-white' : 'text-gray-700 hover:bg-sky-50' }}">Service Requests</a>
        </nav>
        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <button class="text-sm text-gray-500 hover:text-sky-600">Logout</button>
        </form>
    </aside>

    <main class="flex-1 p-6">
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-2 text-sm">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>
</div>
</body>
</html>
