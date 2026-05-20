<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Neon Pulse // 感性を加速させる、次世代ゲーミングギア</title>
    <meta name="description" content="既存のグリッドを破壊し、感覚を同期させろ。Neon Pulse は、単なるハードウェアではなく、あなたのデジタルな神経系の一部となる高精度ゲーミングギアを提供します。">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;800&family=Space+Grotesk:wght@300;700&display=swap" rel="stylesheet">
    <style>
        :root { --neon-cyan: #00fbfb; --neon-magenta: #ff00ff; --void: #0a0a0a; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--void); color: #fff; overflow-x: hidden; }
        .font-tech { font-family: 'Space Grotesk', sans-serif; }
        
        /* Scanline Texture Overlay */
        body::before { content: " "; position: fixed; top: 0; left: 0; bottom: 0; right: 0; background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%), linear-gradient(90deg, rgba(255, 0, 0, 0.06), rgba(0, 255, 0, 0.02), rgba(0, 0, 255, 0.06)); z-index: 100; background-size: 100% 2px, 3px 100%; pointer-events: none; }

        .hud-header { position: fixed; top: 10px; md:top: 40px; left: 50%; transform: translateX(-50%); z-index: 1000; width: calc(100% - 20px); md:width: calc(100% - 80px); max-width: 1400px; background: rgba(10, 10, 10, 0.9); backdrop-filter: blur(20px); border: 2px solid var(--neon-cyan); padding: 10px 20px; md:padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 0 30px rgba(0, 251, 251, 0.1); }
        .brutal-border { border: 4px solid var(--neon-cyan); position: relative; }
        .brutal-border::after { content: ''; position: absolute; bottom: -12px; right: -12px; width: 100%; height: 100%; border: 4px solid var(--neon-magenta); z-index: -1; opacity: 0.3; }
        
        @keyframes glitch { 0% { text-shadow: 2px 0 var(--neon-cyan), -2px 0 var(--neon-magenta); } 25% { text-shadow: -2px 0 var(--neon-cyan), 2px 0 var(--neon-magenta); } 50% { text-shadow: 2px -2px var(--neon-cyan), -2px 2px var(--neon-magenta); } 100% { text-shadow: 2px 0 var(--neon-cyan), -2px 0 var(--neon-magenta); } }
        .glitch-text { animation: glitch 0.5s infinite; }
        .vertical-flourish { writing-mode: vertical-rl; text-orientation: mixed; }
    </style>
