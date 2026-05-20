<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('meta_title', $page->meta_title ?? $page->title) | {{ config('app.name', 'EC-NEXT') }}</title>
    @if($page->meta_description)
        <meta name="description" content="{{ $page->meta_description }}">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&family=Noto+Sans+JP:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', 'Noto Sans JP', sans-serif; background-color: #fbfbfb; color: #1a1a1a; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.3); }
        .page-title { font-weight: 800; letter-spacing: -0.025em; }
    </style>
</head>
<body class="antialiased">
    <header class="fixed top-0 inset-x-0 z-50 h-20 glass flex items-center px-6 md:px-12 justify-between">
        <a href="/" class="text-xl font-black tracking-tighter hover:opacity-70 transition-opacity">
            {{ config('app.name', 'EC-NEXT') }}
        </a>
        <div class="flex items-center gap-8">
            <a href="{{ route('home') }}" class="text-xs font-bold uppercase tracking-widest opacity-60 hover:opacity-100 transition-opacity">Shop</a>
            <a href="/cart" class="opacity-60 hover:opacity-100 transition-opacity">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
            </a>
        </div>
    </header>

    <main class="pt-32 pb-24 px-6 md:px-12">
        <div class="max-w-4xl mx-auto">
            @yield('content')
        </div>
    </main>

    <footer class="py-20 border-t border-black/5 text-center">
        <p class="text-[10px] uppercase tracking-[0.5em] opacity-30">© {{ date('Y') }} {{ config('app.name') }} // Static Page System</p>
    </footer>
</body>
</html>
