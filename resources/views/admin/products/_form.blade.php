@php
    $p = $product ?? null;
    $sel = old('product_category_id', $p->product_category_id ?? null);
    $selCat = $sel ? \App\Models\ProductCategory::find($sel) : null;
    $parentSel = $selCat ? ($selCat->parent_id ?? $selCat->id) : null;
    $catMap = $categories->mapWithKeys(fn ($c) => [$c->id => $c->children->map(fn ($ch) => ['id' => $ch->id, 'name' => $ch->name])->values()]);
    $label = 'mb-1 block text-xs font-semibold uppercase text-gray-500';
    $existing = $p ? $p->images : collect();
    $variantRows = old('variants', $p ? $p->variants->map(fn ($v) => ['name' => $v->name, 'hex' => $v->hex, 'price' => $v->price, 'keep_image' => $v->image])->all() : []);
@endphp
@csrf

@if ($errors->any())
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</div>
@endif

<div class="grid gap-5 sm:grid-cols-3">
    <div>
        <label class="{{ $label }}">Category *</label>
        <select id="parentSel" required class="input">
            <option value="">Select Category</option>
            @foreach ($categories as $c)
                <option value="{{ $c->id }}" @selected($parentSel == $c->id)>{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="{{ $label }}">Sub Category *</label>
        <select name="product_category_id" id="subSel" required class="input"></select>
    </div>
    <div>
        <label class="{{ $label }}">Brand</label>
        <select name="brand_id" class="input">
            <option value="">No brand</option>
            @foreach ($brands as $b)
                <option value="{{ $b->id }}" @selected(old('brand_id', $p->brand_id ?? '') == $b->id)>{{ $b->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="sm:col-span-3">
        <label class="{{ $label }}">Product images (max 5, 2 MB each)</label>
        <div id="imgZone" class="rounded-xl border-2 border-dashed border-gray-300 p-4">
            <div id="imgGrid" class="flex flex-wrap gap-3">
                @foreach ($existing as $img)
                    <div class="img-tile relative h-24 w-24 cursor-move rounded-lg border bg-white" draggable="true" data-token="e{{ $img->id }}" data-id="{{ $img->id }}">
                        <img src="{{ asset('storage/'.$img->path) }}" class="h-full w-full rounded-lg object-cover" alt="" draggable="false">
                        <span class="absolute bottom-1 left-1 flex gap-1"><button type="button" class="mv rounded bg-black/60 px-1.5 text-xs text-white" data-d="-1">&#9664;</button><button type="button" class="mv rounded bg-black/60 px-1.5 text-xs text-white" data-d="1">&#9654;</button></span><span class="badge absolute left-1 top-1 hidden rounded bg-brand px-1.5 text-[10px] font-semibold text-white">Main</span>
                        <button type="button" class="rm absolute right-1 top-1 h-5 w-5 rounded-full bg-black/60 text-xs leading-5 text-white">&times;</button>
                    </div>
                @endforeach
            </div>
            <button type="button" id="imgAdd" class="btn-outline mt-3">+ Choose images</button>
            <span class="ml-2 text-xs text-gray-500">or drop files here. Drag tiles to change the order.</span>
            <input type="file" id="imgPicker" name="images[]" accept="image/*" multiple class="hidden">
        </div>
    </div>

    <div class="sm:col-span-3">
        <label class="{{ $label }}">Title *</label>
        <input type="text" name="name" value="{{ old('name', $p->name ?? '') }}" required class="input">
    </div>

    <div>
        <label class="{{ $label }}">Price (₹)</label>
        <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $p->price ?? '') }}" class="input">
        <p class="mt-1 text-xs text-gray-500">Leave empty to show "Contact for price".</p>
    </div>
    <div>
        <label class="{{ $label }}">Discount %</label>
        <input type="number" min="0" max="100" name="discount_percent" value="{{ old('discount_percent', $p->discount_percent ?? 0) }}" class="input">
    </div>
    <div>
        <label class="{{ $label }}">Unit (e.g. piece, meter)</label>
        <input type="text" name="unit" value="{{ old('unit', $p->unit ?? '') }}" class="input">
    </div>
    <div>
        <label class="{{ $label }}">Sizes (tick all that apply)</label>
        @php $selSizes = array_map('intval', old('size_ids', $p ? $p->sizes->pluck('id')->all() : [])); @endphp
        <div class="flex max-h-40 flex-wrap gap-x-4 gap-y-2 overflow-y-auto rounded-lg border border-gray-300 p-3 text-sm">
            @forelse ($sizes as $s)
                <label class="flex items-center gap-2"><input type="checkbox" name="size_ids[]" value="{{ $s->id }}" @checked(in_array($s->id, $selSizes, true))> {{ $s->name }}</label>
            @empty
                <span class="text-gray-500">No sizes yet. Add them on the Sizes page.</span>
            @endforelse
        </div>
    </div>
    <div class="sm:col-span-2">
        <label class="{{ $label }}">Warranty</label>
        <input type="text" name="warranty" value="{{ old('warranty', $p->warranty ?? '') }}" class="input">
    </div>

    <div>
        <label class="{{ $label }}">Stock quantity</label>
        <input type="number" min="0" step="1" name="stock_quantity" value="{{ old('stock_quantity', $p->stock_quantity ?? '') }}" class="input">
        <p class="mt-1 text-xs text-gray-500">Leave empty if you don't track stock.</p>
    </div>

    <div class="sm:col-span-3 rounded-xl border border-gray-200 p-4">
        <p class="font-semibold text-gray-800">Variants</p>
        <p class="mb-3 text-xs text-gray-500">Each variant (for example a colour or size) has its own name. Tick Shade to pick a swatch colour. Price and image are optional.</p>
        <div id="variants" class="space-y-3"></div>
        <button type="button" id="addVariant" class="btn-outline mt-3">+ Add Variant</button>
    </div>

    <div class="sm:col-span-3 flex flex-wrap gap-6 text-sm">
        <label class="flex items-center gap-2"><input type="checkbox" name="is_bestseller" value="1" @checked(old('is_bestseller', $p->is_bestseller ?? false))> Best Seller</label>
        <label class="flex items-center gap-2"><input type="checkbox" name="is_trending" value="1" @checked(old('is_trending', $p->is_trending ?? false))> Trending</label>
        <label class="flex items-center gap-2"><input type="checkbox" name="is_latest" value="1" @checked(old('is_latest', $p->is_latest ?? false))> Latest</label>
        <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $p->is_active ?? true))> Show on website</label>
    </div>

    <div class="sm:col-span-3">
        <label class="{{ $label }}">Product details</label>
        <textarea data-rich name="details" rows="3" class="input">{{ old('details', $p->details ?? '') }}</textarea>
    </div>
    <div class="sm:col-span-3">
        <label class="{{ $label }}">Description</label>
        <textarea data-rich name="description" rows="4" class="input">{{ old('description', $p->description ?? '') }}</textarea>
    </div>
    <div class="sm:col-span-3">
        <label class="{{ $label }}">Meta description (optional)</label>
        <textarea name="meta_description" rows="2" maxlength="320" class="input">{{ old('meta_description', $p->meta_description ?? '') }}</textarea>
        <p class="mt-1 text-xs text-gray-500">Shown in Google results. About 150 to 160 characters works best. Leave empty to use the description.</p>
    </div>
