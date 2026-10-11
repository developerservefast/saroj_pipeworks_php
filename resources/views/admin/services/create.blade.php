@extends('admin.layouts.app')
@section('title', 'Add Service')
@section('content')
    <h2 class="text-2xl font-bold text-sky-700 mb-4">Add Service</h2>
    <form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data"
          class="bg-white rounded-xl shadow p-6 max-w-2xl">
        @include('admin.services._form')
    </form>
@endsection
