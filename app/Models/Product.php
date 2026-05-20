<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class Product extends Model
{
    use HasFactory, Auditable, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'is_active',
        'meta_title',
        'meta_description',
        'tax_rule_id',
        'individual_tax_rate',
        'tax_type',
    ];

    /**
     * Get the active tax rule associated with the product.
     */
    public function taxRule()
    {
        return $this->belongsTo(TaxRule::class);
    }
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the variants (SKUs) for the product.
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Get the default variant.
     */
    public function defaultVariant()
    {
        return $this->hasOne(ProductVariant::class)->where('is_default', true);
    }

    /**
     * Get the images for the product.
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    /**
     * Get the categories associated with the product.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    /**
     * Get the tags associated with the product.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags');
    }

    /**
     * Get the metafields for the product (Polymorphic).
     */
    public function metafields()
    {
        return $this->morphMany(Metafield::class, 'metafieldable');
    }

    /**
     * Get the scheduled sales for the product.
     */
    public function sales()
    {
        return $this->hasMany(ProductSale::class)->orderBy('starts_at');
    }

    /**
     * Get the order items associated with this product through its variants.
     */
    public function orderItems()
    {
        return $this->hasManyThrough(OrderItem::class, ProductVariant::class);
    }

    /**
     * Get the dynamic block layouts for this product.
     */
    public function layouts()
    {
        return $this->morphMany(PageLayout::class, 'layoutable')->orderBy('sort_order');
    }
}
