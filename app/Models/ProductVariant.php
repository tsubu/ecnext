<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'compare_at_price',
        'stock_quantity',
        'weight',
        'option1_name',
        'option1_value',
        'option2_name',
        'option2_value',
        'option3_name',
        'option3_value',
        'is_default',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_default' => 'boolean',
    ];

    /**
     * Get the active price (considering campaign discounts).
     */
    public function getActivePriceAttribute()
    {
        $price = (float) $this->price;
        $now = now();

        // 1. Check for product-specific scheduled sales (Highest priority)
        $productSale = DB::table('product_sales')
            ->where('product_id', $this->product_id)
            ->where('is_active', true)
            ->where('starts_at', '<=', $now)
            ->where('expires_at', '>=', $now)
            ->orderBy('price', 'asc') // Use lowest price if multiple (though overlaps should be blocked)
            ->first();

        if ($productSale) {
            return round((float) $productSale->price, 2);
        }
        
        // 2. Check for active campaigns linked to the parent product
        $campaign = DB::table('campaigns')
            ->join('campaign_product', 'campaigns.id', '=', 'campaign_product.campaign_id')
            ->where('campaign_product.product_id', $this->product_id)
            ->where('campaigns.is_active', true)
            ->where(function ($query) use ($now) {
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($query) use ($now) {
                $query->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
            })
            ->select('discount_type', 'discount_value')
            ->orderBy('discount_value', 'desc') // Choose best discount if multiple
            ->first();

        if ($campaign) {
            if ($campaign->discount_type === 'percentage') {
                $price = $price * (1 - ($campaign->discount_value / 100));
            } else {
                $price = max(0, $price - $campaign->discount_value);
            }
        }

        return round($price, 2);
    }

    /**
     * Check if the variant is currently on sale.
     */
    public function getIsOnSaleAttribute(): bool
    {
        return $this->compare_at_price > $this->price || $this->active_price < $this->price;
    }

    /**
     * Get the product that the variant belongs to.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the images specifically associated with this variant.
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}
