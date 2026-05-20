<?php

namespace App\Services\Blocks;

use App\Models\BlockType;

class BlockPluginSyncService
{
    /**
     * Upsert block_types from folder plugins (block.json + view.blade.php).
     *
     * @return int Number of types synced (skipped plugins with missing view are not counted)
     */
    public function syncTypes(): int
    {
        $synced = 0;
        foreach (BlockPluginDiscovery::plugins() as $plugin) {
            $key = $plugin['type_key'];
            $m = $plugin['manifest'];

            if (! empty($m['_manifest_type_key_mismatch'])) {
                // Folder name is canonical; mismatch is informational only here.
            }

            $name = isset($m['name']) && is_string($m['name']) && $m['name'] !== ''
                ? $m['name']
                : $key;
            $category = isset($m['category']) && is_string($m['category']) ? $m['category'] : 'General';
            $icon = isset($m['icon']) && is_string($m['icon']) ? $m['icon'] : 'cube';
            $schema = isset($m['schema']) && is_array($m['schema']) ? $m['schema'] : null;

            if (! BlockPluginDiscovery::hasView($key)) {
                continue;
            }

            BlockType::updateOrCreate(
                ['type_key' => $key],
                [
                    'name' => $name,
                    'category' => $category,
                    'schema' => $schema,
                    'icon' => $icon,
                    'is_system' => true,
                ]
            );
            $synced++;
        }

        return $synced;
    }
}
