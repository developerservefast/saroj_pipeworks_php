@php $s = $service ?? null; @endphp
@csrf

<div class="grid gap-4">
    <div>
        <label class="block text-sm text-gray-600 mb-1">Service name *</label>
        <input type="text" name="name" value="{{ old('name', $s->name ?? '') }}" required
               class="w-full border border-gray-300 rounded-lg px-3 py-2">
        @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm text-gray-600 mb-1">Description</label>
        <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2">{{ old('description', $s->description ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm text-gray-600 mb-1">Display order (0 shows first)</label>
        <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $s->sort_order ?? 0) }}"
               class="w-32 border border-gray-300 rounded-lg px-3 py-2">
    </div>

    <div>
        <label class="block text-sm text-gray-600 mb-1">Image (max 2 MB)</label>
        @if ($s && $s->image)
            <img src="{{ asset('storage/'.$s->image) }}" class="h-20 w-20 rounded object-cover mb-2" alt="">
        @endif
        <input type="file" name="image" accept="image/*" class="text-sm">
        @error('image') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <label class="flex items-center gap-2 text-sm text-gray-700">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $s->is_active ?? true))>
        Show on website
    </label>
</div>

<div class="mt-6 flex gap-3">
    <button class="bg-sky-500 hover:bg-sky-600 text-white font-semibold px-5 py-2 rounded-lg">Save</button>
    <a href="{{ route('admin.services.index') }}" class="px-5 py-2 text-gray-600 hover:text-sky-600">Cancel</a>
</div>
