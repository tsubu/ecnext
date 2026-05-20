<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class Coupon extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'code',
        'name',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'starts_at',
        'expires_at',
        'usage_limit',
        'usage_count',
        'is_active',
        'user_id',
        'is_unique',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'is_unique' => 'boolean',
    ];

    /**
     * Check if the coupon is currently valid.
     */
    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        
        $now = now();
        if ($this->starts_at && $now->lt($this->starts_at)) return false;
        if ($this->expires_at && $now->gt($this->expires_at)) return false;
        
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) return false;
        
        return true;
    }

    /**
     * Get the user who owns the unique coupon.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