</div>

<div class="mt-6 flex gap-3">
    <button class="btn-primary">Save</button>
    <a href="{{ route('admin.products.index') }}" class="btn-outline">Cancel</a>
</div>

<script>
    // Category -> Sub Category
    const catMap = @json($catMap);
    const parentSel = document.getElementById('parentSel');
    const subSel = document.getElementById('subSel');
    const selected = @json($sel ? (int) $sel : null);

    function fillSubs() {
        const pid = parentSel.value;
        subSel.innerHTML = '';
        if (!pid) { subSel.innerHTML = '<option value="">Select Sub Category</option>'; return; }
        subSel.add(new Option('General (no sub category)', pid, false, selected == pid));
        (catMap[pid] || []).forEach(s => subSel.add(new Option(s.name, s.id, false, selected == s.id)));
    }
    parentSel.addEventListener('change', () => { fillSubs(); });
    fillSubs();

    // Variants
    const box = document.getElementById('variants');
    let vi = 0;
    function addVariant(d = {}) {
        const i = vi++;
        const row = document.createElement('div');
        row.className = 'rounded-lg border border-gray-200 p-3';
        const hasHex = !!d.hex;
        row.innerHTML = `
            <div class="flex flex-wrap items-center gap-2">
                <input name="variants[${i}][name]" placeholder="Variant name" value="${(d.name || '').replace(/"/g, '&quot;')}" class="input w-full sm:w-48">
                <label class="flex items-center gap-1 text-sm"><input type="checkbox" class="shade" ${hasHex ? 'checked' : ''}> Shade</label>
                <input type="color" name="variants[${i}][hex]" value="${d.hex || '#cccccc'}" class="h-10 w-12 rounded" ${hasHex ? '' : 'disabled'}>
                <input type="number" step="0.01" min="0" name="variants[${i}][price]" placeholder="Price" value="${d.price ?? ''}" class="input w-full sm:w-28">
                <button type="button" class="btn-danger ml-auto rm">Remove</button>
            </div>
            <div class="mt-2 flex items-center gap-3 text-xs text-gray-500">
                ${d.keep_image ? `<img src="/storage/${d.keep_image}" class="h-12 w-12 rounded border object-cover" alt=""><input type="hidden" name="variants[${i}][keep_image]" value="${d.keep_image}">` : ''}
                <span>Image (optional)</span>
                <input type="file" name="variants[${i}][image]" accept="image/*" class="file-input">
            </div>`;
        const shade = row.querySelector('.shade'), color = row.querySelector('input[type=color]');
        shade.onchange = () => color.disabled = !shade.checked;
        row.querySelector('.rm').onclick = () => row.remove();
        box.appendChild(row);
    }
    document.getElementById('addVariant').onclick = () => addVariant();
    @json($variantRows).forEach(v => addVariant(v));

