@extends('admin.layouts.app')
@section('title', 'Service Requests')
@section('content')
    <h2 class="text-2xl font-bold text-sky-700 mb-4">Service Requests</h2>

    <form method="GET" class="flex gap-2 mb-4">
        <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">All statuses</option>
            @foreach (\App\Models\ServiceRequest::STATUSES as $key => $label)
                <option value="{{ $key }}" @selected(request('status') == $key)>{{ $label }}</option>
            @endforeach
        </select>
        <button class="bg-sky-100 text-sky-700 px-4 py-2 rounded-lg text-sm">Filter</button>
    </form>

    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-sky-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3">Received</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Phone</th>
                    <th class="px-4 py-3">Service</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($requests as $r)
                    <tr class="border-t border-gray-100">
                        <td class="px-4 py-2 whitespace-nowrap">{{ $r->created_at->format('d M Y, h:i A') }}</td>
                        <td class="px-4 py-2 font-medium">{{ $r->name }}</td>
                        <td class="px-4 py-2">
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $r->phone) }}" class="text-sky-600 hover:underline">{{ $r->phone }}</a>
                        </td>
                        <td class="px-4 py-2">{{ $r->services->pluck('name')->implode(', ') ?: '-' }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded-full text-xs {{ $r->status === 'new' ? 'bg-sky-100 text-sky-700' : ($r->status === 'completed' ? 'bg-green-100 text-green-700' : ($r->status === 'cancelled' ? 'bg-gray-100 text-gray-500' : 'bg-yellow-100 text-yellow-700')) }}">
                                {{ \App\Models\ServiceRequest::STATUSES[$r->status] ?? $r->status }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('admin.service-requests.show', $r) }}" class="text-sky-600 hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">No requests yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $requests->links() }}</div>
@endsection
