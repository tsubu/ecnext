<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSale extends Model
{
    protected $fillable = [
        'product_id',
        'price',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the product that the sale belongs to.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
