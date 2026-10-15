@extends('admin.layouts.app')
@section('title', 'Change password')

@section('content')
@if ($errors->any())
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('admin.password.update') }}" class="card max-w-md space-y-4 p-5">
    @csrf @method('PUT')
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase text-gray-500">Current password</label>
        <input type="password" name="current_password" required autocomplete="current-password" class="input">
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase text-gray-500">New password</label>
        <input type="password" name="password" required minlength="10" autocomplete="new-password" class="input">
        <p class="mt-1 text-xs text-gray-500">At least 10 characters.</p>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase text-gray-500">Confirm new password</label>
        <input type="password" name="password_confirmation" required autocomplete="new-password" class="input">
    </div>
    <button class="btn-primary w-full sm:w-auto">Change password</button>
</form>
@endsection