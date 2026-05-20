<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @viteReactRefresh
        @vite(['resources/js/app.jsx', "resources/js/Pages/{$page['component']}.jsx"])
        @inertiaHead

        {{-- Dynamic Design Tokens --}}
        @php
            $designService = app(\App\Services\Design\DesignTokenService::class);
            $activeTheme = \App\Models\Theme::where('is_active', true)->first();
        @endphp
        <style>
            {!! $designService->generateCss($activeTheme) !!}
            
            body { 
                font-family: var(--theme-font-main); 
                background-color: var(--theme-bg);
            }
            .rounded-theme { border-radius: var(--theme-radius); }
        </style>
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