// Image gallery: add, remove, drag to reorder
(function () {
    const MAX = 5, grid = document.getElementById('imgGrid'), picker = document.getElementById('imgPicker');
    const zone = document.getElementById('imgZone'), form = picker.form;
    const newFiles = new Map(); let nid = 0, dragged = null;

    const refresh = () => {
        [...grid.children].forEach((t, i) => t.querySelector('.badge').classList.toggle('hidden', i !== 0));
        document.getElementById('imgAdd').disabled = grid.children.length >= MAX;
    };

    function wire(tile) {
        tile.addEventListener('dragstart', e => { dragged = tile; e.dataTransfer.effectAllowed = 'move'; e.dataTransfer.setData('text/plain', 'tile'); tile.classList.add('opacity-40'); });
        tile.addEventListener('dragend', () => { tile.classList.remove('opacity-40'); dragged = null; refresh(); });
        tile.addEventListener('dragover', e => {
            if (!dragged || dragged === tile) return;
            e.preventDefault();
            const r = tile.getBoundingClientRect();
            grid.insertBefore(dragged, e.clientX < r.left + r.width / 2 ? tile : tile.nextSibling);
        });
        tile.querySelectorAll('.mv').forEach(b => b.onclick = () => { const d = +b.dataset.d; const sib = d < 0 ? tile.previousElementSibling : tile.nextElementSibling; if (!sib) return; if (d < 0) grid.insertBefore(tile, sib); else grid.insertBefore(sib, tile); refresh(); });
        tile.querySelector('.rm').onclick = () => {
            if (tile.dataset.id) {
                const h = document.createElement('input');
                h.type = 'hidden'; h.name = 'remove_images[]'; h.value = tile.dataset.id; form.appendChild(h);
            } else newFiles.delete(tile.dataset.token);
            tile.remove(); refresh();
        };
    }

    function addFiles(list) {
        [...list].filter(f => f.type.startsWith('image/')).forEach(f => {
            if (grid.children.length >= MAX) return;
            const token = 'f' + (nid++); newFiles.set(token, f);
            const tile = document.createElement('div');
            tile.className = 'img-tile relative h-24 w-24 cursor-move rounded-lg border bg-white';
            tile.draggable = true; tile.dataset.token = token;
            tile.innerHTML = `<img src="${URL.createObjectURL(f)}" class="h-full w-full rounded-lg object-cover" alt="" draggable="false">
                <span class="absolute bottom-1 left-1 flex gap-1"><button type="button" class="mv rounded bg-black/60 px-1.5 text-xs text-white" data-d="-1">&#9664;</button><button type="button" class="mv rounded bg-black/60 px-1.5 text-xs text-white" data-d="1">&#9654;</button></span><span class="badge absolute left-1 top-1 hidden rounded bg-brand px-1.5 text-[10px] font-semibold text-white">Main</span>
                <button type="button" class="rm absolute right-1 top-1 h-5 w-5 rounded-full bg-black/60 text-xs leading-5 text-white">&times;</button>`;
            grid.appendChild(tile); wire(tile);
        });
        picker.value = ''; refresh();
    }

    grid.querySelectorAll('.img-tile').forEach(wire);
    document.getElementById('imgAdd').onclick = () => picker.click();
    picker.onchange = () => addFiles(picker.files);
    zone.addEventListener('dragover', e => { if (e.dataTransfer.types.includes('Files')) e.preventDefault(); });
    zone.addEventListener('drop', e => { if (e.dataTransfer.files.length) { e.preventDefault(); addFiles(e.dataTransfer.files); } });

    form.addEventListener('submit', () => {
        const dt = new DataTransfer();
        [...grid.children].forEach(t => {
            const h = document.createElement('input'); h.type = 'hidden'; h.name = 'order[]';
            if (t.dataset.id) h.value = 'e' + t.dataset.id;
            else { h.value = 'n' + dt.files.length; dt.items.add(newFiles.get(t.dataset.token)); }
            form.appendChild(h);
        });
        picker.files = dt.files;
    });
    refresh();
})();
</script>
