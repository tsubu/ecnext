<?php

namespace Database\Seeders;

use App\Models\BlockInstance;
use App\Models\BlockType;
use Database\Seeders\Concerns\BuildsSharedBlockInstanceFromPreset;
use Illuminate\Database\Seeder;

/**
 * 販売状況ブロック（楽天風）を3種登録します。
 * ・店舗全体 / カテゴリ内 / 商品（商品ページでは context で商品IDを自動解決）
 * BlockLibrarySeeder にも同型が含まれるため、フルシード時は重複作成されません。
 * 既存DBへ足すだけのとき: php artisan db:seed --class=SalesActivityBlockSeeder
 */
class SalesActivityBlockSeeder extends Seeder
{
    use BuildsSharedBlockInstanceFromPreset;

    public function run(): void
    {
        $type = BlockType::updateOrCreate(
            ['type_key' => 'sales_activity'],
            [
                'name' => '販売状況',
                'category' => 'Commerce',
                'schema' => [
                    'scope' => 'string',
                    'category_id' => 'integer',
                    'product_id' => 'integer',
                    'hours' => 'integer',
                    'title' => 'string',
                    'style' => 'string',
                ],
                'icon' => 'fire',
                'is_system' => true,
            ]
        );

        BlockInstance::where('block_type_id', $type->id)->delete();

        $presets = [
            [
                'name' => '販売状況（店舗全体）',
                'name_locales' => [
                    'ja' => '販売状況（店舗全体）',
                    'en' => 'Sales activity (store-wide)',
                ],
                'settings' => [
                    'scope' => 'store',
                    'category_id' => null,
                    'product_id' => null,
                    'hours' => 24,
                    'title' => '',
                    'style' => 'card',
                ],
            ],
            [
                'name' => '販売状況（カテゴリ内）',
                'settings' => [
                    'scope' => 'category',
                    'category_id' => 1,
                    'product_id' => null,
                    'hours' => 24,
                    'title' => '',
                    'style' => 'card',
                ],
            ],
            [
                'name' => '販売状況（商品・商品ページ向け）',
                'settings' => [
                    'scope' => 'product',
                    'category_id' => null,
                    'product_id' => null,
                    'hours' => 24,
                    'title' => '',
                    'style' => 'card',
                ],
            ],
        ];

        foreach ($presets as $p) {
            BlockInstance::create(array_merge($p, [
                'block_type_id' => $type->id,
                'is_active' => true,
                'is_shared' => true,
            ]));
        }
    }
}