</head>
<body class="selection:bg-cyan-500/30">
    <header class="hud-header">
        <div class="font-tech text-xl md:text-3xl font-bold tracking-tighter text-cyan-400">PULSE // OS</div>
        <nav class="hidden md:flex gap-8 md:gap-12 font-tech text-[10px] font-bold uppercase tracking-[0.4em]">
            <a href="#" class="hover:text-cyan-400 transition-colors">GEAR</a>
            <a href="#" class="hover:text-cyan-400 transition-colors">NEURAL</a>
            <a href="#" class="hover:text-cyan-400 transition-colors">CORE</a>
        </nav>
        <div class="flex items-center gap-4 md:gap-8">
            <a href="#" class="text-cyan-400 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
            </a>
            <div class="w-8 h-8 md:w-10 md:h-10 bg-cyan-500/10 border border-cyan-500 p-1 md:p-2 text-[6px] md:text-[8px] font-bold text-cyan-400 flex items-center justify-center cursor-pointer hover:bg-cyan-500 hover:text-black transition-all">USER</div>
        </div>
    </header>

    <main class="mt-32 md:mt-48 max-w-[1400px] mx-auto px-4 md:px-10 pb-40">
        <!-- Hero Section -->
        <section class="grid grid-cols-12 gap-6 md:gap-10 items-end py-12 md:py-20">
            <div class="col-span-12 lg:col-span-8 space-y-8 md:space-y-12">
                <div class="flex items-center gap-4">
                    <span class="text-[8px] md:text-[10px] bg-pink-600 text-white px-3 py-1 font-bold">LIVE STATUS: ACTIVE</span>
                    <div class="h-px flex-grow bg-white/10"></div>
                </div>
                <h1 class="font-tech text-5xl md:text-8xl lg:text-[11rem] leading-[0.9] font-bold tracking-tighter glitch-text">
                    感性を、<br><span class="text-cyan-400">加速させろ。</span>
                </h1>
                <p class="max-w-xl text-slate-400 text-base md:text-lg leading-relaxed">
                    既存のグリッドを破壊し、感覚を同期させろ。Neon Pulse は、単なるハードウェアではなく、あなたのデジタルな神経系の一部となる。
                </p>
            </div>
            <div class="col-span-12 lg:col-span-4 relative group mt-12 lg:mt-0">
                <div class="brutal-border bg-slate-900 aspect-square overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&q=80&w=800" alt="Neon Pulse ハイエンドゲーミングコントローラー" class="w-full h-full object-cover group-hover:scale-125 transition-transform duration-[3s] grayscale hover:grayscale-0">
                </div>
                <div class="absolute -top-10 -right-2 md:-right-4 vertical-flourish text-[8px] md:text-[10px] font-bold text-slate-500 tracking-[1em] uppercase">SYSTEM_OVERRIDE_V4</div>
            </div>
        </section>

        <!-- Dynamic Product Grid/List -->
        <section class="mt-40 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
             @foreach($products as $product)
             <a href="{{ route('products.show', $product->slug) }}" class="group relative p-1 bg-white/5 hover:bg-cyan-500/10 transition-all border-l-4 border-cyan-500 cursor-pointer block">
                <div class="aspect-[5/6] bg-black mb-6 overflow-hidden relative">
                    <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&q=80&w=800" alt="{{ $product->name }}" class="w-full h-full object-cover opacity-60 group-hover:opacity-100 transition-opacity">
                    <div class="absolute bottom-4 left-4 font-tech text-[10px] font-bold text-cyan-400 bg-black/80 px-4 py-2 border border-cyan-400">DATA_LOADED</div>
                </div>
                <div class="px-4 pb-6 space-y-2">
                    <h3 class="font-tech font-bold text-xl uppercase tracking-tighter">{{ $product->name }}</h3>
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-bold text-slate-500">ID: {{ $product->sku }}</span>
                        <div class="flex flex-col items-end">
                            @if($product->defaultVariant && $product->defaultVariant->is_on_sale)
                                <span class="text-rose-500 font-bold font-tech text-lg shadow-[0_0_10px_rgba(244,63,94,0.3)]">¥{{ number_format($product->defaultVariant->active_price) }}</span>
                                <span class="text-[10px] text-slate-600 line-through">¥{{ number_format($product->defaultVariant->price) }}</span>
                            @else
                                <span class="text-cyan-400 font-bold font-tech">¥{{ number_format($product->price) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                @if($product->defaultVariant && $product->defaultVariant->is_on_sale)
                    <div class="absolute -top-2 -left-2 bg-rose-600 text-white text-[8px] font-black px-3 py-1 uppercase tracking-widest shadow-lg shadow-rose-600/50 z-10 glitch-text">
                        SALE_OVERRIDE
                    </div>
                @endif
                <!-- Tech Greebles -->
                <div class="absolute top-2 right-2 flex gap-1">
                    <div class="w-1 h-1 bg-cyan-500"></div>
                    <div class="w-1 h-1 bg-cyan-500 opacity-20"></div>
                </div>
             </a>
             @endforeach
        </section>
    </main>

    <!-- Dynamic Blocks from Index Page -->
    @if(isset($indexPage) && $indexPage && $indexPage->layouts->count() > 0)
    <section class="max-w-[1600px] mx-auto px-10 py-20">
        @foreach($indexPage->layouts as $layout)
            @if($layout->blockInstance && $layout->blockInstance->is_active)
                <x-shop.renderer :instance="$layout->blockInstance" :settings="$layout->settings_override ?? []" />
            @endif
        @endforeach
    </section>
    @endif
    <footer class="mt-40 border-t border-cyan-500/20 py-20 px-10 flex justify-between items-center bg-black">
        <div class="font-tech text-xs font-bold text-slate-500 tracking-[1em]">END_TRANSMISSION</div>
        <div class="text-[8px] font-bold text-slate-700">© 2026 PULSE // NEXUS_PROTOCOL</div>
    </footer>
</body>
</html>
