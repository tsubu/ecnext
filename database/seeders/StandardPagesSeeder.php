<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\PageCategory;

class StandardPagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // System Pages Category
        $category = PageCategory::firstOrCreate(
            ['slug' => 'system'], 
            ['name' => 'System Pages']
        );

        $pages = [
            [
                'title' => 'INDEX',
                'slug' => 'home',
                'type' => 'default',
                'content' => 'Welcome to our store. This page is under construction.',
                'page_category_id' => $category->id,
                'is_system' => true,
                'is_published' => true,
            ],
            [
                'title' => 'プライバシーポリシー',
                'slug' => 'privacy-policy',
                'type' => 'default',
                'content' => '個人情報の取り扱いに関する規約です。',
                'page_category_id' => $category->id,
                'is_system' => true,
                'is_published' => true,
            ],
            [
                'title' => '特定商取引法に基づく表記',
                'slug' => 'trade-law',
                'type' => 'legal',
                'legal_data' => [
                    'operating_manager' => '運営 太郎',
                    'address' => '東京都港区...',
                    'phone' => '03-1234-5678',
                    'additional_fees' => '送料、振込手数料',
                    'payment_methods' => 'クレジットカード、銀行振込',
                    'delivery_time' => '注文確定後3日以内',
                    'returns' => '商品到着後7日以内'
                ],
                'page_category_id' => $category->id,
                'is_system' => true,
                'is_published' => true,
            ],
            [
                'title' => '会社概要',
                'slug' => 'about-us',
                'type' => 'default',
                'content' => '会社についての情報です。',
                'page_category_id' => $category->id,
                'is_system' => true,
                'is_published' => true,
            ],
            [
                'title' => 'お問い合わせ',
                'slug' => 'contact',
                'type' => 'default',
                'content' => '何かお困りのことがございましたら、以下のフォームよりお問い合わせください。',
                'page_category_id' => $category->id,
                'is_system' => true,
                'is_published' => true,
            ],
        ];

        foreach ($pages as $pageData) {
            Page::updateOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }
    }
}
