<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewRewardSetting extends Model
{
    protected $fillable = [
        'min_rating',
        'reward_type',
        'reward_value',
        'reward_delay_days',
        'coupon_expiry_days',
        'is_active',
    ];

    protected $casts = [
        'reward_value' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
