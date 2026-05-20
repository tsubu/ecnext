<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlockType;
use App\Models\BlockInstance;

class CommonBlockSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            // ===== バナースライダー =====
            [
                'type_key' => 'banner_slider',
                'name' => 'バナースライダー',
                'category' => 'Marketing',
                'schema' => [
                    'slide1_image' => 'image', 'slide1_link' => 'string', 'slide1_alt' => 'string',
                    'slide2_image' => 'image', 'slide2_link' => 'string', 'slide2_alt' => 'string',
                    'slide3_image' => 'image', 'slide3_link' => 'string', 'slide3_alt' => 'string',
                    'interval' => 'integer',
                ],
                'icon' => 'switch-horizontal',
            ],
            // ===== お知らせ一覧 =====
            [
                'type_key' => 'news_list',
                'name' => 'お知らせ一覧',
                'category' => 'General',
                'schema' => ['title' => 'string', 'limit' => 'integer'],
                'icon' => 'bell',
            ],
            // ===== カテゴリナビ =====
            [
                'type_key' => 'category_nav',
                'name' => 'カテゴリナビ',
                'category' => 'Commerce',
                'schema' => ['title' => 'string', 'style' => 'string'],
                'icon' => 'dots-circle-horizontal',
            ],
            // ===== クーポンバナー =====
            [
                'type_key' => 'coupon_banner',
                'name' => 'クーポンバナー',
                'category' => 'Marketing',
                'schema' => ['title' => 'string', 'code' => 'string', 'description' => 'text', 'expires' => 'string'],
                'icon' => 'ticket',
            ],
            // ===== ロゴクラウド =====
            [
                'type_key' => 'logo_cloud',
                'name' => 'ロゴクラウド',
                'category' => 'Trust',
                'schema' => ['title' => 'string', 'logo1' => 'image', 'logo2' => 'image', 'logo3' => 'image', 'logo4' => 'image', 'logo5' => 'image', 'logo6' => 'image'],
                'icon' => 'shield-check',
            ],
            // ===== カウントダウン =====
            [
                'type_key' => 'countdown_timer',
                'name' => 'カウントダウン',
                'category' => 'Marketing',
                'schema' => ['title' => 'string', 'end_date' => 'string', 'message' => 'text', 'bg_color' => 'string'],
                'icon' => 'clock',
            ],
            // ===== スペーサー =====
            [
                'type_key' => 'spacer',
                'name' => 'スペーサー',
                'category' => 'General',
                'schema' => ['height' => 'integer', 'show_line' => 'string'],
                'icon' => 'minus',
            ],
            // ===== CTAボタン =====
            [
                'type_key' => 'button_cta',
                'name' => 'CTAボタン',
                'category' => 'Marketing',
                'schema' => ['label' => 'string', 'url' => 'string', 'style' => 'string', 'size' => 'string', 'align' => 'string'],
                'icon' => 'cursor-click',
            ],
        ];

        foreach ($types as $t) {
            BlockType::firstOrCreate(['type_key' => $t['type_key']], array_merge($t, ['is_system' => true]));
        }

        // ===== プリセット =====
        $presets = [
            'banner_slider' => [
                ['name' => 'TOPメインスライダー（3枚）', 'settings' => ['slide1_image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&q=80&w=2000', 'slide1_link' => '/new', 'slide1_alt' => '新着商品', 'slide2_image' => 'https://images.unsplash.com/photo-1607082349566-187342175e2f?auto=format&fit=crop&q=80&w=2000', 'slide2_link' => '/sale', 'slide2_alt' => 'セール', 'slide3_image' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&q=80&w=2000', 'slide3_link' => '/collections', 'slide3_alt' => 'コレクション', 'interval' => 5]],
                ['name' => 'セールスライダー（3枚）', 'settings' => ['slide1_image' => 'https://images.unsplash.com/photo-1607082349566-187342175e2f?auto=format&fit=crop&q=80&w=2000', 'slide1_link' => '/sale', 'slide1_alt' => 'サマーセール', 'slide2_image' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&q=80&w=2000', 'slide2_link' => '/sale', 'slide2_alt' => 'タイムセール', 'slide3_image' => 'https://images.unsplash.com/photo-1549462980-6a6200418242?auto=format&fit=crop&q=80&w=2000', 'slide3_link' => '/sale', 'slide3_alt' => 'クリアランス', 'interval' => 4]],
                ['name' => 'ブランドストーリー・スライダー', 'settings' => ['slide1_image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=2000', 'slide1_link' => '/about', 'slide1_alt' => '私たちについて', 'slide2_image' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&q=80&w=2000', 'slide2_link' => '/sustainability', 'slide2_alt' => 'サステナビリティ', 'slide3_image' => 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?auto=format&fit=crop&q=80&w=2000', 'slide3_link' => '/story', 'slide3_alt' => 'ストーリー', 'interval' => 6]],
            ],
            'news_list' => [
                ['name' => '新着情報（5件）', 'settings' => ['title' => '新着情報', 'limit' => 5]],
                ['name' => '新着情報（3件・コンパクト）', 'settings' => ['title' => 'NEWS', 'limit' => 3]],
                ['name' => '新着情報（10件・一覧）', 'settings' => ['title' => 'お知らせ', 'limit' => 10]],
            ],
            'category_nav' => [
                ['name' => 'カテゴリ一覧（カード型）', 'settings' => ['title' => 'カテゴリから探す', 'style' => 'card']],
                ['name' => 'カテゴリ一覧（丸型）', 'settings' => ['title' => 'CATEGORY', 'style' => 'circle']],
                ['name' => 'カテゴリ一覧（リスト型）', 'settings' => ['title' => 'カテゴリ', 'style' => 'list']],
            ],
            'coupon_banner' => [
                ['name' => '新規会員クーポン', 'settings' => ['title' => '新規会員限定', 'code' => 'WELCOME10', 'description' => '会員登録で今すぐ使える10%OFFクーポンをプレゼント！', 'expires' => '']],
                ['name' => '期間限定500円OFF', 'settings' => ['title' => '期間限定クーポン', 'code' => 'SAVE500', 'description' => '¥5,000以上のお買い上げで500円OFF', 'expires' => '2026年5月31日まで']],
                ['name' => '送料無料クーポン', 'settings' => ['title' => '送料無料キャンペーン', 'code' => 'FREESHIP', 'description' => '全品送料無料！この機会をお見逃しなく。', 'expires' => '今月末まで']],
                ['name' => 'アプリ限定クーポン', 'settings' => ['title' => 'アプリ限定', 'code' => 'APP20', 'description' => 'アプリからのご注文で20%OFF！ダウンロードしてお得にお買い物♪', 'expires' => '']],
                ['name' => '誕生日月クーポン', 'settings' => ['title' => 'お誕生日おめでとうございます🎂', 'code' => 'BIRTHDAY15', 'description' => 'お誕生日月限定の特別クーポン。15%OFFでお好きなアイテムをどうぞ。', 'expires' => 'お誕生日月末まで']],
            ],
            'logo_cloud' => [
                ['name' => '取扱ブランドロゴ', 'settings' => ['title' => '取扱ブランド', 'logo1' => '', 'logo2' => '', 'logo3' => '', 'logo4' => '', 'logo5' => '', 'logo6' => '']],
                ['name' => '掲載メディアロゴ', 'settings' => ['title' => 'メディア掲載実績', 'logo1' => '', 'logo2' => '', 'logo3' => '', 'logo4' => '', 'logo5' => '', 'logo6' => '']],
                ['name' => '認証・パートナー', 'settings' => ['title' => '認証 & パートナー', 'logo1' => '', 'logo2' => '', 'logo3' => '', 'logo4' => '', 'logo5' => '', 'logo6' => '']],
            ],
            'countdown_timer' => [
                ['name' => 'セール終了カウントダウン', 'settings' => ['title' => 'タイムセール終了まで', 'end_date' => '2026-12-31T23:59', 'message' => 'お見逃しなく！全品最大50%OFF', 'bg_color' => '#dc2626']],
                ['name' => '新商品発売カウントダウン', 'settings' => ['title' => '新コレクション発売まで', 'end_date' => '2026-06-01T00:00', 'message' => '待望の新作を一番にチェックしよう', 'bg_color' => '#4f46e5']],
                ['name' => '送料無料キャンペーン終了', 'settings' => ['title' => '送料無料キャンペーン残り', 'end_date' => '2026-05-31T23:59', 'message' => '期間中は全品送料無料でお届けします。', 'bg_color' => '#059669']],
            ],
            'spacer' => [
                ['name' => '小スペース（40px）', 'settings' => ['height' => 40, 'show_line' => 'no']],
                ['name' => '中スペース（80px）', 'settings' => ['height' => 80, 'show_line' => 'no']],
                ['name' => '大スペース（120px）', 'settings' => ['height' => 120, 'show_line' => 'no']],
                ['name' => '区切り線付き（60px）', 'settings' => ['height' => 60, 'show_line' => 'yes']],
                ['name' => '区切り線付き（100px）', 'settings' => ['height' => 100, 'show_line' => 'yes']],
            ],
            'button_cta' => [
                ['name' => '商品一覧へ（プライマリ）', 'settings' => ['label' => '商品一覧を見る', 'url' => '/products', 'style' => 'primary', 'size' => 'large', 'align' => 'center']],
                ['name' => 'セール会場へ（赤）', 'settings' => ['label' => 'セール会場はこちら', 'url' => '/sale', 'style' => 'danger', 'size' => 'large', 'align' => 'center']],
                ['name' => '会員登録（アウトライン）', 'settings' => ['label' => '無料会員登録', 'url' => '/register', 'style' => 'outline', 'size' => 'medium', 'align' => 'center']],
                ['name' => 'お問い合わせ', 'settings' => ['label' => 'お問い合わせはこちら', 'url' => '/contact', 'style' => 'secondary', 'size' => 'medium', 'align' => 'center']],
                ['name' => 'もっと見る（小）', 'settings' => ['label' => 'もっと見る →', 'url' => '#', 'style' => 'text', 'size' => 'small', 'align' => 'right']],
            ],
        ];

        foreach ($presets as $typeKey => $blocks) {
            $type = BlockType::where('type_key', $typeKey)->first();
            if (!$type) continue;
            foreach ($blocks as $b) {
                BlockInstance::create(array_merge($b, [
                    'block_type_id' => $type->id,
                    'is_active' => true,
                    'is_shared' => true,
                ]));
            }
        }
    }
}
