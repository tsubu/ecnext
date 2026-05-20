@props([
    'block' => null,
    'instance' => null,
    'settings' => [],
    'contextProduct' => null,
    'contextCategory' => null,
])

@php
    $instance = $instance ?? $block;
    if (!$instance) return;

    $resolved = $instance->resolvedSettings();
    $passed = $settings ?? [];
    if (is_array($passed) && $passed !== []) {
        $settings = array_replace_recursive($resolved, $passed);
    } else {
        $settings = $resolved;
    }
    $typeKey = $instance->blockType?->type_key ?? 'static_cms';
    
    $activeTheme = \App\Models\Theme::active()->first();
    $themeSlug = $activeTheme ? $activeTheme->theme_key : 'classic';
    
    // Resolve View Path
    // 1. Theme Specific Path: themes.{slug}.blocks.{type}
    // 2. Folder plugin: resources/blocks/{type}/view.blade.php (namespace block-plugins)
    // 3. Legacy fallback: components.shop.blocks.{type}
    
    $themeView = "themes.{$themeSlug}.blocks.{$typeKey}";
    $pluginView = \App\Services\Blocks\BlockPluginDiscovery::viewName($typeKey);
    $commonView = "components.shop.blocks.{$typeKey}";
    
    $finalView = null;
    if (view()->exists($themeView)) {
        $finalView = $themeView;
    } elseif (view()->exists($pluginView)) {
        $finalView = $pluginView;
    } elseif (view()->exists($commonView)) {
        $finalView = $commonView;
    }
@endphp

@if($finalView)
    <div class="block-instance" data-block-id="{{ $instance->id }}" data-type="{{ $typeKey }}">
        @include($finalView, [
            'settings' => $settings,
            'instance' => $instance,
            'contextProduct' => $contextProduct,
            'contextCategory' => $contextCategory,
        ])
    </div>
@else
    <div class="p-8 bg-slate-50 border border-dashed border-slate-200 rounded-3xl text-center">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('System: Missing Component View') }}</p>
        <p class="text-[10px] text-slate-300 font-mono mt-1">{{ "Tried: $themeView, $pluginView, $commonView" }}</p>
    </div>
@endif
