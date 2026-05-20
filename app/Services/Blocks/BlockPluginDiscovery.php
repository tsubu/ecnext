<?php

namespace App\Services\Blocks;

use Illuminate\Support\Facades\File;

/**
 * Discovers block "plugins": one folder per type_key under the plugins root,
 * each containing block.json and a Blade entry view.
 */
final class BlockPluginDiscovery
{
    /**
     * @return list<array{type_key: string, path: string, manifest: array<string, mixed>}>
     */
    public static function plugins(): array
    {
        $root = config('blocks.plugins_path');
        $manifestName = config('blocks.plugins_manifest', 'block.json');

        if (! is_string($root) || ! is_dir($root)) {
            return [];
        }

        $out = [];
        foreach (File::directories($root) as $dir) {
            $typeKey = basename($dir);
            if ($typeKey === '' || str_contains($typeKey, '.')) {
                continue;
            }
            $manifestFile = $dir.DIRECTORY_SEPARATOR.$manifestName;
            if (! is_file($manifestFile)) {
                continue;
            }
            $raw = File::get($manifestFile);
            $data = json_decode($raw, true);
            if (! is_array($data)) {
                continue;
            }
            $jsonType = isset($data['type_key']) ? (string) $data['type_key'] : '';
            if ($jsonType !== '' && $jsonType !== $typeKey) {
                $data['_folder_type_key'] = $typeKey;
                $data['_manifest_type_key_mismatch'] = $jsonType;
            }
            $data['type_key'] = $typeKey;

            $out[] = [
                'type_key' => $typeKey,
                'path' => $dir,
                'manifest' => $data,
            ];
        }

        return $out;
    }

    public static function viewName(string $typeKey): string
    {
        $entry = str_replace('.', '', config('blocks.plugins_view_entry', 'view'));

        return "block-plugins::{$typeKey}.{$entry}";
    }

    public static function hasView(string $typeKey): bool
    {
        return view()->exists(self::viewName($typeKey));
    }
}
