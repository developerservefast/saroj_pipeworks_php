@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('content')
    <h2 class="text-2xl font-bold text-sky-700 mb-4">Welcome, {{ auth()->user()->name }}</h2>
    <div class="grid gap-4 sm:grid-cols-2 max-w-xl">
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Products</p>
            <p class="text-3xl font-bold text-sky-600">{{ $productCount }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Categories</p>
            <p class="text-3xl font-bold text-sky-600">{{ $categoryCount }}</p>
        </div>
    </div>
@endsection
