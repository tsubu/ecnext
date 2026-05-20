<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;

class Campaign extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'discount_type',
        'discount_value',
        'starts_at',
        'expires_at',
        'banner_path',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * The products that belong to the campaign.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
