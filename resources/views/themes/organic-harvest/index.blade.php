<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Organic Harvest // 自然を食卓に、職人が土から育てる誠実な収穫物</title>
    <meta name="description" content="地元の土から一粒ずつ手摘みし、心を込めて届けます。Organic Harvest は、季節の純粋さと職人による農業を祝福する、最高品質のオーガニック・フード・マーケットです。">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Quicksand:wght@300;700&display=swap" rel="stylesheet">
    <style>
        :root { --forest: #1E3932; --earth: #A44231; --cream: #FFF9F0; }
        body { font-family: 'Quicksand', sans-serif; background-color: var(--cream); color: var(--forest); background-image: url('https://www.transparenttextures.com/patterns/pinstriped-suit.png'); }
        .font-serif { font-family: 'DM Serif Display', serif; }
        
        /* De-bossed effect for cards */
        .paper-deboss { background: var(--cream); border-radius: 40px; box-shadow: inset 8px 8px 16px rgba(0,0,0,0.05), inset -8px -8px 16px rgba(255,255,255,0.8); border: 1px solid rgba(0,0,0,0.02); }
        .paper-emboss { background: var(--cream); border-radius: 40px; box-shadow: 8px 8px 16px rgba(0,0,0,0.02), -8px -8px 16px rgba(255,255,255,0.8); transition: all 0.3s ease; }
        .paper-emboss:hover { transform: translateY(-4px); box-shadow: 12px 12px 24px rgba(0,0,0,0.05); }
        
        .organic-accent { width: 100px; height: 100px; background: var(--earth); border-radius: 45% 55% 70% 30% / 30% 60% 40% 70%; opacity: 0.1; position: absolute; }
    </style>
</head>
<body>
    <header class="p-6 md:p-10 flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="hidden md:block w-32"></div>
        <div class="font-serif text-4xl md:text-5xl italic text-emerald-900 tracking-tighter">Organic Harvest.</div>
        <div class="flex items-center gap-8 text-[10px] font-bold uppercase tracking-[0.4em]">
            <a href="#" class="hover:text-emerald-900 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                <span class="hidden sm:inline">Cart</span>
            </a>
            <a href="#" class="hover:text-emerald-900">Login</a>
        </div>
    </header>
    <div class="flex justify-center mb-10">
        <div class="h-px w-20 bg-emerald-900/10"></div>
    </div>

    <main class="max-w-7xl mx-auto px-10 pb-40">
        <!-- Earthy Hero -->
        <section class="py-12 md:py-24 text-center space-y-8 md:space-y-12 relative overflow-hidden px-4">
            <div class="organic-accent top-0 left-4 md:left-20"></div>
            <div class="organic-accent bottom-0 right-4 md:right-20"></div>
            
            <div class="relative z-10">
                <span class="text-[9px] md:text-[10px] uppercase tracking-[0.4em] md:tracking-[0.5em] text-emerald-800 font-bold mb-4 md:mb-6 block">Directly from the soil</span>
                <h1 class="font-serif text-5xl sm:text-[4rem] md:text-[6rem] leading-[1] md:leading-[0.9] italic text-emerald-900">
                    自然を、<br><span class="text-amber-800">食卓に。</span>
                </h1>
                <p class="max-w-xl mx-auto text-base md:text-lg leading-loose opacity-60 font-serif italic mt-8 md:mt-12 px-2">
                    地元の土から一粒ずつ手摘みし、心を込めて届けます。私たちの収穫は、季節の純粋さと職人による農業を祝福するものです。
                </p>
                <div class="pt-10 md:pt-16 flex flex-col sm:flex-row justify-center gap-4 md:gap-6">
                    <button class="px-10 py-4 md:px-12 md:py-5 bg-emerald-900 text-white rounded-full text-xs font-bold uppercase tracking-widest shadow-xl shadow-emerald-900/20">Check Harvest</button>
                    <button class="px-10 py-4 md:px-12 md:py-5 border border-emerald-900/20 rounded-full text-xs font-bold uppercase tracking-widest">Our Story</button>
                </div>
            </div>
        </section>

        <!-- Artisanal Product Grid -->
        <section class="mt-20 md:mt-40 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 md:gap-12 px-6 md:px-10">
            @foreach($products as $product)
            <div class="paper-emboss p-6 md:p-8 group cursor-pointer text-center">
                <div class="paper-deboss aspect-square mb-6 md:mb-10 overflow-hidden flex items-center justify-center p-8 md:p-10">
                    <img src="https://images.unsplash.com/photo-1542831371-29b0f74f9713?auto=format&fit=crop&q=80&w=600" alt="{{ $product->name }}" class="w-full h-full object-contain group-hover:rotate-12 transition-transform duration-700">
                </div>
                <div class="space-y-4">
                    <h3 class="font-serif text-2xl italic text-emerald-900 leading-tight">{{ $product->name }}</h3>
                    <div class="flex justify-center items-baseline gap-2">
                        <span class="text-xs opacity-40 italic">¥</span>
                        <span class="text-sm font-bold text-amber-900">{{ number_format($product->price) }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </section>
    </main>

    <footer class="py-20 text-center opacity-40 bg-emerald-900/5 mt-40">
        <div class="font-serif italic text-2xl text-emerald-900">Organic Spirits.</div>
        <div class="text-[9px] uppercase tracking-[0.6em] mt-4">Purely grown // Since 2026</div>
    </footer>
</body>
</html>
