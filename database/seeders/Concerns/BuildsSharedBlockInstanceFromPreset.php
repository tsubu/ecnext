<?php

namespace Database\Seeders\Concerns;

trait BuildsSharedBlockInstanceFromPreset
{
    /**
     * @param  array<string, mixed>  $preset
     * @param  array<string, mixed>  $extra  merged last (e.g. slug overrides)
     * @return array<string, mixed>
     */
    protected function sharedBlockInstanceAttributesFromPreset(array $preset, int $blockTypeId, array $extra = []): array
    {
        $locales = array_values(array_filter(config('blocks.locales', ['ja', 'en'])));
        $baseName = (string) ($preset['name'] ?? '');
        $raw = isset($preset['name_locales']) && is_array($preset['name_locales'])
            ? $preset['name_locales']
            : [];
        $nameLocales = [];
        foreach ($locales as $loc) {
            if (isset($raw[$loc]) && $raw[$loc] !== '' && $raw[$loc] !== null) {
                $nameLocales[$loc] = (string) $raw[$loc];
            }
        }
        $primary = $locales[0] ?? 'ja';
        $dbName = $nameLocales[$primary] ?? $baseName;

        return array_merge([
            'block_type_id' => $blockTypeId,
            'name' => $dbName,
            'name_locales' => $nameLocales !== [] ? $nameLocales : null,
            'settings' => is_array($preset['settings'] ?? null) ? $preset['settings'] : [],
            'is_active' => true,
            'is_shared' => true,
        ], $extra);
    }
}
