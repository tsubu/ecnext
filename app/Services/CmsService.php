<?php

namespace App\Services;

use App\Models\BlockInstance;
use App\Models\Page;
use App\Models\PageLayout;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CmsService
{
    /**
     * Create or update a block instance.
     */
    public function saveBlock(array $data, ?BlockInstance $block = null): BlockInstance
    {
        $settings = null;
        if (!empty($data['settings'])) {
            $settings = is_array($data['settings']) 
                ? $data['settings'] 
                : json_decode($data['settings'], true);
        }

        $typeId = $data['block_type_id'] ?? null;
        if (!$typeId && !$block) {
            $typeId = DB::table('block_types')->where('type_key', 'static_cms')->value('id');
        }

        $attributes = [
            'block_type_id' => $typeId ?? ($block ? $block->block_type_id : null),
            'name' => $data['name'] ?? $data['title'] ?? '',
            'slug' => $data['slug'] ?? null,
            'settings' => $settings,
            'is_active' => $data['is_active'] ?? true,
            'is_shared' => $data['is_shared'] ?? true,
            'owner_id' => $data['owner_id'] ?? null,
            'owner_type' => $data['owner_type'] ?? null,
        ];

        if ($block) {
            $block->update($attributes);
            return $block;
        }

        return BlockInstance::create($attributes);
    }

    /**
     * Sync layouts for any morphable model (Page, Product, etc.).
     */
    public function syncLayouts(Model $target, array $layouts): void
    {
        DB::transaction(function () use ($target, $layouts) {
            // Option: If they are local blocks belonging to this target, we might want to keep them or clean them.
            // For now, we delete the layout records, but NOT the instances unless they are orphaned.
            $target->layouts()->delete();

            foreach ($layouts as $index => $item) {
                $instanceId = $item['block_instance_id'] ?? null;
                
                // If it's a "New Local Block" (passed via template/settings but no instance ID yet)
                if (!$instanceId && !empty($item['block_type_id'])) {
                    $instance = $this->saveBlock([
                        'block_type_id' => $item['block_type_id'],
                        'name' => ($item['name'] ?? 'Local Section') . ' (' . $target->id . ')',
                        'settings' => $item['settings'] ?? [],
                        'is_shared' => false,
                        'owner_id' => $target->id,
                        'owner_type' => get_class($target),
                    ]);
                    $instanceId = $instance->id;
                }

                if ($instanceId) {
                    PageLayout::create([
                        'layoutable_id' => $target->id,
                        'layoutable_type' => get_class($target),
                        'block_instance_id' => $instanceId,
                        'settings_override' => $item['settings_override'] ?? null,
                        'section_key' => $item['section_key'] ?? 'main',
                        'sort_order' => $index,
                        'starts_at' => !empty($item['starts_at']) ? $item['starts_at'] : null,
                        'expires_at' => !empty($item['expires_at']) ? $item['expires_at'] : null,
                    ]);
                }
            }
        });
    }

    /**
     * Save a page with its structured data.
     */
    public function savePage(array $data, ?Page $page = null): Page
    {
        if ($page) {
            $page->update($data);
        } else {
            $page = Page::create($data);
        }

        return $page;
    }
}
