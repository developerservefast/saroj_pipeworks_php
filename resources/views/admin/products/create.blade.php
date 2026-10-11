@extends('admin.layouts.app')
@section('title', 'Add Product')
@section('content')
    <h2 class="text-2xl font-bold text-sky-700 mb-4">Add Product</h2>
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data"
          class="bg-white rounded-xl shadow p-6 max-w-3xl">
        @include('admin.products._form')
    </form>
@endsection
