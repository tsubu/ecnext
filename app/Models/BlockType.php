<?php

namespace App\Models;

use App\Services\Blocks\BlockPluginLang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockType extends Model
{
    use HasFactory;

    protected $appends = [
        'display_name',
    ];

    protected $fillable = [
        'type_key',
        'name',
        'category',
        'schema',
        'icon',
        'is_system',
    ];

    protected $casts = [
        'schema' => 'json',
        'is_system' => 'boolean',
    ];

    /**
     * Get the presets for this block type.
     */
    public function presets()
    {
        return $this->hasMany(BlockPreset::class);
    }

    /**
     * Get the instances for this block type.
     */
    public function instances()
    {
        return $this->hasMany(BlockInstance::class);
    }

    /**
     * Admin / JSON: localized blueprint label for current UI locale.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->resolvedName();
    }

    /**
     * Folder lang/{locale}.json "name" → fallback DB name.
     */
    public function resolvedName(?string $locale = null): string
    {
        return BlockPluginLang::resolvedTypeName(
            (string) $this->type_key,
            (string) $this->name,
            $locale
        );
    }
}
