<?php

namespace App\Console\Commands;

use App\Services\Blocks\BlockPluginDiscovery;
use App\Services\Blocks\BlockPluginSyncService;
use Illuminate\Console\Command;

class SyncBlockPlugins extends Command
{
    protected $signature = 'blocks:sync';

    protected $description = 'Register or update block_types from folder plugins (resources/blocks/*/block.json)';

    public function handle(BlockPluginSyncService $syncService): int
    {
        $plugins = BlockPluginDiscovery::plugins();

        if ($plugins === []) {
            $this->warn('No block plugins found. Add folders under '.config('blocks.plugins_path').' each with block.json');

            return self::SUCCESS;
        }

        foreach ($plugins as $plugin) {
            $key = $plugin['type_key'];
            $m = $plugin['manifest'];

            if (! empty($m['_manifest_type_key_mismatch'])) {
                $this->warn("Plugin [{$key}]: block.json type_key \"{$m['_manifest_type_key_mismatch']}\" ignored; folder name wins.");
            }

            if (! BlockPluginDiscovery::hasView($key)) {
                $this->error("Plugin [{$key}]: missing view — expected ".BlockPluginDiscovery::viewName($key));
            }
        }

        $n = $syncService->syncTypes();

        $this->newLine();
        $this->info("Synced {$n} block type(s) from folders. is_system=true (protected from deletion like core types).");

        return self::SUCCESS;
    }
}
