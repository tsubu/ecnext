<?php

namespace App\Support;

use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Collection;

final class SalesActivityBlockData
{
    /**
     * @return array{scope: string, hours: int, style: string, title: ?string, totalQuantity: int, recentItems: Collection<int, OrderItem>}
     */
    public static function resolve(
        array $settings,
        ?Product $contextProduct = null,
        ?Category $contextCategory = null
    ): array {
        $scope = $settings['scope'] ?? 'store';
        $hours = max(1, (int) ($settings['hours'] ?? 24));
        $style = $settings['style'] ?? 'card';
        $title = isset($settings['title']) && $settings['title'] !== '' ? $settings['title'] : null;
        $categoryId = $settings['category_id'] ?? null;
        $productId = $settings['product_id'] ?? null;

        if ($scope === 'product' && $productId === null && $contextProduct) {
            $productId = $contextProduct->id;
        }
        if ($scope === 'category' && $categoryId === null && $contextCategory) {
            $categoryId = $contextCategory->id;
        }

        if ($scope === 'product' && $productId === null) {
            $routeProduct = request()->route('product');
            if ($routeProduct instanceof Product) {
                $productId = $routeProduct->id;
            }
        }

        if ($scope === 'category' && $categoryId === null) {
            $routeCategory = request()->route('category');
            if ($routeCategory instanceof Category) {
                $categoryId = $routeCategory->id;
            }
        }

        if ($scope === 'category' && ! $categoryId) {
            return self::payload($scope, $hours, $style, $title, 0, collect());
        }

        $since = Carbon::now()->subHours($hours);

        $base = OrderItem::query()
            ->where('order_items.created_at', '>=', $since)
            ->whereHas('order', function ($q) {
                $q->whereNotIn('status', ['cancelled', 'canceled', 'refunded']);
            })
            ->whereNotNull('product_variant_id');

        if ($scope === 'category' && $categoryId) {
            $base->whereHas('variant.product', function ($q) use ($categoryId) {
                $q->whereHas('categories', function ($c) use ($categoryId) {
                    $c->where('categories.id', $categoryId);
                });
            });
        } elseif ($scope === 'product' && $productId) {
            $base->whereHas('variant', function ($q) use ($productId) {
                $q->where('product_id', $productId);
            });
        }

        $totalQuantity = (int) (clone $base)->sum('quantity');

        $recentItems = collect();
        if ($totalQuantity > 0 && in_array($style, ['ticker', 'card'], true)) {
            $recentItems = (clone $base)
                ->with(['variant.product.images'])
                ->latest('order_items.created_at')
                ->limit(8)
                ->get();
        }

        return self::payload($scope, $hours, $style, $title, $totalQuantity, $recentItems);
    }

    /**
     * @param  Collection<int, OrderItem>  $recentItems
     */
    private static function payload(
        string $scope,
        int $hours,
        string $style,
        ?string $title,
        int $totalQuantity,
        Collection $recentItems
    ): array {
        return [
            'scope' => $scope,
            'hours' => $hours,
            'style' => $style,
            'title' => $title,
            'totalQuantity' => $totalQuantity,
            'recentItems' => $recentItems,
        ];
    }
}
