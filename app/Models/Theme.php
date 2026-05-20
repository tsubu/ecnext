<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'theme_key',
        'preview_image',
        'is_active',
        'settings',
        'languages',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'json',
        'languages' => 'array',
    ];

    /**
     * Scope a query to only include active themes.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the currently active theme.
     */
    public static function current()
    {
        return self::where('is_active', true)->first();
    }
}
