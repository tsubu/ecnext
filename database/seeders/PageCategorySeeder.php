<?php

namespace Database\Seeders;

use App\Models\PageCategory;
use Illuminate\Database\Seeder;

class PageCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PageCategory::firstOrCreate(['slug' => 'legal'], [
            'name' => '法務・規約',
            'description' => '特定商取引法に基づく表記やプライバシーポリシーなどの重要文書。',
            'sort_order' => 10,
        ]);

        PageCategory::firstOrCreate(['slug' => 'features'], [
            'name' => 'セール・特集',
            'description' => '季節のキャンペーンや商品特集ページ。',
            'sort_order' => 20,
        ]);
        
        PageCategory::firstOrCreate(['slug' => 'guides'], [
            'name' => 'ご利用ガイド',
            'description' => '送料・配送や支払方法についての説明ページ。',
            'sort_order' => 30,
        ]);
    }
}
