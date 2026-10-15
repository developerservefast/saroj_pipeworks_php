@extends('admin.layouts.app')
@section('title', 'Sizes')

@section('content')
@if ($errors->any())
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('admin.sizes.store') }}" class="card mb-6 flex flex-col gap-3 p-5 sm:flex-row">
    @csrf
    <input name="name" required placeholder="New size (e.g. 3/4 inch, 110 mm)" class="input">
    <button class="btn-primary sm:w-40">Add Size</button>
</form>

<div class="card divide-y divide-gray-100">
@forelse ($sizes as $size)
    <div class="flex flex-wrap items-center gap-3 p-3">
        <form method="POST" action="{{ route('admin.sizes.update', $size) }}" class="flex flex-1 flex-wrap items-center gap-2">
            @csrf @method('PUT')
            <input name="name" value="{{ $size->name }}" class="input w-full sm:w-64">
            <label class="flex items-center gap-1 text-sm"><input type="checkbox" name="is_active" value="1" @checked($size->is_active)> Active</label>
            <button class="btn-outline">Save</button>
        </form>
        <span class="text-sm text-gray-500">{{ $size->products_count }} products</span>
        <form method="POST" action="{{ route('admin.sizes.destroy', $size) }}" onsubmit="return confirm('Delete this size?')">
            @csrf @method('DELETE')
            <button class="btn-danger">Delete</button>
        </form>
    </div>
@empty
    <p class="p-6 text-center text-gray-500">No sizes yet.</p>
@endforelse
</div>
@endsection