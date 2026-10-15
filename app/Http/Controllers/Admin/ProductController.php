<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Size;
use App\Support\Html;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $sorts = ['newest' => ['id', 'desc'], 'oldest' => ['id', 'asc'], 'name' => ['name', 'asc'], 'category' => ['product_category_id', 'asc']];
        [$col, $dir] = $sorts[$request->sort] ?? $sorts['newest'];

        $products = Product::with(['category.parent', 'brandRecord'])
            ->when($request->q, fn ($query, $term) => $query->where('name', 'like', "%{$term}%"))
            ->when($request->category, function ($query, $id) {
                $ids = ProductCategory::where('parent_id', $id)->pluck('id')->push((int) $id);
                $query->whereIn('product_category_id', $ids);
            })
            ->when($request->brand, fn ($query, $id) => $query->where('brand_id', $id))
            ->when($request->status === 'active', fn ($query) => $query->where('is_active', true))
            ->when($request->status === 'hidden', fn ($query) => $query->where('is_active', false))
            ->when(in_array($request->badge, ['is_bestseller', 'is_trending', 'is_latest'], true), fn ($query) => $query->where($request->badge, true))
            ->orderBy($col, $dir)
            ->paginate(20)->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => $this->categoryTree(),
            'brands' => Brand::orderBy('name')->get(),
        ]);
    }

    public function duplicate(Product $product)
    {
        $product->load(['images', 'variants', 'sizes']);
        $disk = Storage::disk('public');
        $copyFile = function (?string $path, string $dir) use ($disk) {
            if (! $path || ! $disk->exists($path)) return null;
            $new = $dir . '/' . \Illuminate\Support\Str::random(40) . '.' . pathinfo($path, PATHINFO_EXTENSION);
            $disk->copy($path, $new);
            return $new;
        };

        $copy = $product->replicate();
        $copy->name = $product->name . ' (copy)';
        $copy->is_active = false;
        $copy->image = null;
        $copy->save();

        foreach ($product->images as $img) {
            if ($new = $copyFile($img->path, 'products')) {
                $copy->images()->create(['path' => $new, 'sort_order' => $img->sort_order]);
            }
        }
        $copy->update(['image' => $copy->images()->value('path')]);

        foreach ($product->variants as $v) {
            $copy->variants()->create([
                'name' => $v->name, 'hex' => $v->hex, 'price' => $v->price,
                'image' => $copyFile($v->image, 'variants'), 'sort_order' => $v->sort_order,
            ]);
        }
        $copy->sizes()->sync($product->sizes->pluck('id')->all());

        return redirect()->route('admin.products.edit', $copy)->with('success', 'Product duplicated (hidden). Edit it, then tick "Show on website".');
    }

    public function bulk(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
            'action' => ['required', 'in:show,hide,delete,move'],
            'target_category' => ['nullable', 'exists:product_categories,id'],
        ], ['ids.required' => 'Tick at least one product first.']);

        $q = Product::whereIn('id', $request->ids);
        $n = (clone $q)->count();

        switch ($request->action) {
            case 'show': $q->update(['is_active' => true]); break;
            case 'hide': $q->update(['is_active' => false]); break;
            case 'move':
                if (! $request->target_category) {
                    return back()->with('error', 'Choose the category to move to.');
                }
                $q->update(['product_category_id' => $request->target_category]);
                break;
            case 'delete':
                foreach ($q->with(['images', 'variants'])->get() as $p) {
                    foreach ($p->images as $img) Storage::disk('public')->delete($img->path);
                    foreach ($p->variants as $v) if ($v->image) Storage::disk('public')->delete($v->image);
                    $p->delete();
                }
                break;
        }

        return back()->with('success', "$n product(s) updated.");
    }

    public function create()
    {
        return view('admin.products.create', $this->formData());
    }

    public function store(Request $request)
    {
        $product = Product::create($this->validated($request));
        $this->saveImages($product, $request);
        $this->saveVariants($product, $request);
        $this->syncSizes($product, $request);

        return redirect()->route('admin.products.index')->with('success', 'Product added.');
    }

    public function edit(Product $product)
    {
        $product->load(['images', 'variants', 'sizes']);

        return view('admin.products.edit', $this->formData() + ['product' => $product]);
    }

    public function update(Request $request, Product $product)
    {
        $product->update($this->validated($request));
        $this->saveImages($product, $request);
        $this->saveVariants($product, $request);
        $this->syncSizes($product, $request);

        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $img) Storage::disk('public')->delete($img->path);
        foreach ($product->variants as $v) if ($v->image) Storage::disk('public')->delete($v->image);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }

    private function categoryTree()
    {
        return ProductCategory::whereNull('parent_id')->with('children')
            ->orderBy('sort_order')->orderBy('name')->get();
    }

    private function formData(): array
    {
        return [
            'categories' => $this->categoryTree(),
            'brands' => Brand::where('is_active', true)->orderBy('name')->get(),
            'sizes' => Size::where('is_active', true)->orderBy('name')->get(),
        ];
    }

    private function syncSizes(Product $product, Request $request): void
    {
        $ids = array_map('intval', $request->input('size_ids', []));
        $product->sizes()->sync($ids);
        $product->update(['size' => $product->sizes()->orderBy('name')->pluck('name')->implode(', ') ?: null]);
    }

    private function saveImages(Product $product, Request $request): void
    {
        $remove = array_map('intval', $request->input('remove_images', []));
        foreach ($product->images()->whereIn('id', $remove)->get() as $img) {
            Storage::disk('public')->delete($img->path);
            $img->delete();
        }

        $existing = $product->images()->get()->keyBy('id');
        $files = array_values(array_filter($request->file('images', [])));
        $pos = 0; $usedIds = []; $usedFiles = [];

        foreach ($request->input('order', []) as $token) {
            $i = (int) substr($token, 1);
            if (str_starts_with($token, 'e') && $existing->has($i)) {
                $existing[$i]->update(['sort_order' => $pos++]);
                $usedIds[] = $i;
            } elseif (str_starts_with($token, 'n') && isset($files[$i]) && $existing->count() + count($usedFiles) < 5) {
                $product->images()->create(['path' => \App\Support\Img::store($files[$i], 'products'), 'sort_order' => $pos++]);
                $usedFiles[] = $i;
            }
        }

        foreach ($existing as $id => $img) {
            if (! in_array($id, $usedIds, true)) $img->update(['sort_order' => $pos++]);
        }

        $product->update(['image' => $product->images()->value('path')]);
    }

    private function saveVariants(Product $product, Request $request): void
    {
        $oldImages = $product->variants()->pluck('image')->filter()->all();
        $kept = [];
        $product->variants()->delete();

        $order = 0;
        foreach ($request->input('variants', []) as $i => $v) {
            if (blank($v['name'] ?? null)) continue;

            $image = null;
            if ($request->hasFile("variants.$i.image")) {
                $image = \App\Support\Img::store($request->file("variants.$i.image"), 'variants');
            } elseif (! empty($v['keep_image']) && in_array($v['keep_image'], $oldImages, true)) {
                $image = $v['keep_image'];
                $kept[] = $image;
            }

            $product->variants()->create([
                'name' => $v['name'],
                'hex' => $v['hex'] ?? null,
                'price' => ($v['price'] ?? '') !== '' ? $v['price'] : null,
                'image' => $image,
                'sort_order' => $order++,
            ]);
        }

        foreach (array_diff($oldImages, $kept) as $path) Storage::disk('public')->delete($path);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'product_category_id' => ['required', 'exists:product_categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'details' => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string', 'max:320'],
            'size_ids' => ['nullable', 'array'],
            'size_ids.*' => ['exists:sizes,id'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'discount_percent' => ['nullable', 'integer', 'min:0', 'max:100'],
            'unit' => ['nullable', 'string', 'max:50'],
            'warranty' => ['nullable', 'string', 'max:255'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'max:2048'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['integer'],
            'variants' => ['nullable', 'array'],
            'variants.*.name' => ['nullable', 'string', 'max:100'],
            'variants.*.hex' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'variants.*.price' => ['nullable', 'numeric', 'min:0'],
            'variants.*.image' => ['nullable', 'image', 'max:2048'],
            'variants.*.keep_image' => ['nullable', 'string'],
        ]);

        $data['description'] = Html::clean($data['description'] ?? null);
        $data['details'] = Html::clean($data['details'] ?? null);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_bestseller'] = $request->boolean('is_bestseller');
        $data['is_trending'] = $request->boolean('is_trending');
        $data['is_latest'] = $request->boolean('is_latest');
        $data['discount_percent'] = (int) ($data['discount_percent'] ?? 0);
        unset($data['images'], $data['remove_images'], $data['variants'], $data['size_ids']);

        $data['brand'] = ! empty($data['brand_id']) ? Brand::whereKey($data['brand_id'])->value('name') : null;

        return $data;
    }
}