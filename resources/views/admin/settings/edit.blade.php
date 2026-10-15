@extends('admin.layouts.app')
@section('title', 'Settings')

@section('content')
@if ($errors->any())
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('admin.settings.update') }}" class="card max-w-2xl space-y-4 p-5">
    @csrf @method('PUT')
    @php
        $fields = ['name' => 'Business name *', 'tagline' => 'Tagline', 'phone' => 'Phone *', 'email' => 'Email', 'address' => 'Address', 'hours' => 'Opening hours'];
    @endphp
    @foreach ($fields as $k => $label)
        <div>
            <label class="mb-1 block text-xs font-semibold uppercase text-gray-500">{{ $label }}</label>
            <input name="{{ $k }}" value="{{ old($k, config('business.'.$k)) }}" class="input">
        </div>
    @endforeach
    <p class="text-xs text-gray-500">These show in the website header, footer, Contact page and Call buttons.</p>
    <button class="btn-primary w-full sm:w-auto">Save settings</button>
</form>
@endsection