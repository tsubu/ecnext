<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_variant_id',
        'product_name',
        'variant_sku',
        'quantity',
        'price',
        'tax',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'tax' => 'decimal:2',
    ];

    /**
     * Get the order that the item belongs to.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the current variant associated with the item (if not deleted).
     */
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
