<?php

/**
 * Creates resources/blocks/{type}/lang/ja.json and en.json from block.json "name".
 * Run: php scripts/seed-block-folder-lang.php
 */
$root = dirname(__DIR__).'/resources/blocks';
$enMap = [
    'hero_canvas' => 'Hero banner',
    'split_banner' => 'Split banner',
    'bento_grid' => 'Bento grid',
    'banner_slider' => 'Banner slider',
    'image_block' => 'Image',
    'coupon_banner' => 'Coupon banner',
    'countdown_timer' => 'Countdown timer',
    'button_cta' => 'CTA button',
    'product_display' => 'Product display',
    'sales_activity' => 'Sales activity',
    'spec_table' => 'Specifications table',
    'category_nav' => 'Category navigation',
    'static_cms' => 'Rich text / HTML',
    'feature_matrix' => 'Feature matrix',
    'faq_accordion' => 'FAQ accordion',
    'video_block' => 'Video',
    'news_list' => 'News list',
    'custom_css' => 'Custom CSS',
    'spacer' => 'Spacer',
    'testimonial_slider' => 'Testimonials',
    'logo_cloud' => 'Logo cloud',
    'newsletter_signup' => 'Newsletter signup',
];

foreach (glob($root.'/*/block.json') ?: [] as $manifest) {
    $dir = dirname($manifest);
    $key = basename($dir);
    $j = json_decode(file_get_contents($manifest), true);
    if (! is_array($j)) {
        continue;
    }
    $jaName = isset($j['name']) && is_string($j['name']) ? $j['name'] : $key;
    $enName = $enMap[$key] ?? ucwords(str_replace('_', ' ', $key));

    $langDir = $dir.'/lang';
    if (! is_dir($langDir)) {
        mkdir($langDir, 0755, true);
    }

    file_put_contents(
        $langDir.'/ja.json',
        json_encode(['name' => $jaName], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)."\n"
    );
    file_put_contents(
        $langDir.'/en.json',
        json_encode(['name' => $enName], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)."\n"
    );
    echo "lang: {$key}\n";
}
