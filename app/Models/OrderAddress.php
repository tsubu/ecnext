<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'type',
        'first_name',
        'last_name',
        'email',
        'phone',
        'user_zip',
        'user_address1',
        'user_address2',
        'user_country',
        'user_pref',
    ];

    /**
     * Get the order associated with the address record.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
