<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_key',
        'name',
        'config',
        'is_active',
    ];

    protected $casts = [
        'config' => 'json',
        'is_active' => 'boolean',
    ];

    /**
     * Get the payment methods associated with this provider.
     */
    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
    }
}
