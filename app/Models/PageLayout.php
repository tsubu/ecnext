<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PageLayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'layoutable_id',
        'layoutable_type',
        'block_instance_id',
        'settings_override',
        'section_key',
        'sort_order',
        'starts_at',
        'expires_at',
    ];

    protected $casts = [
        'settings_override' => 'json',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the owning layoutable model (Page, Product, etc.).
     */
    public function layoutable()
    {
        return $this->morphTo();
    }

    /**
     * Get the block instance assigned in this layout.
     */
    public function blockInstance()
    {
        return $this->belongsTo(BlockInstance::class);
    }

    /**
     * Check if this layout is currently visible based on schedule.
     * - No dates set = permanent (always visible)
     * - starts_at only = visible from that date onward
     * - expires_at only = visible until that date
     * - Both set = visible only during the period
     */
    public function isCurrentlyVisible(): bool
    {
        $now = now();

        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }

        if ($this->expires_at && $now->gt($this->expires_at)) {
            return false;
        }

        return true;
    }

    /**
     * Scope: Only currently visible layouts (permanent + within schedule).
     */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNull('starts_at')
              ->orWhere('starts_at', '<=', now());
        })->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>=', now());
        });
    }

    /**
     * Check if this is a permanent (non-scheduled) layout.
     */
    public function isPermanent(): bool
    {
        return is_null($this->starts_at) && is_null($this->expires_at);
    }

    /**
     * Get a human-readable schedule label.
     */
    public function getScheduleLabelAttribute(): string
    {
        if ($this->isPermanent()) {
            return __('Permanent');
        }

        if ($this->starts_at && $this->expires_at) {
            return $this->starts_at->format('Y/m/d H:i') . ' ~ ' . $this->expires_at->format('Y/m/d H:i');
        }

        if ($this->starts_at) {
            return $this->starts_at->format('Y/m/d H:i') . ' ~';
        }

        return '~ ' . $this->expires_at->format('Y/m/d H:i');
    }
}
