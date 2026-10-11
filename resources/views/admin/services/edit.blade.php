@extends('admin.layouts.app')
@section('title', 'Edit Service')
@section('content')
    <h2 class="text-2xl font-bold text-sky-700 mb-4">Edit Service</h2>
    <form method="POST" action="{{ route('admin.services.update', $service) }}" enctype="multipart/form-data"
          class="bg-white rounded-xl shadow p-6 max-w-2xl">
        @method('PUT')
        @include('admin.services._form')
    </form>
@endsection
