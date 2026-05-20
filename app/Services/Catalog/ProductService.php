<?php

namespace App\Services\Catalog;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    /**
     * Get a product by its slug with all necessary relations for storefront display.
     *
     * @param string $slug
     * @return Product|null
     */
    public function getProductBySlug(string $slug): ?Product
    {
        return Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['variants', 'images', 'categories', 'tags'])
            ->first();
    }

    /**
     * Search and paginate active products.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function searchProducts(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        $query = Product::where('is_active', true)
            ->with(['defaultVariant', 'images']);

        if (!empty($filters['keyword'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['keyword'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['keyword'] . '%');
            });
        }

        if (!empty($filters['category_id'])) {
            $query->whereHas('categories', function ($q) use ($filters) {
                $q->where('categories.id', $filters['category_id']);
            });
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Resolve a variant by its ID and ensure it belongs to the correct product.
     *
     * @param int $productId
     * @param int $variantId
     * @return ProductVariant|null
     */
    public function resolveVariant(int $productId, int $variantId): ?ProductVariant
    {
        return ProductVariant::where('id', $variantId)
            ->where('product_id', $productId)
            ->first();
    }
}
