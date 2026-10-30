@extends('admin.layouts.app')
@section('title', 'Contact Messages')
@section('content')
<div class="mb-4 flex flex-wrap items-center justify-between gap-3">
    <h1 class="text-xl font-bold text-gray-800">Contact Messages</h1>
    <div class="flex gap-2 text-sm">
        <a href="{{ route('admin.contact-messages.index') }}" class="rounded-lg border px-3 py-1.5 hover:bg-gray-50">All</a>
        <a href="{{ route('admin.contact-messages.index', ['status' => 'new']) }}" class="rounded-lg border px-3 py-1.5 hover:bg-gray-50">New ({{ $counts['new'] ?? 0 }})</a>
        <a href="{{ route('admin.contact-messages.index', ['status' => 'read']) }}" class="rounded-lg border px-3 py-1.5 hover:bg-gray-50">Read ({{ $counts['read'] ?? 0 }})</a>
    </div>
</div>

<div class="overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
    <table class="min-w-full text-left text-sm">
        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
            <tr><th class="px-4 py-3">Name</th><th class="px-4 py-3">Phone</th><th class="px-4 py-3">Message</th><th class="px-4 py-3">Date</th><th class="px-4 py-3">Status</th><th class="px-4 py-3"></th></tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($messages as $m)
                <tr class="{{ $m->status === 'new' ? 'bg-sky-50/60 font-medium' : '' }}">
                    <td class="px-4 py-3">{{ $m->name }}</td>
                    <td class="px-4 py-3"><a href="tel:{{ $m->phone }}" class="text-sky-600 hover:underline">{{ $m->phone }}</a></td>
                    <td class="max-w-xs truncate px-4 py-3">{{ $m->subject ? $m->subject . ': ' : '' }}{{ \Illuminate\Support\Str::limit($m->message, 60) }}</td>
                    <td class="whitespace-nowrap px-4 py-3">{{ $m->created_at->format('d M Y, h:i A') }}</td>
                    <td class="px-4 py-3"><span class="rounded-full px-2 py-0.5 text-xs {{ $m->status === 'new' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700' }}">{{ ucfirst($m->status) }}</span></td>
                    <td class="px-4 py-3 text-right"><a href="{{ route('admin.contact-messages.show', $m) }}" class="font-semibold text-sky-600 hover:underline">View</a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">No messages yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $messages->links() }}</div>
@endsection