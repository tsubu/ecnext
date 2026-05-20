<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class Notice extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'title',
        'content',
        'published_at',
        'expired_at',
        'is_active',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expired_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Scope: Currently visible notices based on status and scheduling.
     */
    public function scopeVisible($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expired_at')
                  ->orWhere('expired_at', '>=', now());
            });
    }
}
