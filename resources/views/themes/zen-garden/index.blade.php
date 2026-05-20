<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Zen Garden') }} // {{ __('zen_garden_tagline') }}</title>
    <meta name="description" content="{{ __('zen_garden_description') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;1,300&family=Noto+Sans+JP:wght@100;300&display=swap" rel="stylesheet">
    <style>
        :root { --parchment: #F4F1EA; --ink: #1A1A1A; --moss: #4A5D23; }
        body { font-family: 'Noto Sans JP', sans-serif; background-color: var(--parchment); color: var(--ink); font-weight: 100; overflow-x: hidden; }
        .font-serif { font-family: 'Cormorant Garamond', serif; }
        
        .vertical-text { writing-mode: vertical-rl; text-orientation: mixed; }
        .ma-space { padding: clamp(4rem, 10vw, 15rem); }
        
        .nav-trigger { position: fixed; top: 50px; left: 50px; z-index: 1000; cursor: pointer; transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
        .nav-trigger:hover { transform: rotate(90deg); }
        
        .reveal-on-scroll { opacity: 0; transform: translateY(30px); transition: all 1.5s cubic-bezier(0.16, 1, 0.3, 1); }
        .revealed { opacity: 1; transform: translateY(0); }

        .price-tag { font-family: 'Cormorant Garamond', serif; font-style: italic; font-weight: 300; }
        
        /* Thin Dividers */
        .hairline-v { width: 1px; height: 100px; background: var(--ink); opacity: 0.1; }
    </style>
</head>
<body class="selection:bg-green-100">
    <div class="nav-trigger group !left-6 !top-6 md:!left-[50px] md:!top-[50px]">
        <div class="w-8 h-[1px] bg-black mb-2 transition-all group-hover:w-12"></div>
        <div class="w-12 h-[1px] bg-black mb-2"></div>
        <div class="w-8 h-[1px] bg-black transition-all group-hover:w-12"></div>
    </div>

    <div class="fixed top-6 right-6 md:top-[50px] md:right-[50px] z-[1000] flex items-center gap-6 md:gap-10">
        <a href="#" class="opacity-40 hover:opacity-100 transition-opacity">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
        </a>
        <a href="#" class="text-[9px] uppercase tracking-[0.4em] opacity-40 hover:opacity-100 transition-opacity">{{ __('Login') }}</a>
    </div>

    <main>
        <!-- Horizontal Hero -->
        <section class="min-h-screen flex items-center justify-center ma-space relative">
            <div class="absolute top-20 right-20 text-[10px] uppercase tracking-[1em] vertical-text opacity-40">
                {{ __('SCENE_01') }}: {{ __('RESIDENCE_OF_SILENCE') }}
            </div>
            
            <div class="grid grid-cols-12 gap-0 items-center w-full">
                <div class="col-span-12 lg:col-span-7 pr-0 lg:pr-20 mb-12 lg:mb-0">
                    <div class="aspect-[4/5] overflow-hidden bg-white/50 relative group">
                        <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&q=80&w=1200" alt="Zen Garden 彫刻的ミニマルチェア" class="w-full h-full object-cover transition-transform duration-[4s] group-hover:scale-105">
                        <div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-[#F4F1EA] to-transparent"></div>
                    </div>
                </div>
                <div class="col-span-12 lg:col-span-5 space-y-8 md:space-y-12">
                    <h1 class="font-serif text-5xl md:text-8xl leading-none italic reveal-on-scroll">
                        {!! __('zen_garden_hero_title') !!}
                    </h1>
                    <p class="max-w-sm text-sm leading-loose opacity-60 reveal-on-scroll">
                        {{ __('zen_garden_hero_description') }}
                    </p>
                    <div class="pt-6 md:pt-10 flex items-center gap-6 reveal-on-scroll">
                        <div class="hairline-v"></div>
                        <button class="text-[10px] uppercase tracking-[0.5em] border-b border-black pb-2 hover:opacity-50 transition-opacity">{{ __('Discover Collection') }}</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Staggered Product Gallery -->
        <section class="ma-space space-y-20 md:space-y-[40vh]">
            @foreach($products as $index => $product)
            <div class="flex {{ $index % 2 == 0 ? 'justify-start' : 'justify-end' }} reveal-on-scroll">
                <div class="max-w-2xl group cursor-pointer">
                    <div class="relative mb-10 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1592078615290-033ee584e267?auto=format&fit=crop&q=80&w=800" alt="{{ $product->name }}" class="w-full grayscale hover:grayscale-0 transition-all duration-1000">
                        <div class="absolute top-10 {{ $index % 2 == 0 ? 'right-10' : 'left-10' }} text-[10px] font-serif italic text-white/50">00.{{ $index + 1 }}</div>
                    </div>
                    <div class="flex justify-between items-end border-b border-black/5 pb-8">
                        <div>
                            <span class="text-[9px] uppercase tracking-[0.4em] opacity-40 mb-2 block">{{ __('Available Piece') }}</span>
                            <h3 class="text-3xl font-light tracking-tight">{{ $product->name }}</h3>
                        </div>
                        <span class="price-tag text-2xl">¥{{ number_format($product->price) }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </section>
    </main>

    <footer class="py-40 text-center space-y-10 opacity-30">
        <div class="font-serif italic text-4xl italic">Zen.</div>
        <div class="text-[9px] uppercase tracking-[1em]">{{ __('Existence in Void') }} // 2026</div>
    </footer>

    <script>
        // Simple Intersection Observer to reveal elements
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
