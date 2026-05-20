<?php
 
 namespace Database\Seeders;
 
 use App\Models\Theme;
 use Illuminate\Database\Seeder;
 
 class ThemeSeeder extends Seeder
 {
     /**
      * Run the database seeds.
      */
     public function run(): void
     {
         $themes = [
             [
                 'name' => 'Luxe Aura',
                 'theme_key' => 'luxe-aura',
                 'preview_image' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?auto=format&fit=crop&q=80&w=800',
                 'is_active' => true,
                 'settings' => ['industry' => 'ジュエリー・高級宝飾', 'primary_color' => '#4c1d95'],
                 'languages' => ['ja', 'en'],
             ],
             [
                 'name' => 'Zen Garden',
                 'theme_key' => 'zen-garden',
                 'preview_image' => 'https://images.unsplash.com/photo-1594913785162-e678563fe273?auto=format&fit=crop&q=80&w=800',
                 'is_active' => false,
                 'settings' => ['industry' => 'ミニマル家具・インテリア', 'primary_color' => '#365314'],
                 'languages' => ['ja', 'en'],
             ],
             [
                 'name' => 'Neon Pulse',
                 'theme_key' => 'neon-pulse',
                 'preview_image' => 'https://images.unsplash.com/photo-1614299351052-78be26569f4c?auto=format&fit=crop&q=80&w=800',
                 'is_active' => false,
                 'settings' => ['industry' => 'ゲーミング・最新ガジェット', 'primary_color' => '#db2777'],
                 'languages' => ['ja', 'en'],
             ],
             [
                 'name' => 'Organic Harvest',
                 'theme_key' => 'organic-harvest',
                 'preview_image' => 'https://images.unsplash.com/photo-1466632346460-9d55eac66a3a?auto=format&fit=crop&q=80&w=800',
                 'is_active' => false,
                 'settings' => ['industry' => 'オーガニック・健康食品', 'primary_color' => '#14532d'],
                 'languages' => ['ja', 'en'],
             ],
             [
                 'name' => 'Petite Patisserie',
                 'theme_key' => 'petite-patisserie',
                 'preview_image' => 'https://images.unsplash.com/photo-1488477181946-6428a0291777?auto=format&fit=crop&q=80&w=800',
                 'is_active' => false,
                 'settings' => ['industry' => 'スイーツ・洋菓子専門店', 'primary_color' => '#be185d'],
                 'languages' => ['ja', 'en'],
             ],
             [
                 'name' => 'Aqua Marine',
                 'theme_key' => 'aqua-marine',
                 'preview_image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&q=80&w=800',
                 'is_active' => false,
                 'settings' => ['industry' => 'アウトドア・サーフギア', 'primary_color' => '#0e7490'],
                 'languages' => ['ja', 'en'],
             ],
             [
                 'name' => 'Titanium Edge',
                 'theme_key' => 'titanium-edge',
                 'preview_image' => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=800',
                 'is_active' => false,
                 'settings' => ['industry' => '自動車パーツ・産業機器', 'primary_color' => '#475569'],
                 'languages' => ['ja', 'en'],
             ],
             [
                 'name' => 'Silk & Satin',
                 'theme_key' => 'silk-satin',
                 'preview_image' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&q=80&w=800',
                 'is_active' => false,
                 'settings' => ['industry' => 'コスメ・スキンケア', 'primary_color' => '#ec4899'],
                 'languages' => ['ja', 'en'],
             ],
             [
                 'name' => 'Pixel Perfect',
                 'theme_key' => 'pixel-perfect',
                 'preview_image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&q=80&w=800',
                 'is_active' => false,
                 'settings' => ['industry' => 'クリエイティブ・デザイン', 'primary_color' => '#2563eb'],
                 'languages' => ['ja', 'en'],
             ],
             [
                 'name' => 'Ancient Ink',
                 'theme_key' => 'ancient-ink',
                 'preview_image' => 'https://images.unsplash.com/photo-1564850785122-38e4a9e5ef2b?auto=format&fit=crop&q=80&w=800',
                 'is_active' => false,
                 'settings' => ['industry' => '伝統工芸・上質文具', 'primary_color' => '#1e293b'],
                 'languages' => ['ja', 'en'],
             ],
         ];
 
         foreach ($themes as $themeData) {
             Theme::updateOrCreate(
                 ['theme_key' => $themeData['theme_key']],
                 $themeData
             );
         }
     }
 }
