<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | {{ config('business.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-gradient-to-br from-brand-dark to-brand p-4">
    <form method="POST" action="{{ route('login') }}" class="w-full max-w-sm space-y-4 rounded-2xl bg-white p-6 shadow-xl sm:p-8">
        @csrf
        <div class="text-center">
            @if (file_exists(public_path('images/logo.png')))
                <img src="{{ asset('images/logo.png') }}" alt="{{ config('business.name') }}" class="mx-auto h-14">
            @endif
            <h1 class="mt-3 text-2xl font-bold text-brand-dark">Admin Login</h1>
        </div>

        @error('email')
            <div role="alert" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">{{ $message }}</div>
        @enderror

        <div>
            <label for="email" class="mb-1 block text-sm text-gray-600">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   class="w-full rounded-lg border border-gray-300 px-3 py-3 text-base focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/30">
        </div>
        <div>
            <label for="password" class="mb-1 block text-sm text-gray-600">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="w-full rounded-lg border border-gray-300 px-3 py-3 text-base focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/30">
        </div>
        <label class="flex min-h-11 items-center gap-2 text-sm text-gray-600">
            <input type="checkbox" name="remember" class="h-4 w-4"> Remember me
        </label>
        <button class="min-h-12 w-full rounded-lg bg-brand py-3 font-semibold text-white transition hover:bg-brand-dark">Sign in</button>
    </form>
</body>
</html>
