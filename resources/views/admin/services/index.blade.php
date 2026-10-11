@extends('admin.layouts.app')
@section('title', 'Services')
@section('content')
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-sky-700">Services</h2>
        <a href="{{ route('admin.services.create') }}"
           class="bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded-lg text-sm font-semibold">+ Add Service</a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-sky-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3">Image</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($services as $service)
                    <tr class="border-t border-gray-100">
                        <td class="px-4 py-2">
                            @if ($service->image)
                                <img src="{{ asset('storage/'.$service->image) }}" class="h-10 w-10 rounded object-cover" alt="">
                            @else
                                <div class="h-10 w-10 rounded bg-sky-100"></div>
                            @endif
                        </td>
                        <td class="px-4 py-2 font-medium">{{ $service->name }}</td>
                        <td class="px-4 py-2">{{ $service->sort_order }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded-full text-xs {{ $service->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $service->is_active ? 'Active' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-right whitespace-nowrap">
                            <a href="{{ route('admin.services.edit', $service) }}" class="text-sky-600 hover:underline mr-3">Edit</a>
                            <form method="POST" action="{{ route('admin.services.destroy', $service) }}" class="inline"
                                  onsubmit="return confirm('Delete this service?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">No services yet. Click "+ Add Service".</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
