<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_provider_id',
        'name',
        'fee',
        'is_active',
    ];

    protected $casts = [
        'fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the provider associated with the method.
     */
    public function provider()
    {
        return $this->belongsTo(PaymentProvider::class, 'payment_provider_id');
    }

    /**
     * Get the orders associated with this payment method.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
