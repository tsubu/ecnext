<?php

namespace App\Services\Design;

use App\Models\Theme;

class DesignTokenService
{
    /**
     * Default design tokens used if theme settings are missing.
     */
    protected array $defaults = [
        'primary_color' => '#6366f1', // indigo-500
        'secondary_color' => '#ec4899', // pink-500
        'font_family' => "'Figtree', ui-sans-serif, system-ui",
        'border_radius' => '1rem',
        'bg_color' => '#0f172a', // slate-900
    ];

    /**
     * Generate CSS variables from the active theme's design tokens.
     */
    public function generateCss(?Theme $theme = null): string
    {
        $settings = $theme ? ($theme->settings ?? []) : [];
        $tokens = array_merge($this->defaults, $settings);

        $css = ":root {\n";
        $css .= "    --theme-primary: {$tokens['primary_color']};\n";
        $css .= "    --theme-secondary: {$tokens['secondary_color']};\n";
        $css .= "    --theme-font-main: {$tokens['font_family']};\n";
        $css .= "    --theme-radius: {$tokens['border_radius']};\n";
        $css .= "    --theme-bg: {$tokens['bg_color']};\n";
        $css .= "}\n";

        return $css;
    }

    /**
     * Get tokens as a flattened array for API/Frontend consumption.
     */
    public function getTokens(?Theme $theme = null): array
    {
        $settings = $theme ? ($theme->settings ?? []) : [];
        return array_merge($this->defaults, $settings);
    }
}
