<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class Order extends Model
{
    use HasFactory, Auditable, SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        'total_price',
        'item_total',
        'shipping_fee',
        'payment_fee',
        'tax',
        'status',
        'payment_method_id',
        'shipping_method_id',
        'notes',
        'coupon_id',
        'coupon_discount',
        'points_used',
        'points_discount',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'item_total' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'payment_fee' => 'decimal:2',
        'tax' => 'decimal:2',
        'coupon_discount' => 'decimal:2',
        'points_used' => 'decimal:2',
        'points_discount' => 'decimal:2',
    ];

    /**
     * Get the customer (User) who placed the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items in the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the addresses (shipping/billing) for the order.
     */
    public function addresses()
    {
        return $this->hasMany(OrderAddress::class);
    }

    /**
     * Get the shipping address specifically.
     */
    public function shippingAddress()
    {
        return $this->hasOne(OrderAddress::class)->where('type', 'shipping');
    }

    /**
     * Get the shipments for the order.
     */
    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    /**
     * Get the payment transactions for the order.
     */
    public function transactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    /**
     * Get the payment method used.
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * Get the coupon used for the order.
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Get the shipping method used.
     */
    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }
}
