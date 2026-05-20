<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use App\Traits\Auditable;

class Page extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'page_category_id',
        'slug',
        'title',
        'type',
        'content',
        'legal_data',
        'meta_title',
        'meta_description',
        'is_system',
        'is_published',
        'published_at',
        'expired_at',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'is_published' => 'boolean',
        'legal_data' => 'array',
        'published_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    /**
     * Scope: Currently visible pages based on publishing status and scheduling.
     */
    public function scopeVisible(Builder $query)
    {
        return $query->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expired_at')
                  ->orWhere('expired_at', '>=', now());
            });
    }

    /**
     * Get the category this page belongs to.
     */
    public function category()
    {
        return $this->belongsTo(PageCategory::class, 'page_category_id');
    }

    /**
     * Get the dynamic block layouts for this page.
     */
    public function layouts()
    {
        return $this->morphMany(PageLayout::class, 'layoutable')->orderBy('sort_order');
    }
}
