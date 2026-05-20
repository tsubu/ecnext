<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page->title }} // Zen Garden</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;1,300&family=Noto+Sans+JP:wght@100;300&display=swap" rel="stylesheet">
    <style>
        :root { --parchment: #F4F1EA; --ink: #1A1A1A; }
        body { font-family: 'Noto Sans JP', sans-serif; background-color: var(--parchment); color: var(--ink); font-weight: 100; }
        .font-serif { font-family: 'Cormorant Garamond', serif; }
        .ma-space { padding: clamp(2rem, 5vw, 10rem); }
        .reveal-on-scroll { opacity: 0; transform: translateY(20px); transition: all 1s cubic-bezier(0.16, 1, 0.3, 1); }
        .revealed { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body class="selection:bg-green-100">
    <!-- Close / Back Button -->
    <a href="{{ url('/') }}" class="fixed top-10 left-10 z-[1000] p-4 bg-white/50 backdrop-blur-md rounded-full border border-black/5 hover:bg-white transition-all">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>

    <main class="ma-space max-w-7xl mx-auto space-y-20 pt-32">
        <!-- Page Header -->
        <header class="text-center space-y-4 mb-32 reveal-on-scroll">
            <span class="text-[10px] uppercase tracking-[0.5em] opacity-40">
                {{ $page->category?->name ?? __('Dynamic Segment') }}
            </span>
            <h1 class="font-serif text-5xl md:text-7xl italic leading-none">{{ $page->title }}</h1>
            <div class="w-12 h-px bg-black/10 mx-auto mt-10"></div>
        </header>

        <!-- Dynamic Blocks -->
        <div class="space-y-32">
            @foreach($page->layouts as $layout)
                @if($layout->blockInstance && $layout->blockInstance->is_active)
                    <div class="reveal-on-scroll">
                        <x-shop.renderer :instance="$layout->blockInstance" :settings="$layout->settings_override ?? []" />
                    </div>
                @endif
            @endforeach
        </div>
    </main>

    <footer class="py-40 text-center space-y-4 opacity-30">
        <div class="font-serif italic text-2xl">Zen.</div>
        <div class="text-[9px] uppercase tracking-[1em]">{{ date('Y') }} // System Design</div>
    </footer>

    <script>
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                }
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal-on-scroll').forEach(el => observer.observe(el));
    </script>
</body>
</html>
