<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected \App\Services\CmsService $cmsService;

    public function __construct(\App\Services\CmsService $cmsService)
    {
        $this->cmsService = $cmsService;
    }

    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Product::with(['defaultVariant', 'categories'])->latest()->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $tags = Tag::all();
        $taxRules = \App\Models\TaxRule::all();
        return view('admin.products.create', compact('categories', 'tags', 'taxRules'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:1000',
            'sku' => 'required|string|max:100|unique:product_variants,sku',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_ids' => 'nullable|array',
            'tag_ids' => 'nullable|array',
            'tax_rule_id' => 'nullable|exists:tax_rules,id',
            'individual_tax_rate' => 'nullable|numeric|min:0|max:100',
            'tax_type' => 'required|in:inherit,inclusive,exclusive',
            'images.*' => 'nullable|image|max:5120', // 5MB
        ]);

        return DB::transaction(function () use ($request, $validated) {
            // 1. Create Product
            $product = Product::create([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'],
                'short_description' => $validated['short_description'],
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'is_active' => $request->has('is_active'),
                'tax_rule_id' => $validated['tax_rule_id'],
                'individual_tax_rate' => $validated['individual_tax_rate'],
                'tax_type' => $validated['tax_type'],
            ]);

            // 2. Create Default Variant
            ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $validated['sku'],
                'price' => $validated['price'],
                'stock_quantity' => $validated['stock_quantity'],
                'is_default' => true,
            ]);

            // 3. Attach Categories & Tags
            if (!empty($validated['category_ids'])) {
                $product->categories()->attach($validated['category_ids']);
            }
            if (!empty($validated['tag_ids'])) {
                $product->tags()->attach($validated['tag_ids']);
            }

            // 4. Handle Scheduled Sales
            if ($request->has('sales')) {
                $salesData = collect($request->sales)->filter(fn($s) => !empty($s['starts_at']) && !empty($s['expires_at']));
                
                if ($this->hasOverlappingSales($salesData)) {
                    throw new \Illuminate\Validation\ValidationException(
                        \Validator::make([], []),
                        ['sales' => __('Sale periods must not overlap.')]
                    );
                }

                foreach ($salesData as $sale) {
                    $product->sales()->create([
                        'price' => $sale['price'],
                        'starts_at' => $sale['starts_at'],
                        'expires_at' => $sale['expires_at'],
                        'is_active' => isset($sale['is_active']),
                    ]);
                }
            }

            // 5. Handle Images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $path = $file->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'file_path' => $path,
                        'is_primary' => false,
                    ]);
                }
            }

            return redirect()->route('admin.products.index')->with('success', __('Product created successfully.'));
        });
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $product->load(['defaultVariant', 'categories', 'tags', 'images', 'sales', 'layouts.blockInstance.blockType']);
        $categories = Category::where('is_active', true)->get();
        $tags = Tag::all();
        
        $blockInstances = \App\Models\BlockInstance::with('blockType')->shared()->where('is_active', true)->get();
        $blockTypes = \App\Models\BlockType::all();
        $assignedLayouts = $product->layouts;
        $taxRules = \App\Models\TaxRule::all();

        return view('admin.products.edit', compact('product', 'categories', 'tags', 'blockInstances', 'blockTypes', 'assignedLayouts', 'taxRules'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:1000',
            'sku' => 'required|string|max:100|unique:product_variants,sku,' . $product->defaultVariant?->id,
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_ids' => 'nullable|array',
            'tag_ids' => 'nullable|array',
            'tax_rule_id' => 'nullable|exists:tax_rules,id',
            'individual_tax_rate' => 'nullable|numeric|min:0|max:100',
            'tax_type' => 'required|in:inherit,inclusive,exclusive',
            'images.*' => 'nullable|image|max:5120',
        ]);

        return DB::transaction(function () use ($request, $product, $validated) {
            // 1. Update Product
            $product->update([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'],
                'short_description' => $validated['short_description'],
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'is_active' => $request->has('is_active'),
                'tax_rule_id' => $validated['tax_rule_id'],
                'individual_tax_rate' => $validated['individual_tax_rate'],
                'tax_type' => $validated['tax_type'],
            ]);

            // 2. Update Default Variant
            $product->defaultVariant()->update([
                'sku' => $validated['sku'],
                'price' => $validated['price'],
                'stock_quantity' => $validated['stock_quantity'],
            ]);

            // 3. Sync Categories & Tags
            $product->categories()->sync($validated['category_ids'] ?? []);
            $product->tags()->sync($validated['tag_ids'] ?? []);

            // 4. Handle Scheduled Sales
            if ($request->has('sales')) {
                $salesData = collect($request->sales)->filter(fn($s) => !empty($s['starts_at']) && !empty($s['expires_at']));
                
                // Validate Overlaps
                if ($this->hasOverlappingSales($salesData)) {
                    throw new \Illuminate\Validation\ValidationException(
                        \Validator::make([], []),
                        ['sales' => __('Sale periods must not overlap.')]
                    );
                }

                $product->sales()->delete();
                foreach ($salesData as $sale) {
                    $product->sales()->create([
                        'price' => $sale['price'],
                        'starts_at' => $sale['starts_at'],
                        'expires_at' => $sale['expires_at'],
                        'is_active' => isset($sale['is_active']),
                    ]);
                }
            } else {
                $product->sales()->delete();
            }

            // 5. Handle New Images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $path = $file->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'file_path' => $path,
                        'is_primary' => false,
                    ]);
                }
            }

            // 6. Sync Layouts
            if ($request->has('layout')) {
                $this->cmsService->syncLayouts($product, $request->layout);
            }

            return redirect()->route('admin.products.index')->with('success', __('Product updated successfully.'));
        });
    }

    /**
     * Check if a collection of sales data has overlapping periods.
     */
    private function hasOverlappingSales($salesData): bool
    {
        $sorted = $salesData->sortBy('starts_at')->values();

        for ($i = 0; $i < $sorted->count() - 1; $i++) {
            $currentEnd = \Carbon\Carbon::parse($sorted[$i]['expires_at']);
            $nextStart = \Carbon\Carbon::parse($sorted[$i + 1]['starts_at']);

            if ($currentEnd->gt($nextStart)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Delete images from disk
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->file_path);
        }
        
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', __('Product deleted successfully.'));
    }
}
