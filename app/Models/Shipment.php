<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'carrier',
        'tracking_number',
        'shipped_at',
        'status',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
    ];

    /**
     * Get the order associated with the shipment.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
