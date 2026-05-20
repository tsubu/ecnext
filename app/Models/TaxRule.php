<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxRule extends Model
{
    protected $fillable = [
        'name',
        'rate',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Scope for active rules based on current time.
     */
    public function scopeActive($query)
    {
        $now = \Illuminate\Support\Carbon::now();
        return $query->where('is_active', true)
            ->where('starts_at', '<=', $now)
            ->where(function ($q) use ($now) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
            })
            ->orderBy('starts_at', 'desc'); // Most recent rule wins
    }
}
