@php $p = $product ?? null; @endphp
@csrf

<div class="grid gap-4 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="block text-sm text-gray-600 mb-1">Category *</label>
        <select name="product_category_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
            @foreach ($categories as $c)
                <option value="{{ $c->id }}" @selected(old('product_category_id', $p->product_category_id ?? '') == $c->id)>{{ $c->name }}</option>
            @endforeach
        </select>
        @error('product_category_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="sm:col-span-2">
        <label class="block text-sm text-gray-600 mb-1">Name *</label>
        <input type="text" name="name" value="{{ old('name', $p->name ?? '') }}" required
               class="w-full border border-gray-300 rounded-lg px-3 py-2">
        @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm text-gray-600 mb-1">Brand</label>
        <input type="text" name="brand" value="{{ old('brand', $p->brand ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
    </div>
    <div>
        <label class="block text-sm text-gray-600 mb-1">Size</label>
        <input type="text" name="size" value="{{ old('size', $p->size ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
    </div>
    <div>
        <label class="block text-sm text-gray-600 mb-1">Price (₹)</label>
        <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $p->price ?? '') }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2">
        @error('price') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm text-gray-600 mb-1">Unit (e.g. piece, meter)</label>
        <input type="text" name="unit" value="{{ old('unit', $p->unit ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
    </div>
    <div class="sm:col-span-2">
        <label class="block text-sm text-gray-600 mb-1">Warranty</label>
        <input type="text" name="warranty" value="{{ old('warranty', $p->warranty ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
    </div>
    <div class="sm:col-span-2">
        <label class="block text-sm text-gray-600 mb-1">Description</label>
        <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2">{{ old('description', $p->description ?? '') }}</textarea>
    </div>

    <div class="sm:col-span-2">
        <label class="block text-sm text-gray-600 mb-1">Image (max 2 MB)</label>
        @if ($p && $p->image)
            <img src="{{ asset('storage/'.$p->image) }}" class="h-20 w-20 rounded object-cover mb-2" alt="">
        @endif
        <input type="file" name="image" accept="image/*" class="text-sm">
        @error('image') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <label class="sm:col-span-2 flex items-center gap-2 text-sm text-gray-700">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $p->is_active ?? true))>
        Show on website
    </label>
</div>

<div class="mt-6 flex gap-3">
    <button class="bg-sky-500 hover:bg-sky-600 text-white font-semibold px-5 py-2 rounded-lg">Save</button>
    <a href="{{ route('admin.products.index') }}" class="px-5 py-2 text-gray-600 hover:text-sky-600">Cancel</a>
</div>
