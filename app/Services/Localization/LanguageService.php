<?php

namespace App\Services\Localization;

use Illuminate\Support\Facades\File;

class LanguageService
{
    /**
     * Get list of available languages by scanning lang directory.
     * WordPress style discovery.
     *
     * @return array
     */
    public function getAvailableLanguages(): array
    {
        $langPath = base_path('lang');
        $locales = [];

        if (!File::exists($langPath)) {
            return ['en' => 'English'];
        }

        // 1. Scan Directories (PHP based translations)
        $directories = File::directories($langPath);
        foreach ($directories as $dir) {
            $code = basename($dir);
            $locales[$code] = $this->getLanguageName($code);
        }

        // 2. Scan JSON Files
        $jsonFiles = File::files($langPath);
        foreach ($jsonFiles as $file) {
            if ($file->getExtension() === 'json') {
                $code = $file->getFilenameWithoutExtension();
                if (!isset($locales[$code])) {
                    $locales[$code] = $this->getLanguageName($code);
                }
            }
        }

        return $locales;
    }

    /**
     * Display name for a locale code (admin block fields, headers, etc.).
     */
    public function labelForLocale(string $code): string
    {
        return $this->getLanguageName($code);
    }

    /**
     * Try to get a user-friendly language name.
     *
     * @param string $code
     * @return string
     */
    protected function getLanguageName(string $code): string
    {
        // Check if the language file itself has a name definition (WordPress style)
        // We look for a special key '_language_name' in JSON file
        $jsonPath = base_path("lang/{$code}.json");
        if (File::exists($jsonPath)) {
            $data = json_decode(File::get($jsonPath), true);
            if (isset($data['_language_name'])) {
                return $data['_language_name'];
            }
        }

        // Fallback mapping (extend as needed; use lang/{code}.json `_language_name` or config blocks.locale_labels)
        $mapping = [
            'ja' => '日本語',
            'en' => 'English',
            'fr' => 'Français',
            'de' => 'Deutsch',
            'es' => 'Español',
            'it' => 'Italiano',
            'pt' => 'Português',
            'pt_BR' => 'Português (Brasil)',
            'nl' => 'Nederlands',
            'pl' => 'Polski',
            'ru' => 'Русский',
            'uk' => 'Українська',
            'sv' => 'Svenska',
            'da' => 'Dansk',
            'fi' => 'Suomi',
            'no' => 'Norsk',
            'nb' => 'Norsk bokmål',
            'cs' => 'Čeština',
            'sk' => 'Slovenčina',
            'hu' => 'Magyar',
            'ro' => 'Română',
            'el' => 'Ελληνικά',
            'tr' => 'Türkçe',
            'ar' => 'العربية',
            'he' => 'עברית',
            'hi' => 'हिन्दी',
            'th' => 'ไทย',
            'vi' => 'Tiếng Việt',
            'id' => 'Bahasa Indonesia',
            'ms' => 'Bahasa Melayu',
            'tl' => 'Filipino',
            'zh' => '中文',
            'zh_CN' => '中文（简体）',
            'zh_TW' => '中文（繁體）',
            'ko' => '한국어',
        ];

        return $mapping[$code] ?? strtoupper($code);
    }
}
