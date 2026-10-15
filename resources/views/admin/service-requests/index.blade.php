@extends('admin.layouts.app')
@section('title', 'Service Requests')

@section('content')
@php
    $badge = fn ($s) => match ($s) {
        'new' => 'bg-sky-100 text-sky-700',
        'completed' => 'bg-green-100 text-green-700',
        'cancelled' => 'bg-gray-100 text-gray-500',
        default => 'bg-yellow-100 text-yellow-700',
    };
    $current = request('status');
@endphp

<div class="mb-4 flex gap-2 overflow-x-auto pb-1">
    <a href="{{ route('admin.service-requests.index') }}" class="whitespace-nowrap rounded-lg px-4 py-2 text-sm font-medium {{ ! $current ? 'bg-gray-900 text-white' : 'border border-gray-200 bg-white text-gray-600' }}">All ({{ $counts->sum() }})</a>
    @foreach (\App\Models\ServiceRequest::STATUSES as $key => $label)
        <a href="{{ route('admin.service-requests.index', ['status' => $key]) }}" class="whitespace-nowrap rounded-lg px-4 py-2 text-sm font-medium {{ $current === $key ? 'bg-gray-900 text-white' : 'border border-gray-200 bg-white text-gray-600' }}">{{ $label }} ({{ $counts[$key] ?? 0 }})</a>
    @endforeach
</div>

<div class="space-y-2">
@forelse ($requests as $r)
    <div class="card flex flex-wrap items-center gap-3 p-3 {{ $r->status === 'new' ? 'border-sky-300' : '' }}">
        <div class="min-w-0 flex-1 basis-56">
            <p class="truncate font-medium">{{ $r->name }}</p>
            <p class="truncate text-xs text-gray-500">{{ $r->services->pluck('name')->implode(', ') ?: 'No service chosen' }}</p>
            <p class="text-xs text-gray-400">{{ $r->created_at->format('d M Y, h:i A') }} · {{ $r->created_at->diffForHumans() }}</p>
        </div>
        <span class="rounded-full px-2 py-0.5 text-xs {{ $badge($r->status) }}">{{ \App\Models\ServiceRequest::STATUSES[$r->status] ?? $r->status }}</span>
        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $r->phone) }}" class="btn-primary !px-3 !py-1.5">Call {{ $r->phone }}</a>
        <a href="{{ route('admin.service-requests.show', $r) }}" class="btn-outline !px-3 !py-1.5">View</a>
    </div>
@empty
    <p class="card p-6 text-center text-gray-500">No requests found.</p>
@endforelse
</div>

<div class="mt-4">{{ $requests->links() }}</div>
@endsection