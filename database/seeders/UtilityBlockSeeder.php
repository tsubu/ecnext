<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlockType;
use App\Models\BlockInstance;

class UtilityBlockSeeder extends Seeder
{
    public function run(): void
    {
        // ===== カスタムCSS =====
        $css = BlockType::firstOrCreate(
            ['type_key' => 'custom_css'],
            [
                'name' => 'カスタムCSS',
                'category' => 'General',
                'schema' => ['css_code' => 'text'],
                'icon' => 'code',
                'is_system' => true,
            ]
        );

        // ===== HTMLブロック =====
        $html = BlockType::firstOrCreate(
            ['type_key' => 'raw_html'],
            [
                'name' => 'HTML埋め込み',
                'category' => 'General',
                'schema' => ['html_code' => 'text'],
                'icon' => 'code',
                'is_system' => true,
            ]
        );

        // ===== 画像ブロック =====
        $img = BlockType::firstOrCreate(
            ['type_key' => 'image_block'],
            [
                'name' => '画像',
                'category' => 'Marketing',
                'schema' => ['image_url' => 'image', 'alt_text' => 'string', 'caption' => 'string', 'link_url' => 'string', 'width' => 'string'],
                'icon' => 'photograph',
                'is_system' => true,
            ]
        );

        // ===== 動画ブロック =====
        $vid = BlockType::firstOrCreate(
            ['type_key' => 'video_block'],
            [
                'name' => '動画',
                'category' => 'Marketing',
                'schema' => ['video_url' => 'string', 'poster_image' => 'image', 'caption' => 'string', 'autoplay' => 'string', 'loop' => 'string'],
                'icon' => 'video-camera',
                'is_system' => true,
            ]
        );

        // --- カスタムCSS プリセット ---
        $cssPresets = [
            ['name' => 'セール用 赤アクセント', 'settings' => ['css_code' => ".sale-badge { background: #dc2626; color: #fff; padding: 4px 12px; border-radius: 999px; font-size: 11px; font-weight: 800; }\n.sale-price { color: #dc2626; font-weight: 900; font-size: 1.5em; }"]],
            ['name' => 'LP用 フルワイド解除', 'settings' => ['css_code' => ".page-content { max-width: 100% !important; padding: 0 !important; }\nsection { border-radius: 0 !important; }"]],
            ['name' => 'ダークセクション背景', 'settings' => ['css_code' => ".dark-section { background: #0f172a; color: #f8fafc; padding: 80px 40px; }\n.dark-section h2 { color: #818cf8; }\n.dark-section p { color: #94a3b8; }"]],
        ];

        foreach ($cssPresets as $p) {
            BlockInstance::create(array_merge($p, ['block_type_id' => $css->id, 'is_active' => true, 'is_shared' => true]));
        }

        // --- HTML埋め込み プリセット ---
        $htmlPresets = [
            ['name' => 'Googleマップ埋め込み', 'settings' => ['html_code' => '<div style="border-radius:24px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.08)"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3240.828030365498!2d139.76454!3d35.6812!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzXCsDQwJzUyLjMiTiAxMznCsDQ1JzUyLjMiRQ!5e0!3m2!1sja!2sjp!4v1" width="100%" height="400" style="border:0" allowfullscreen loading="lazy"></iframe></div>']],
            ['name' => 'SNSフォローボタン', 'settings' => ['html_code' => '<div style="text-align:center;padding:40px 0"><p style="font-size:11px;font-weight:800;letter-spacing:0.2em;text-transform:uppercase;color:#94a3b8;margin-bottom:16px">Follow Us</p><div style="display:flex;justify-content:center;gap:16px"><a href="#" style="display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;border-radius:12px;background:#f1f5f9;color:#475569;text-decoration:none;font-size:20px;transition:all .3s" onmouseover="this.style.background=\'#4f46e5\';this.style.color=\'#fff\'" onmouseout="this.style.background=\'#f1f5f9\';this.style.color=\'#475569\'">𝕏</a><a href="#" style="display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;border-radius:12px;background:#f1f5f9;color:#475569;text-decoration:none;font-size:20px;transition:all .3s" onmouseover="this.style.background=\'#4f46e5\';this.style.color=\'#fff\'" onmouseout="this.style.background=\'#f1f5f9\';this.style.color=\'#475569\'">IG</a><a href="#" style="display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;border-radius:12px;background:#f1f5f9;color:#475569;text-decoration:none;font-size:20px;transition:all .3s" onmouseover="this.style.background=\'#4f46e5\';this.style.color=\'#fff\'" onmouseout="this.style.background=\'#f1f5f9\';this.style.color=\'#475569\'">FB</a></div></div>']],
            ['name' => 'お知らせバー', 'settings' => ['html_code' => '<div style="background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;text-align:center;padding:14px 24px;font-size:13px;font-weight:700;letter-spacing:0.05em;border-radius:16px;margin:16px 0">🎉 ただいま全品ポイント2倍キャンペーン実施中！ <a href="/sale" style="color:#fbbf24;margin-left:8px;text-decoration:underline">詳しくはこちら →</a></div>']],
        ];

        foreach ($htmlPresets as $p) {
            BlockInstance::create(array_merge($p, ['block_type_id' => $html->id, 'is_active' => true, 'is_shared' => true]));
        }

        // --- 画像ブロック プリセット ---
        $imgPresets = [
            ['name' => 'フルワイド・バナー画像', 'settings' => ['image_url' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&q=80&w=2000', 'alt_text' => 'プロモーションバナー', 'caption' => '', 'link_url' => '/sale', 'width' => '100%']],
            ['name' => 'セール告知バナー', 'settings' => ['image_url' => 'https://images.unsplash.com/photo-1607082349566-187342175e2f?auto=format&fit=crop&q=80&w=2000', 'alt_text' => 'セール告知', 'caption' => '期間限定セール開催中', 'link_url' => '/sale', 'width' => '100%']],
            ['name' => 'ブランドイメージ（中央配置）', 'settings' => ['image_url' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&q=80&w=1200', 'alt_text' => 'ブランドイメージ', 'caption' => '洗練されたデザイン、揺るぎない品質', 'link_url' => '', 'width' => '800px']],
        ];

        foreach ($imgPresets as $p) {
            BlockInstance::create(array_merge($p, ['block_type_id' => $img->id, 'is_active' => true, 'is_shared' => true]));
        }

        // --- 動画ブロック プリセット ---
        $vidPresets = [
            ['name' => '商品プロモーション動画', 'settings' => ['video_url' => '', 'poster_image' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&q=80&w=2000', 'caption' => '商品の魅力を映像でお届けします', 'autoplay' => 'no', 'loop' => 'no']],
            ['name' => '背景ループ動画', 'settings' => ['video_url' => '', 'poster_image' => '', 'caption' => '', 'autoplay' => 'yes', 'loop' => 'yes']],
            ['name' => 'ハウツー・チュートリアル動画', 'settings' => ['video_url' => '', 'poster_image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&q=80&w=2000', 'caption' => '使い方をわかりやすくご説明します', 'autoplay' => 'no', 'loop' => 'no']],
        ];

        foreach ($vidPresets as $p) {
            BlockInstance::create(array_merge($p, ['block_type_id' => $vid->id, 'is_active' => true, 'is_shared' => true]));
        }
    }
}
