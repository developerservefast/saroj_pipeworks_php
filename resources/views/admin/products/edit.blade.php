@extends('admin.layouts.app')
@section('title', 'Edit Product')
@section('content')
    <h2 class="text-2xl font-bold text-sky-700 mb-4">Edit Product</h2>
    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data"
          class="bg-white rounded-xl shadow p-6 max-w-3xl">
        @method('PUT')
        @include('admin.products._form')
    </form>
@endsection
