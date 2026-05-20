<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aqua Marine // 大海原を遊び尽くす、最高峰のサーフ＆アドベンチャーギア</title>
    <meta name="description" content="冒険は、波の向こうにある。Aqua Marine は、耐久性、機能性、そしてスタイルを兼ね備え、海を愛するすべての人へ最高級のソルトウォーター・ギアを提供します。">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@200;900&display=swap" rel="stylesheet">
    <style>
        :root { --deep-sea: #002B36; --wave: #00B4D8; --foam: #CAF0F8; }
        body { font-family: 'Outfit', sans-serif; background-color: var(--deep-sea); color: #fff; overflow-x: hidden; }
        
        .diagonal-slice { clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%); }
        .diagonal-slice-reverse { clip-path: polygon(0 15%, 100% 0, 100% 100%, 0 100%); }
        
        .wave-text { text-transform: uppercase; font-weight: 900; letter-spacing: -0.05em; font-size: clamp(4rem, 15vw, 15rem); line-height: 0.8; }
        .ripple { position: relative; overflow: hidden; }
        .ripple::after { content: ''; position: absolute; inset: 0; background: radial-gradient(circle, rgba(202, 240, 248, 0.2) 0%, transparent 70%); transform: scale(0); transition: transform 0.6s ease-out; }
        .ripple:hover::after { transform: scale(3); }
        
        .floating { animation: float 6s ease-in-out infinite; }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }
    </style>
</head>
<body class="selection:bg-cyan-500">
    <header class="p-6 md:p-10 flex justify-between items-center fixed top-0 w-full z-50 mix-blend-difference">
        <div class="text-xl md:text-3xl font-black italic tracking-tighter">AQUA_MARINE.</div>
        <nav class="flex items-center gap-4 md:gap-10 text-[10px] font-bold uppercase tracking-[0.3em] font-light">
            <a href="#" class="hover:text-cyan-400 hidden sm:block">Surf</a>
            <a href="#" class="hover:text-cyan-400 hidden sm:block">Depth</a>
            <a href="#" class="hover:text-cyan-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
            </a>
            <a href="#" class="hover:text-cyan-400">Join</a>
        </nav>
    </header>

    <main>
        <!-- Dynamic Hero -->
        <section class="min-h-[70vh] md:h-screen bg-cyan-500 diagonal-slice flex items-center justify-center relative overflow-hidden pt-20">
            <div class="absolute inset-0 opacity-20">
                <img src="https://images.unsplash.com/photo-1505118380757-91f5f45d8de6?auto=format&fit=crop&q=80&w=2000" alt="Aqua Marine オーシャンアドベンチャー" class="w-full h-full object-cover">
            </div>
            <div class="relative z-10 text-center px-6">
                <div class="text-[10px] md:text-xs uppercase tracking-[0.5em] md:tracking-[1em] mb-6 md:mb-10 opacity-60">Ride the pulse of the ocean</div>
                <h1 class="wave-text">大海を、<br>遊びつくせ。</h1>
                <div class="mt-12 md:mt-20 flex justify-center gap-10">
                    <button class="px-8 py-4 md:px-12 md:py-6 bg-white text-cyan-900 font-black uppercase text-xs tracking-widest hover:bg-foam transition-colors">Gear Up</button>
                </div>
            </div>
        </section>

        <!-- Product Surf Gallery -->
        <section class="py-40 px-10 max-w-[1800px] mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-20">
                @foreach($products as $index => $product)
                <div class="ripple group cursor-pointer {{ $index % 2 != 0 ? 'lg:mt-40' : '' }}">
                    <div class="aspect-[4/5] bg-white/5 relative overflow-hidden mb-12 flex items-center justify-center p-6 md:p-10">
                        <img src="https://images.unsplash.com/photo-1502680390469-be75c86b636f?auto=format&fit=crop&q=80&w=800" alt="{{ $product->name }}" class="w-full h-full object-contain floating group-hover:scale-110 transition-transform duration-[2s]">
                        <div class="absolute bottom-6 left-6 text-[10px] font-black italic opacity-20 tracking-widest">WAVE_PRO_0{{ $index + 1 }}</div>
                    </div>
                    <div class="space-y-4">
                        <h3 class="text-4xl font-black uppercase tracking-tighter italic border-l-8 border-cyan-500 pl-6">{{ $product->name }}</h3>
                        <div class="flex items-center justify-between opacity-60 font-light">
                            <span>Saltwater Grade</span>
                            <span class="text-2xl font-black text-cyan-400 tracking-tighter italic">¥{{ number_format($product->price) }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        <!-- Dynamic Call to Action -->
        <section class="min-h-[50vh] md:min-h-[60vh] bg-white text-cyan-900 diagonal-slice-reverse flex items-center px-6 md:px-40 py-20 md:py-40">
            <div class="max-w-4xl space-y-8 md:space-y-12">
                <h2 class="text-4xl md:text-7xl font-black uppercase leading-none italic">
                    冒険は、<br>波の向こうにある。
                </h2>
                <p class="text-lg md:text-xl leading-relaxed opacity-80">
                    Aqua Marine は、海を愛するすべての人に、最高級のギアを提供します。耐久性、機能性、そしてスタイル。妥協のない性能を体感してください。
                </p>
                <div class="h-1 lg:w-96 bg-cyan-900/20"></div>
            </div>
        </section>
    </main>

    <footer class="py-20 text-center opacity-20 border-t border-white/5 mt-40">
        <div class="text-[10px] uppercase font-black tracking-[1em]">Stay Salty // 2026 Aqua Marine</div>
    </footer>
</body>
</html>
