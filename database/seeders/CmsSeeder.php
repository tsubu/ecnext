<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlockType;
use App\Models\BlockInstance;
use App\Models\Page;
use App\Models\PageLayout;

class CmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Basic Block Types
        $heroType = BlockType::create([
            'type_key' => 'hero_simple',
            'name' => 'シンプルヒーロー',
            'schema' => [
                'title' => 'string',
                'subtitle' => 'string',
                'image_url' => 'string',
                'button_text' => 'string',
            ],
            'is_system' => true,
        ]);

        $gridType = BlockType::create([
            'type_key' => 'product_grid',
            'name' => '商品グリッド',
            'schema' => [
                'category_id' => 'integer',
                'limit' => 'integer',
            ],
            'is_system' => true,
        ]);

        // 2. Create Block Instances for Home Page
        $heroInstance = BlockInstance::create([
            'block_type_id' => $heroType->id,
            'name' => 'Top Hero Banner',
            'settings' => [
                'title' => 'The Future of Living',
                'subtitle' => 'Exclusive technology and modern interior collections.',
                'image_url' => '/images/hero-tech.jpg',
                'button_text' => 'Shop Now',
            ],
            'is_active' => true,
        ]);

        $featuredProducts = BlockInstance::create([
            'block_type_id' => $gridType->id,
            'name' => 'Home Featured Products',
            'settings' => [
                'category_id' => 1, // High-End Tech
                'limit' => 4,
            ],
            'is_active' => true,
        ]);

        // 3. Create Home Page and Assign Layouts
        $homePage = Page::create([
            'slug' => 'index', // Home page marker
            'title' => 'EC-NEXT | Premium Store',
            'is_system' => true,
            'is_published' => true,
        ]);

        PageLayout::create([
            'page_id' => $homePage->id,
            'block_instance_id' => $heroInstance->id,
            'section_key' => 'main_top',
            'sort_order' => 1,
        ]);

        PageLayout::create([
            'page_id' => $homePage->id,
            'block_instance_id' => $featuredProducts->id,
            'section_key' => 'main_content',
            'sort_order' => 2,
        ]);
    }
}
