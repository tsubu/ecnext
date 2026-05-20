<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'transaction_id',
        'amount',
        'status',
        'payment_method_key',
        'raw_response',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'raw_response' => 'json',
    ];

    /**
     * Get the order associated with the transaction record.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
