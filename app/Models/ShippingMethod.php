<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'base_fee',
        'is_active',
    ];

    protected $casts = [
        'base_fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the orders associated with this shipping method.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
