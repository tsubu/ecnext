<?php

namespace App\Services\Blocks;

/**
 * Per-block folder translations: resources/blocks/{type_key}/lang/{locale}.json
 * Merged with app fallback_locale for missing keys.
 */
final class BlockPluginLang
{
    /** @var array<string, array<string, mixed>> */
    private static array $mergedCache = [];

    /**
     * @return array<string, mixed>
     */
    public static function merged(string $typeKey, ?string $locale = null): array
    {
        $locale = $locale ?? app()->getLocale();
        $cacheKey = $typeKey.'|'.$locale;
        if (isset(self::$mergedCache[$cacheKey])) {
            return self::$mergedCache[$cacheKey];
        }

        $fallback = config('app.fallback_locale');
        $base = self::loadFile($typeKey, $fallback) ?? [];
        $over = self::loadFileWithAliases($typeKey, $locale) ?? [];
        if (! is_array($base)) {
            $base = [];
        }
        if (! is_array($over)) {
            $over = [];
        }

        $merged = array_replace_recursive($base, $over);
        self::$mergedCache[$cacheKey] = $merged;

        return $merged;
    }

    public static function line(string $typeKey, string $key, mixed $default = null, ?string $locale = null): string
    {
        $data = self::merged($typeKey, $locale);
        $v = data_get($data, $key);
        if ($v !== null && $v !== '') {
            return is_scalar($v) ? (string) $v : json_encode($v, JSON_UNESCAPED_UNICODE);
        }

        return $default !== null ? (string) $default : $key;
    }

    /**
     * Localized blueprint title: lang/{locale}.json → key "name", else DB name.
     */
    public static function resolvedTypeName(string $typeKey, string $dbName, ?string $locale = null): string
    {
        $n = self::line($typeKey, 'name', '', $locale);

        return $n !== '' ? $n : $dbName;
    }

    public static function forgetCache(): void
    {
        self::$mergedCache = [];
    }

    /**
     * @return array<string, mixed>|null
     */
    private static function loadFile(string $typeKey, ?string $locale): ?array
    {
        if ($locale === null || $locale === '') {
            return null;
        }
        $root = config('blocks.plugins_path');
        if (! is_string($root) || $root === '') {
            return null;
        }
        $path = $root.DIRECTORY_SEPARATOR.$typeKey.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.$locale.'.json';
        if (! is_file($path)) {
            return null;
        }
        $raw = @file_get_contents($path);
        if ($raw === false) {
            return null;
        }
        $data = json_decode($raw, true);

        return is_array($data) ? $data : null;
    }

    /**
     * Try exact locale filename, then primary subtag (en_US → en), then configured block locales.
     *
     * @return array<string, mixed>|null
     */
    private static function loadFileWithAliases(string $typeKey, string $locale): ?array
    {
        $direct = self::loadFile($typeKey, $locale);
        if ($direct !== null) {
            return $direct;
        }

        $primary = strtolower(explode('-', str_replace('_', '-', $locale))[0]);
        if ($primary !== '' && $primary !== $locale) {
            $try = self::loadFile($typeKey, $primary);
            if ($try !== null) {
                return $try;
            }
        }

        if (function_exists('block_configured_locales')) {
            foreach (block_configured_locales() as $code) {
                if ($code === $locale || $code === $primary) {
                    continue;
                }
                if (function_exists('block_admin_locale_codes_match') && block_admin_locale_codes_match($code, $locale)) {
                    $try = self::loadFile($typeKey, $code);
                    if ($try !== null) {
                        return $try;
                    }
                }
            }
        }

        return null;
    }
}
