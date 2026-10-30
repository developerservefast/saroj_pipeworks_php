<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Size;
use App\Support\Html;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProductImportController extends Controller
{
    private const COLUMNS = [
        'name', 'category', 'subcategory', 'brands', 'sizes', 'price', 'discount_percent', 'unit',
        'stock_quantity', 'warranty', 'description', 'details', 'meta_description',
        'is_active', 'is_bestseller', 'is_trending', 'is_latest', 'variants',
    ];

    private const MAX_ROWS = 2000;

    public function create()
    {
        return view('admin.products.import', ['columns' => self::COLUMNS]);
    }

    public function template()
    {
        $book = new Spreadsheet();
        $sheet = $book->getActiveSheet();
        $sheet->setTitle('Products');
        $sheet->fromArray(self::COLUMNS, null, 'A1');
        $sheet->getStyle('A1:R1')->getFont()->setBold(true);
        $sheet->freezePane('A2');
        foreach (range('A', 'R') as $c) {
            $sheet->getColumnDimension($c)->setWidth(match ($c) {
                'A', 'B', 'C', 'D', 'K', 'L', 'M', 'R' => 28,
                default => 16,
            });
        }
        // Keep sizes like 3/4" as text so Excel does not turn them into dates.
        $sheet->getStyle('E2:E2001')->getNumberFormat()->setFormatCode('@');

        // Hidden sheet that holds the dropdown lists.
        $lists = $book->createSheet();
        $lists->setTitle('Lists');
        $str = fn (int $col, int $row, string $v) => $lists->setCellValueExplicit(
            Coordinate::stringFromColumnIndex($col) . $row, $v, DataType::TYPE_STRING
        );

        $cats = ProductCategory::whereNull('parent_id')->with('children')
            ->orderBy('sort_order')->orderBy('name')->take(100)->get();
        $brands = Brand::orderBy('name')->pluck('name');

        $str(1, 1, 'categories');
        foreach ($cats as $i => $c) {
            $str(1, $i + 2, $c->name);
            $str(5 + $i, 1, $c->name);                       // sub list header = parent name
            foreach ($c->children->take(50) as $j => $ch) {
                $str(5 + $i, $j + 2, $ch->name);
            }
        }
        $str(2, 1, 'brands');
        foreach ($brands as $i => $b) {
            $str(2, $i + 2, $b);
        }
        $lists->setSheetState(Worksheet::SHEETSTATE_HIDDEN);

        $make = function (string $formula, bool $strict, string $message) {
            $dv = new DataValidation();
            $dv->setType(DataValidation::TYPE_LIST);
            $dv->setErrorStyle($strict ? DataValidation::STYLE_STOP : DataValidation::STYLE_INFORMATION);
            $dv->setAllowBlank(true);
            $dv->setShowDropDown(true);
            $dv->setShowErrorMessage(true);
            $dv->setErrorTitle($strict ? 'Invalid value' : 'Not in the list');
            $dv->setError($message);
            $dv->setFormula1($formula);

            return $dv;
        };

        $catEnd = max(2, $cats->count() + 1);
        $brandEnd = max(2, $brands->count() + 1);
        $match = 'MATCH($B2,Lists!$E$1:$CZ$1,0)-1';
        $subFormula = "=OFFSET(Lists!\$E\$2,0,{$match},MAX(1,COUNTA(OFFSET(Lists!\$E\$2,0,{$match},50,1))),1)";
        $newMsg = 'Not in the list. It will be created as new on import. Continue?';
        $brandMsg = 'For several brands, separate them with | (example: Astral|Ashirvad). New brands are created on import. Continue?';

        $sheet->setDataValidation('B2:B2001', $make("=Lists!\$A\$2:\$A\${$catEnd}", false, $newMsg));
        $sheet->setDataValidation('C2:C2001', $make($subFormula, false, $newMsg));
        $sheet->setDataValidation('D2:D2001', $make("=Lists!\$B\$2:\$B\${$brandEnd}", false, $brandMsg));
        foreach (['N', 'O', 'P', 'Q'] as $col) {
            $sheet->setDataValidation("{$col}2:{$col}2001", $make('"yes,no"', true, 'Choose yes or no.'));
        }

        $book->setActiveSheetIndex(0);

        return response()->streamDownload(function () use ($book) {
            (new Xlsx($book))->save('php://output');
        }, 'product-import-template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(['file' => ['required', 'file', 'extensions:csv,txt,xlsx', 'max:2048']]);

        [$records, $problem] = $this->readFile($request->file('file'));
        if ($problem) {
            return back()->with('import_errors', [$problem]);
        }

        $errors = [];
        $clean = [];

        foreach ($records as $rowNo => $r) {
            [$data, $rowErrors] = $this->checkRow($r);
            if ($rowErrors) {
                foreach ($rowErrors as $e) {
                    $errors[] = "Row $rowNo: $e";
                }
            } else {
                $clean[$rowNo] = $data;
            }
        }

        if ($errors) {
            $errors = array_slice($errors, 0, 100);
            array_unshift($errors, 'Nothing was imported. Fix these rows and upload again:');

            return back()->with('import_errors', $errors);
        }

        $created = 0;
        $updated = 0;

        DB::transaction(function () use ($clean, &$created, &$updated) {
            foreach ($clean as $d) {
                $category = $this->resolveCategory($d['category'], $d['subcategory']);

                $product = Product::where('product_category_id', $category->id)
                    ->whereRaw('LOWER(name) = ?', [mb_strtolower($d['name'])])
                    ->first();
                $isNew = ! $product;

                $attrs = [
                    'price' => $d['price'],
                    'discount_percent' => $d['discount_percent'],
                    'unit' => $d['unit'],
                    'stock_quantity' => $d['stock_quantity'],
                    'warranty' => $d['warranty'],
                    'description' => $d['description'],
                    'details' => $d['details'],
                    'meta_description' => $d['meta_description'],
                    'is_active' => $d['is_active'],
                    'is_bestseller' => $d['is_bestseller'],
                    'is_trending' => $d['is_trending'],
                    'is_latest' => $d['is_latest'],
                ];

                if ($isNew) {
                    $product = Product::create($attrs + [
                        'product_category_id' => $category->id,
                        'name' => $d['name'],
                        'discount_percent' => $d['discount_percent'] ?? 0,
                        'is_active' => $d['is_active'] ?? true,
                        'is_bestseller' => $d['is_bestseller'] ?? false,
                        'is_trending' => $d['is_trending'] ?? false,
                        'is_latest' => $d['is_latest'] ?? false,
                    ]);
                    $created++;
                } else {
                    $product->update(array_filter($attrs, fn ($v) => $v !== null));
                    $updated++;
                }

                if ($d['brands']) {
                    $ids = collect($d['brands'])->map(fn ($n) => $this->resolveBrand($n)->id)->unique()->values()->all();
                    $product->brands()->sync($ids);
                    $product->update([
                        'brand_id' => $ids[0] ?? null,
                        'brand' => $product->brands()->orderBy('name')->pluck('name')->implode(', ') ?: null,
                    ]);
                }

                if ($d['sizes']) {
                    $ids = collect($d['sizes'])->map(fn ($n) => $this->resolveSize($n)->id)->unique()->all();
                    $product->sizes()->sync($ids);
                    $product->update(['size' => $product->sizes()->orderBy('name')->pluck('name')->implode(', ') ?: null]);
                }

                if ($d['variants']) {
                    foreach ($product->variants()->whereNotNull('image')->pluck('image') as $path) {
                        Storage::disk('public')->delete($path);
                    }
                    $product->variants()->delete();
                    foreach ($d['variants'] as $i => $v) {
                        $product->variants()->create($v + ['sort_order' => $i]);
                    }
                }
            }
        });

        return redirect()->route('admin.products.index')
            ->with('success', "Import finished: $created added, $updated updated.");
    }

    /** @return array{0: array<int, array<string,string>>, 1: ?string} */
    private function readFile(UploadedFile $file): array
    {
        if (strtolower($file->getClientOriginalExtension()) === 'xlsx') {
            try {
                $reader = IOFactory::createReader('Xlsx');
                $reader->setReadDataOnly(true);
                $book = $reader->load($file->getRealPath());
                $sheet = $book->getSheetByName('Products') ?? $book->getSheet(0);
                $grid = $sheet->toArray('', true, true, false);
            } catch (\Throwable $e) {
                return [[], 'Could not read the Excel file. Download the template again and re-upload.'];
            }

            return $this->gridToRecords($grid);
        }

        $raw = (string) file_get_contents($file->getRealPath());
        $raw = preg_replace('/^\xEF\xBB\xBF/', '', $raw);
        if (! mb_check_encoding($raw, 'UTF-8')) {
            $raw = mb_convert_encoding($raw, 'UTF-8', 'Windows-1252');
        }
        $firstLine = strtok($raw, "\n") ?: '';
        $delim = substr_count($firstLine, ';') > substr_count($firstLine, ',') ? ';' : ',';

        $h = fopen('php://temp', 'r+');
        fwrite($h, $raw);
        rewind($h);
        $grid = [];
        while (($cells = fgetcsv($h, 0, $delim, '"', '\\')) !== false) {
            $grid[] = $cells;
        }
        fclose($h);

        return $this->gridToRecords($grid);
    }

    /** @return array{0: array<int, array<string,string>>, 1: ?string} */
    private function gridToRecords(array $grid): array
    {
        $header = array_shift($grid);
        if (! $header) {
            return [[], 'The file is empty.'];
        }
        $header = array_map(function ($c) {
            $c = strtolower(trim((string) $c));

            return $c === 'brand' ? 'brands' : $c; // old files used "brand"
        }, $header);

        foreach (['name', 'category'] as $required) {
            if (! in_array($required, $header, true)) {
                return [[], "Missing required column: $required. Download the template to see the correct headers."];
            }
        }
        $unknown = array_diff(array_filter($header), self::COLUMNS);
        if ($unknown) {
            return [[], 'Unknown column(s): ' . implode(', ', $unknown) . '. Download the template to see the correct headers.'];
        }

        $records = [];
        $rowNo = 1;
        foreach ($grid as $cells) {
            $rowNo++;
            $cells = array_map(fn ($c) => trim((string) $c), $cells);
            if (count(array_filter($cells, fn ($c) => $c !== '')) === 0) {
                continue;
            }
            $row = [];
            foreach ($header as $i => $col) {
                if ($col !== '') {
                    $row[$col] = $cells[$i] ?? '';
                }
            }
            $records[$rowNo] = $row;
            if (count($records) > self::MAX_ROWS) {
                return [[], 'Too many rows. Import at most ' . self::MAX_ROWS . ' products per file.'];
            }
        }

        return $records ? [$records, null] : [[], 'The file has no product rows.'];
    }

    /** @return array{0: array<string,mixed>, 1: array<int,string>} */
    private function checkRow(array $r): array
    {
        $get = fn (string $k) => ($r[$k] ?? '') !== '' ? $r[$k] : null;
        $errors = [];

        $price = $get('price') !== null ? str_replace([',', '₹', ' '], '', $get('price')) : null;

        $v = Validator::make([
            'name' => $get('name'),
            'category' => $get('category'),
            'subcategory' => $get('subcategory'),
            'price' => $price,
            'discount_percent' => $get('discount_percent'),
            'unit' => $get('unit'),
            'stock_quantity' => $get('stock_quantity'),
            'warranty' => $get('warranty'),
            'meta_description' => $get('meta_description'),
        ], [
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'subcategory' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'discount_percent' => ['nullable', 'integer', 'min:0', 'max:100'],
            'unit' => ['nullable', 'string', 'max:50'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'warranty' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:320'],
        ]);
        foreach ($v->errors()->all() as $e) {
            $errors[] = $e;
        }

        $flags = [];
        foreach (['is_active', 'is_bestseller', 'is_trending', 'is_latest'] as $f) {
            $raw = strtolower($get($f) ?? '');
            if ($raw === '') {
                $flags[$f] = null;
            } elseif (in_array($raw, ['yes', 'y', '1', 'true'], true)) {
                $flags[$f] = true;
            } elseif (in_array($raw, ['no', 'n', '0', 'false'], true)) {
                $flags[$f] = false;
            } else {
                $flags[$f] = null;
                $errors[] = "$f must be yes or no.";
            }
        }

        $brands = array_values(array_filter(array_map('trim', explode('|', (string) $get('brands')))));
        foreach ($brands as $b) {
            if (mb_strlen($b) > 255) {
                $errors[] = 'A brand name is too long.';
            }
        }

        $variants = [];
        foreach (array_filter(array_map('trim', explode('|', (string) $get('variants')))) as $part) {
            [$vn, $vh, $vp] = array_pad(array_map('trim', explode(':', $part)), 3, '');
            if ($vn === '' || mb_strlen($vn) > 100) {
                $errors[] = "Variant \"$part\" needs a name (max 100 characters).";
                continue;
            }
            if ($vh !== '' && ! preg_match('/^#[0-9a-fA-F]{6}$/', $vh)) {
                $errors[] = "Variant \"$vn\": colour must look like #ff0000.";
                continue;
            }
            $vp = str_replace([',', '₹'], '', $vp);
            if ($vp !== '' && (! is_numeric($vp) || $vp < 0)) {
                $errors[] = "Variant \"$vn\": price must be a number.";
                continue;
            }
            $variants[] = ['name' => $vn, 'hex' => $vh ?: null, 'price' => $vp !== '' ? $vp : null];
        }

        $sizes = array_values(array_filter(array_map('trim', explode('|', (string) $get('sizes')))));
        foreach ($sizes as $s) {
            if (mb_strlen($s) > 255) {
                $errors[] = 'A size name is too long.';
            }
        }

        $data = [
            'name' => $get('name'),
            'category' => $get('category'),
            'subcategory' => $get('subcategory'),
            'brands' => $brands,
            'sizes' => $sizes,
            'price' => $price,
            'discount_percent' => $get('discount_percent') !== null ? (int) $get('discount_percent') : null,
            'unit' => $get('unit'),
            'stock_quantity' => $get('stock_quantity') !== null ? (int) $get('stock_quantity') : null,
            'warranty' => $get('warranty'),
            'description' => $this->toHtml($get('description')),
            'details' => $this->toHtml($get('details')),
            'meta_description' => $get('meta_description'),
            'variants' => $variants,
        ] + $flags;

        return [$data, $errors];
    }

    private function toHtml(?string $text): ?string
    {
        if ($text === null) {
            return null;
        }
        if (! str_contains($text, '<')) {
            $text = '<p>' . nl2br(e($text)) . '</p>';
        }

        return Html::clean($text);
    }

    private function resolveCategory(string $name, ?string $sub): ProductCategory
    {
        $parent = ProductCategory::whereNull('parent_id')->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])->first()
            ?? ProductCategory::create([
                'name' => $name, 'slug' => ProductCategory::makeSlug($name), 'is_active' => true,
                'sort_order' => (int) ProductCategory::whereNull('parent_id')->max('sort_order') + 1,
            ]);
        if (! $sub) {
            return $parent;
        }

        return ProductCategory::where('parent_id', $parent->id)->whereRaw('LOWER(name) = ?', [mb_strtolower($sub)])->first()
            ?? ProductCategory::create([
                'name' => $sub, 'slug' => ProductCategory::makeSlug($sub), 'parent_id' => $parent->id, 'is_active' => true,
                'sort_order' => (int) ProductCategory::where('parent_id', $parent->id)->max('sort_order') + 1,
            ]);
    }

    private function resolveBrand(string $name): Brand
    {
        return Brand::whereRaw('LOWER(name) = ?', [mb_strtolower($name)])->first()
            ?? Brand::create(['name' => $name, 'is_active' => true]);
    }

    private function resolveSize(string $name): Size
    {
        return Size::whereRaw('LOWER(name) = ?', [mb_strtolower($name)])->first()
            ?? Size::create(['name' => $name, 'is_active' => true]);
    }
}