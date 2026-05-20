<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlockType;
use App\Models\BlockInstance;
use App\Models\Page;
use App\Models\PageLayout;
use Illuminate\Support\Facades\DB;

class MasterShowcaseSeeder extends Seeder
{
    public function run(): void
    {
        // Clean up previous demo page
        Page::where('slug', 'full-library-preview')->delete();

        $page = Page::create([
            'slug' => 'full-library-preview',
            'title' => 'Stitch Library 2.0 | Mastermind Preview',
            'is_published' => true,
        ]);

        $blockTypes = BlockType::all();
        $order = 1;

        foreach ($blockTypes as $type) {
            // Skip general static_cms
            if ($type->type_key === 'static_cms') continue;

            $settings = [];
            // Basic settings based on common keys in schema
            if ($type->schema) {
                foreach ($type->schema as $key => $dataType) {
                    if ($dataType === 'image') $settings[$key] = 'https://images.unsplash.com/photo-1635776062127-d379bfcba9f8?auto=format&fit=crop&q=80&w=2000';
                    elseif ($dataType === 'integer' || $dataType === 'number') $settings[$key] = 5;
                    else $settings[$key] = 'Stitch Premium Content: ' . $key;
                }
            }

            // Custom adjustments for some blocks
            if ($type->type_key === 'banner_ticker') $settings['text'] = 'Stitch Design 2.0 Launching Globally — Explore the Future of Commerce — ';
            if ($type->type_key === 'countdown_sale') $settings['end_date'] = '2026-12-31';
            
            $instance = BlockInstance::create([
                'block_type_id' => $type->id,
                'name' => 'Demo: ' . $type->resolvedName(),
                'settings' => $settings,
                'is_active' => true,
            ]);

            PageLayout::create([
                'layoutable_id' => $page->id,
                'layoutable_type' => Page::class,
                'block_instance_id' => $instance->id,
                'section_key' => $order === 1 ? 'hero' : 'main',
                'sort_order' => $order++,
            ]);
        }
    }
}
