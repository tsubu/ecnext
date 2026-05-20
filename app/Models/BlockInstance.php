<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockInstance extends Model
{
    use HasFactory;

    protected $fillable = [
        'block_type_id',
        'name',
        'name_locales',
        'slug',
        'settings',
        'settings_locales',
        'is_active',
        'is_shared',
        'owner_id',
        'owner_type',
    ];

    protected $casts = [
        'settings' => 'json',
        'name_locales' => 'json',
        'settings_locales' => 'json',
        'is_active' => 'boolean',
        'is_shared' => 'boolean',
    ];

    protected $appends = [
        'display_name',
    ];

    /**
     * Admin / JSON: label for current UI locale.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->resolvedName();
    }

    /**
     * Resolve block title for a locale (name_locales → fallback name).
     */
    public function resolvedName(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $map = $this->name_locales;
        if (is_array($map) && $map !== []) {
            $name = $this->resolveLocaleString($map, $locale);
            if ($name !== null && $name !== '') {
                return $name;
            }
            $fb = config('app.fallback_locale');
            if ($fb) {
                $name = $this->resolveLocaleString($map, (string) $fb);
                if ($name !== null && $name !== '') {
                    return $name;
                }
            }
        }

        return (string) $this->name;
    }

    /**
     * Merge default settings with locale-specific overrides (settings_locales[locale]).
     */
    public function resolvedSettings(?string $locale = null): array
    {
        $locale = $locale ?? app()->getLocale();
        $base = is_array($this->settings) ? $this->settings : [];
        $locales = $this->settings_locales;
        if (! is_array($locales) || $locales === []) {
            return $base;
        }

        $overlay = $this->resolveLocaleSubarray($locales, $locale);
        if ($overlay === []) {
            $fb = config('app.fallback_locale');
            if ($fb && (string) $fb !== '') {
                $overlay = $this->resolveLocaleSubarray($locales, (string) $fb);
            }
        }

        return array_replace_recursive($base, $overlay);
    }

    /**
     * @param  array<string, mixed>  $map  locale code => localized string
     */
    private function resolveLocaleString(array $map, string $locale): ?string
    {
        $v = $this->pickRawLocaleValue($map, $locale);

        return is_string($v) && $v !== '' ? $v : null;
    }

    /**
     * @param  array<string, mixed>  $map  locale code => settings partial array
     * @return array<string, mixed>
     */
    private function resolveLocaleSubarray(array $map, string $locale): array
    {
        $v = $this->pickRawLocaleValue($map, $locale);

        return is_array($v) ? $v : [];
    }

    /**
     * Match stored locale keys to the active locale (exact, BCP47-style, primary subtag).
     *
     * @param  array<string, mixed>  $map
     */
    private function pickRawLocaleValue(array $map, string $locale): mixed
    {
        if (array_key_exists($locale, $map)) {
            return $map[$locale];
        }

        foreach ($map as $key => $value) {
            if (! is_string($key)) {
                continue;
            }
            if (function_exists('block_admin_locale_codes_match') && block_admin_locale_codes_match($key, $locale)) {
                return $value;
            }
        }

        $norm = static function (string $s): string {
            return strtolower(explode('-', str_replace('_', '-', $s))[0]);
        };
        $want = $norm($locale);
        foreach ($map as $key => $value) {
            if (! is_string($key)) {
                continue;
            }
            if ($norm($key) === $want) {
                return $value;
            }
        }

        return null;
    }

    /**
     * Get the owning model for this private block.
     */
    public function owner()
    {
        return $this->morphTo();
    }

    /**
     * Scope for shared (library) blocks.
     */
    public function scopeShared($query)
    {
        return $query->where('is_shared', true);
    }

    /**
     * Get the block type for this instance.
     */
    public function blockType()
    {
        return $this->belongsTo(BlockType::class);
    }

    /**
     * Get the layouts where this instance is used.
     */
    public function layouts()
    {
        return $this->hasMany(PageLayout::class);
    }
}
