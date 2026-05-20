<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopSetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'shop_name',
        'company_name',
        'email',
        'phone',
        'postal_code',
        'address1',
        'address2',
        'timezone',
        'country_code',
        'currency_code',
        'tax_rate',
        'global_tax_type',
        'default_shipping_fee',
        'logo_path',
        'points_enabled',
        'point_rate',
        'point_conversion_rate',
        'trade_law_manager',
        'trade_law_address',
        'trade_law_tel',
        'trade_law_email',
        'trade_law_price_info',
        'trade_law_payment_methods',
        'trade_law_delivery_info',
        'trade_law_return_policy',
    ];
}
