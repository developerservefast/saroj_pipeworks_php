<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | Saroj Pipe Works</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-sky-50 flex items-center justify-center p-4">
    <form method="POST" action="{{ route('login') }}" class="w-full max-w-sm bg-white rounded-xl shadow p-8 space-y-4">
        @csrf
        <h1 class="text-2xl font-bold text-sky-600">Admin Login</h1>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-400">
            @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Password</label>
            <input type="password" name="password" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-400">
        </div>
        <label class="flex items-center gap-2 text-sm text-gray-600">
            <input type="checkbox" name="remember"> Remember me
        </label>
        <button class="w-full bg-sky-500 hover:bg-sky-600 text-white font-semibold rounded-lg py-2">Sign in</button>
    </form>
</body>
</html>
