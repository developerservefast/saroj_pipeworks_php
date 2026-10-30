@extends('admin.layouts.app')
@section('title', 'Contact Messages')
@section('content')
<a href="{{ route('admin.contact-messages.index') }}" class="text-sm text-sky-600 hover:underline">&larr; Back to messages</a>
<div class="mt-3 max-w-2xl rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
    <h1 class="text-xl font-bold text-gray-800">{{ $msg->subject ?: 'Message from ' . $msg->name }}</h1>
    <p class="mt-1 text-xs text-gray-500">{{ $msg->created_at->format('d M Y, h:i A') }}</p>
    <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-2">
        <div><dt class="text-gray-500">Name</dt><dd class="font-semibold">{{ $msg->name }}</dd></div>
        <div><dt class="text-gray-500">Phone</dt><dd class="font-semibold"><a href="tel:{{ $msg->phone }}" class="text-sky-600 hover:underline">{{ $msg->phone }}</a></dd></div>
        @if ($msg->email)<div><dt class="text-gray-500">Email</dt><dd class="font-semibold"><a href="mailto:{{ $msg->email }}" class="text-sky-600 hover:underline">{{ $msg->email }}</a></dd></div>@endif
    </dl>
    <p class="mt-5 whitespace-pre-line rounded-lg bg-gray-50 p-4 text-sm text-gray-800">{{ $msg->message }}</p>
    <form method="POST" action="{{ route('admin.contact-messages.destroy', $msg) }}" class="mt-5" onsubmit="return confirm('Delete this message?')">
        @csrf @method('DELETE')
        <button class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Delete</button>
    </form>
</div>
@endsection