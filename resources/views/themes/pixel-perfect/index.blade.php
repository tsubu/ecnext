<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pixel Perfect // 視覚をハックし、常識をピクセル単位で再構築する</title>
    <meta name="description" content="WE REDEFINE VOIDS. Pixel Perfect は、常識をピクセル単位で分解し、新たな視覚体験を再構築するクリエイティブ・エージェンシーです。高衝撃なデジタルアセットを提供します。">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@400;900&family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root { --studio-white: #ffffff; --ink: #000000; --pixel-red: #FF3E00; --logic-blue: #0066FF; }
        body { font-family: 'Inter', sans-serif; background-color: var(--studio-white); color: var(--ink); overflow-x: hidden; }
        .font-bold-display { font-family: 'Unbounded', sans-serif; }
        
        .brutal-header { position: sticky; top: 0; background: var(--pixel-red); color: white; padding: 10px 40px; font-family: 'Unbounded', sans-serif; font-weight: 900; text-transform: uppercase; font-size: 10px; z-index: 1000; display: flex; justify-content: space-between; }
        .grid-line { border: 1px solid rgba(0,0,0,0.1); }
        .giant-text { font-size: clamp(5rem, 20vw, 25rem); line-height: 0.75; letter-spacing: -0.05em; font-weight: 900; }
        
        .pixel-card { border: 2px solid var(--ink); background: var(--studio-white); box-shadow: 12px 12px 0 var(--ink); transition: all 0.2s; }
        .pixel-card:hover { transform: translate(-4px, -4px); box-shadow: 16px 16px 0 var(--logic-blue); }
        
        @keyframes rotate-slow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        .rotating-stamp { animation: rotate-slow 20s linear infinite; }
    </style>
</head>
<body class="selection:bg-red-500 selection:text-white">
    <div class="brutal-header !px-4 md:!px-10">
        <span class="truncate pr-4">Pixel Perfect // Factory</span>
        <div class="flex gap-4 md:gap-10">
            <a href="#" class="hover:underline">Cart [0]</a>
            <a href="#" class="hover:underline hidden sm:inline">Secure_Login</a>
        </div>
        <span class="hidden md:block">Status: Overdrive</span>
        <span class="hidden lg:block">Local Time: {{ date('H:i') }}</span>
    </div>

    <header class="p-12 md:p-20 grid grid-cols-12 items-end border-b-2 border-black">
        <div class="col-span-12 lg:col-span-8">
            <h1 class="font-bold-display text-6xl sm:text-8xl md:text-[12rem] lg:text-[15rem] leading-none tracking-tighter">
                PIXEL<br>PERF<span class="text-blue-600">ECT.</span>
            </h1>
        </div>
        <div class="col-span-12 lg:col-span-4 flex flex-col items-center lg:items-end gap-10 pb-4 mt-12 lg:mt-0">
            <div class="w-24 h-24 md:w-32 md:h-32 bg-black text-white p-4 font-bold-display text-[8px] md:text-[10px] leading-tight flex items-center justify-center text-center">
                WE<br>REDEFINE<br>VOIDS.
            </div>
            <p class="text-center lg:text-right text-xs md:text-sm font-bold max-w-[200px] leading-relaxed">
                常識をピクセル単位で分解し、新たな視覚体験を再構築するエージェンシー。
            </p>
        </div>
    </header>

    <main class="border-x-2 border-black mx-4 md:mx-10 pb-40">
        <!-- Brutal Hero Section -->
        <section class="p-8 md:p-20 border-b-2 border-black bg-blue-600 text-white relative overflow-hidden">
            <div class="relative z-10">
                <div class="text-[8px] md:text-[10px] font-bold-display mb-8 md:mb-10 tracking-[0.5em] md:tracking-[1em]">STRATEGY_01: RADICAL_IMPACT</div>
                <h2 class="font-bold-display text-4xl md:text-9xl leading-[0.8] tracking-tighter mb-12 md:mb-20 italic">
                    視覚を、<br>ハックせよ。
                </h2>
                <button class="bg-black text-white px-10 py-6 md:px-12 md:py-8 font-bold-display text-[10px] md:text-xs uppercase hover:bg-white hover:text-black transition-all">Start Project</button>
            </div>
            <div class="absolute -right-20 -bottom-20 opacity-10 md:opacity-20">
                <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&q=80&w=1200" alt="Pixel Perfect デザインコア宇宙" class="w-[400px] md:w-[800px] rotating-stamp">
            </div>
        </section>

        <!-- Dynamic Product Inventory -->
        <section class="p-12 md:p-20">
            <div class="flex items-center gap-10 mb-20">
                <div class="h-10 w-10 bg-red-600"></div>
                <h3 class="font-bold-display text-4xl uppercase">Assets_Available.</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 md:gap-20">
                @foreach($products as $product)
                <div class="pixel-card p-6 md:p-10 group cursor-pointer">
                    <div class="aspect-square bg-slate-100 mb-8 border-2 border-black overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&q=80&w=800" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-125 transition-transform duration-[2s]">
                        <div class="absolute top-4 right-4 bg-white border-2 border-black px-4 py-2 font-bold-display text-[8px]">+ADD</div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex justify-between items-start">
                            <h4 class="font-bold-display text-xl leading-none uppercase">{{ $product->name }}</h4>
                            <span class="font-bold-display text-blue-600 text-sm">¥{{ number_format($product->price) }}</span>
                        </div>
                        <div class="h-1 w-full bg-black/5"></div>
                        <div class="text-[8px] font-bold opacity-40 uppercase tracking-widest">Type: Digital_Native // REF: {{ $product->sku }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
    </main>

    <footer class="p-20 bg-black text-white grid grid-cols-1 md:grid-cols-3 gap-20">
        <div class="font-bold-display text-2xl tracking-tighter">PIXEL PERFECT.</div>
        <div class="text-xs space-y-4 opacity-40">
            <div>TOKYO / SHIBUYA / 001</div>
            <div>STAY_CREATIVE_STAY_BOLD</div>
        </div>
        <div class="text-right">
            <span class="font-bold-display text-xs bg-red-600 p-4">END_MISSION</span>
        </div>
    </footer>
</body>
</html>
