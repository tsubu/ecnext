<?php

namespace Database\Seeders;

use App\Models\BlockInstance;
use App\Models\BlockType;
use App\Services\Blocks\BlockPluginSyncService;
use Database\Seeders\Concerns\BuildsSharedBlockInstanceFromPreset;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BlockLibrarySeeder extends Seeder
{
    use BuildsSharedBlockInstanceFromPreset;

    /**
     * Reset block tables, register types from resources/blocks (block.json), then seed shared library
     * instances from resources/blocks/_shared_library.json (optional demo content).
     *
     * Types are not defined here — only folder manifests + blocks:sync / BlockPluginSyncService.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('page_layouts')->delete();
        DB::table('block_instances')->delete();
        DB::table('block_presets')->delete();
        DB::table('block_types')->delete();

        Schema::enableForeignKeyConstraints();

        app(BlockPluginSyncService::class)->syncTypes();

        $path = resource_path('blocks/_shared_library.json');
        if (! is_file($path)) {
            return;
        }

        $shared = json_decode(file_get_contents($path), true);
        if (! is_array($shared)) {
            return;
        }

        foreach ($shared as $typeKey => $rows) {
            if (! is_array($rows)) {
                continue;
            }
            $type = BlockType::where('type_key', $typeKey)->first();
            if (! $type) {
                continue;
            }
            foreach ($rows as $preset) {
                if (! is_array($preset) || empty($preset['name'])) {
                    continue;
                }
                BlockInstance::create($this->sharedBlockInstanceAttributesFromPreset($preset, $type->id));
            }
        }
    }
}
