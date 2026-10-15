@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')
<h2 class="mb-4 text-xl font-bold text-sky-700 sm:text-2xl">Welcome, {{ auth()->user()->name }}</h2>

<div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
    <a href="{{ route('admin.service-requests.index', ['status' => 'new']) }}" class="card p-4 sm:p-5 {{ $newRequests ? 'border-sky-300 bg-sky-50' : '' }}">
        <p class="text-sm text-gray-500">New requests</p>
        <p class="text-2xl font-bold text-sky-600 sm:text-3xl">{{ $newRequests }}</p>
        <p class="mt-1 text-xs text-gray-500">waiting for a call</p>
    </a>
    <a href="{{ route('admin.products.index') }}" class="card p-4 sm:p-5">
        <p class="text-sm text-gray-500">Products</p>
        <p class="text-2xl font-bold text-sky-600 sm:text-3xl">{{ $productCount }}</p>
    </a>
    <a href="{{ route('admin.products.index', ['status' => 'hidden']) }}" class="card p-4 sm:p-5">
        <p class="text-sm text-gray-500">Hidden products</p>
        <p class="text-2xl font-bold text-gray-600 sm:text-3xl">{{ $hiddenCount }}</p>
    </a>
    <a href="{{ route('admin.categories.index') }}" class="card p-4 sm:p-5">
        <p class="text-sm text-gray-500">Categories</p>
        <p class="text-2xl font-bold text-sky-600 sm:text-3xl">{{ $categoryCount }}</p>
    </a>
</div>

<div class="mt-8 flex items-center justify-between">
    <h3 class="font-semibold text-gray-800">Latest requests</h3>
    <a href="{{ route('admin.service-requests.index') }}" class="text-sm text-brand hover:underline">View all &rarr;</a>
</div>

<div class="mt-3 space-y-2">
@forelse ($recent as $r)
    @php
        $badge = $r->status === 'new' ? 'bg-sky-100 text-sky-700' : ($r->status === 'completed' ? 'bg-green-100 text-green-700' : ($r->status === 'cancelled' ? 'bg-gray-100 text-gray-500' : 'bg-yellow-100 text-yellow-700'));
    @endphp
    <div class="card flex flex-wrap items-center gap-3 p-3">
        <div class="min-w-0 flex-1 basis-48">
            <p class="truncate font-medium">{{ $r->name }}</p>
            <p class="truncate text-xs text-gray-500">{{ $r->services->pluck('name')->implode(', ') ?: 'No service chosen' }} · {{ $r->created_at->diffForHumans() }}</p>
        </div>
        <span class="rounded-full px-2 py-0.5 text-xs {{ $badge }}">{{ \App\Models\ServiceRequest::STATUSES[$r->status] ?? $r->status }}</span>
        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $r->phone) }}" class="btn-primary !px-3 !py-1.5">Call</a>
        <a href="{{ route('admin.service-requests.show', $r) }}" class="btn-outline !px-3 !py-1.5">View</a>
    </div>
@empty
    <p class="card p-6 text-center text-gray-500">No requests yet.</p>
@endforelse
</div>
@endsection