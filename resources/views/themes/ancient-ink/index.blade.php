<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ancient Ink // 伝統の再定義、職人が紡ぐ和の文具</title>
    <meta name="description" content="墨が紙に染み渡る刹那の美学。Ancient Ink は、数世紀にわたり継承された技術と現代の感性が交差する、魂の宿る伝統工芸文具をお届けします。">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Shippori+Mincho:wght@400;800&family=Noto+Sans+JP:wght@300&display=swap" rel="stylesheet">
    <style>
        :root { --parchment: #FDFBF7; --ink: #1A1A1A; --vermilion: #E63946; }
        body { font-family: 'Noto Sans JP', sans-serif; background-color: var(--parchment); color: var(--ink); background-image: url('https://www.transparenttextures.com/patterns/handmade-paper.png'); }
        .font-trad { font-family: 'Shippori Mincho', serif; }
        
        /* Vertical Text Utilities */
        .vertical-rl { writing-mode: vertical-rl; text-orientation: mixed; }
        .hanko { width: 60px; height: 60px; background: var(--vermilion); color: white; display: flex; items: center; justify-content: center; font-family: 'Shippori Mincho', serif; font-weight: 800; border-radius: 2px; box-shadow: 2px 2px 0 rgba(0,0,0,0.1); }
        
        .ink-bleed-hover { transition: all 0.6s ease; }
        .ink-bleed-hover:hover { text-shadow: 0 0 10px rgba(26, 26, 26, 0.2); transform: scale(1.02); }

        .horizontal-scroll-container { display: flex; overflow-x: auto; scroll-snap-type: x mandatory; -ms-overflow-style: none; scrollbar-width: none; gap: 2rem; md:gap: 4rem; padding: 2rem 6vw; md:padding: 2rem 10vw; }
        .horizontal-scroll-container::-webkit-scrollbar { display: none; }
        .story-card { scroll-snap-align: center; flex: 0 0 clamp(280px, 80vw, 600px); }
        
        .side-anchor { position: fixed; right: 20px; md:right: 40px; top: 50%; transform: translateY(-50%); z-index: 100; }
    </style>
</head>
<body class="selection:bg-red-100">
    <div class="side-anchor flex flex-col items-center gap-12 hidden md:flex">
        <div class="hanko text-lg leading-tight">古<br>今</div>
        <div class="vertical-rl text-[10px] tracking-[0.8em] uppercase opacity-40 font-trad">REDEFINING_HERITAGE</div>
    </div>

    <header class="p-8 md:p-12 flex justify-between items-start">
        <div class="space-y-4">
            <div class="w-12 h-[2px] bg-red-600"></div>
            <div class="font-trad text-2xl font-extrabold tracking-widest">古今文具</div>
        </div>
        <nav class="flex items-center gap-6 md:gap-10 text-[11px] font-bold uppercase tracking-[0.3em] opacity-60">
            <a href="#" class="hover:text-red-600 transition-colors">History</a>
            <a href="#" class="hover:text-red-600 transition-colors">Craft</a>
            <div class="h-4 w-px bg-black/10 hidden md:block"></div>
            <a href="#" class="hover:text-red-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
            </a>
            <a href="#" class="hover:text-red-600 transition-colors">Login</a>
        </nav>
    </header>

    <main>
        <!-- Vertical Hero -->
        <section class="min-h-[80vh] md:min-h-[90vh] flex flex-col md:flex-row items-center md:items-start justify-center md:justify-start px-6 md:px-40 py-20 gap-10 md:gap-32">
            <div class="vertical-rl font-trad text-[5rem] sm:text-[8rem] md:text-[10rem] lg:text-[12rem] leading-none font-extrabold ink-bleed-hover">
                伝統の、<br><span class="text-red-600">再定義。</span>
            </div>
            <div class="max-w-md space-y-10 self-end pb-20">
                <p class="text-lg leading-[2.5] font-trad opacity-80">
                    墨が紙に染み渡る、その刹那の美学。数世紀にわたり継承された技術と、現代の感性が交差する一点の文具に、魂は宿る。
                </p>
                <div class="flex items-center gap-4">
                    <span class="w-16 h-[1px] bg-black/20"></span>
                    <a href="#" class="text-xs font-bold uppercase tracking-[0.4em] hover:text-red-600">Explore Story</a>
                </div>
            </div>
        </section>

        <!-- Horizontal Story Scroll for Products -->
        <section class="py-40 bg-black/5 border-y border-black/5 overflow-hidden">
            <div class="px-12 md:px-40 mb-20 flex justify-between items-end">
                <div class="vertical-rl font-trad text-4xl opacity-20">作品群</div>
                <div class="text-right text-[10px] tracking-[0.5em] uppercase opacity-40">Scroll to Reveal Stories</div>
            </div>
            
            <div class="horizontal-scroll-container">
                @foreach($products as $product)
                <div class="story-card group">
                    <div class="aspect-[4/5] bg-white mb-10 relative overflow-hidden p-6 md:p-12 flex items-center justify-center">
                        <img src="https://images.unsplash.com/photo-1518173946687-a4c8a9b749f5?auto=format&fit=crop&q=80&w=800" alt="{{ $product->name }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-[3s]">
                        <div class="absolute inset-0 bg-red-900/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                    <div class="space-y-6">
                        <div class="flex justify-between items-start font-trad">
                            <h3 class="text-2xl font-bold border-r-2 border-red-600 pr-4">{{ $product->name }}</h3>
                            <span class="text-sm border border-black/20 px-3 py-1">¥{{ number_format($product->price) }}</span>
                        </div>
                        <p class="text-xs leading-relaxed opacity-60">
                            職人が一本ずつ手作業で研ぎ澄ました、極上の書き味。この一本が、あなたの綴る歴史を豊かにする。
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
    </main>

    <footer class="py-40 px-12 flex flex-col items-center gap-10">
        <div class="hanko text-sm scale-150">古<br>今</div>
        <div class="text-[9px] uppercase tracking-[1em] opacity-30 mt-10">Artisanal Spirits // Since 2026</div>
    </footer>
</body>
</html>
