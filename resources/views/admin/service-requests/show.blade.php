@extends('admin.layouts.app')
@section('title', 'Service Request')
@section('content')
    @php $tel = preg_replace('/[^0-9+]/', '', $req->phone); @endphp

    <a href="{{ route('admin.service-requests.index') }}" class="text-sm text-sky-600 hover:underline">&larr; Back to requests</a>
    <h2 class="text-2xl font-bold text-sky-700 mt-2 mb-4">Request #{{ $req->id }}</h2>

    <div class="grid gap-6 lg:grid-cols-2 max-w-4xl">
        <div class="bg-white rounded-xl shadow p-6 space-y-3 text-sm">
            <div><span class="text-gray-500">Name:</span> <span class="font-medium">{{ $req->name }}</span></div>
            <div><span class="text-gray-500">Phone:</span> <span class="font-medium">{{ $req->phone }}</span></div>
            <div><span class="text-gray-500">Email:</span> {{ $req->email ?: '-' }}</div>
            <div><span class="text-gray-500">Address:</span> {{ $req->address }}</div>
            <div><span class="text-gray-500">Service wanted:</span> <span class="font-medium">{{ $req->services->pluck('name')->implode(', ') ?: 'Not specified' }}</span></div>
            <div><span class="text-gray-500">Received:</span> {{ $req->created_at->format('d M Y, h:i A') }}</div>
            @if ($req->contacted_at)
                <div><span class="text-gray-500">First contacted:</span> {{ $req->contacted_at->format('d M Y, h:i A') }}</div>
            @endif

            <a href="tel:{{ $tel }}" class="inline-block mt-2 bg-sky-500 hover:bg-sky-600 text-white font-semibold px-5 py-2 rounded-lg">
                Call {{ $req->phone }}
            </a>
        </div>

        <form method="POST" action="{{ route('admin.service-requests.update', $req) }}" class="bg-white rounded-xl shadow p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm text-gray-600 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    @foreach (\App\Models\ServiceRequest::STATUSES as $key => $label)
                        <option value="{{ $key }}" @selected($req->status === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Notes</label>
                <textarea name="admin_notes" rows="5" class="w-full border border-gray-300 rounded-lg px-3 py-2">{{ old('admin_notes', $req->admin_notes) }}</textarea>
            </div>
            <button class="bg-sky-500 hover:bg-sky-600 text-white font-semibold px-5 py-2 rounded-lg">Save</button>
        </form>
    </div>

    <form method="POST" action="{{ route('admin.service-requests.destroy', $req) }}" class="mt-6"
          onsubmit="return confirm('Delete this request?')">
        @csrf
        @method('DELETE')
        <button class="text-sm text-red-600 hover:underline">Delete this request</button>
    </form>
@endsection
