<?php

use App\Services\Blocks\BlockPluginLang;
use App\Services\Localization\LanguageService;

if (! function_exists('block_configured_locales')) {
    /**
     * Locale codes enabled for block instance names / settings (see config/blocks.php).
     *
     * @return list<string>
     */
    function block_configured_locales(): array
    {
        return config('blocks.locales', ['ja', 'en']);
    }
}

if (! function_exists('block_display_name_placeholder')) {
    /**
     * Placeholder copy for block instance name fields (example only, not a second label).
     */
    function block_display_name_placeholder(string $code): string
    {
        $examples = [
            'ja' => '例: メインビジュアル（夏季セール）',
            'en' => 'e.g. Main hero (summer sale)',
            'fr' => 'ex. : Visuel principal (soldes d’été)',
            'de' => 'z. B. Hauptbanner (Sommerschlussverkauf)',
            'es' => 'p. ej. Banner principal (rebajas de verano)',
            'it' => 'es. Banner principale (saldi estivi)',
            'pt' => 'ex.: Banner principal (liquidação de verão)',
            'pt_BR' => 'ex.: Banner principal (liquidação de verão)',
            'nl' => 'bijv. Hoofdbanner (zomersale)',
            'ko' => '예: 메인 히어로 (여름 세일)',
            'zh' => '例：主视觉横幅（夏季促销）',
            'zh_CN' => '例：主视觉横幅（夏季促销）',
            'zh_TW' => '例：主視覺橫幅（夏季促銷）',
            'th' => 'เช่น แบนเนอร์หลัก (ลดราคาฤดูร้อน)',
            'vi' => 'vd: Banner chính (sale mùa hè)',
            'id' => 'mis. Banner utama (sale musim panas)',
            'ru' => 'напр. Главный баннер (летняя распродажа)',
            'ar' => 'مثال: بانر رئيسي (تخفيضات الصيف)',
        ];

        return $examples[$code] ?? 'e.g. Block title for this language';
    }
}

if (! function_exists('block_admin_locale_rows')) {
    /**
     * Admin UI: each configured block locale with label (e.g. preview dropdown) and example placeholder.
     * Order: current admin locale first, then English (if configured), then the rest.
     *
     * @return list<array{code: string, label: string, placeholder: string}>
     */
    function block_admin_locale_rows(): array
    {
        $overrides = config('blocks.locale_labels');
        if (! is_array($overrides)) {
            $overrides = [];
        }
        $svc = app(LanguageService::class);
        $configured = block_configured_locales();
        $order = array_flip($configured);
        $current = app()->getLocale();

        $rows = [];
        foreach ($configured as $code) {
            $rows[] = [
                'code' => $code,
                'label' => $overrides[$code] ?? $svc->labelForLocale($code),
                'placeholder' => block_display_name_placeholder($code),
            ];
        }

        usort($rows, function (array $a, array $b) use ($current, $order): int {
            $ra = block_admin_locale_sort_rank($a['code'], $current);
            $rb = block_admin_locale_sort_rank($b['code'], $current);
            if ($ra !== $rb) {
                return $ra <=> $rb;
            }

            return ($order[$a['code']] ?? 999) <=> ($order[$b['code']] ?? 999);
        });

        return $rows;
    }
}

if (! function_exists('block_admin_locale_codes_match')) {
    /**
     * True when block locale code matches admin UI locale (e.g. fr ↔ fr_FR).
     */
    function block_admin_locale_codes_match(string $code, string $adminLocale): bool
    {
        if ($code === $adminLocale) {
            return true;
        }
        $norm = static function (string $s): string {
            return strtolower(str_replace('_', '-', explode('.', $s)[0]));
        };
        $a = $norm($adminLocale);
        $c = $norm($code);

        return $a !== '' && ($a === $c || str_starts_with($a, $c.'-') || str_starts_with($c, $a.'-'));
    }
}

if (! function_exists('block_admin_locale_sort_rank')) {
    function block_admin_locale_sort_rank(string $code, string $adminLocale): int
    {
        if (block_admin_locale_codes_match($code, $adminLocale)) {
            return 0;
        }
        if ($code === 'en') {
            return 1;
        }

        return 2;
    }
}

if (! function_exists('block_trans')) {
    /**
     * Translation from resources/blocks/{type_key}/lang/{locale}.json (dot keys supported).
     */
    function block_trans(string $typeKey, string $key, mixed $default = null, ?string $locale = null): string
    {
        return BlockPluginLang::line($typeKey, $key, $default, $locale);
    }
}
