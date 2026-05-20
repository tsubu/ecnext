<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Silk & Satin // 肌に語り継ぐ、究極のオーガニックシルク・コレクション</title>
    <meta name="description" content="流行を追うのではなく、普遍的な心地よさを。Silk & Satin は、最上級のシルクと職人の技術を融合させた、第二の肌のような優しさを提供するファッション・アトリエです。">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;1,400&family=Tenor+Sans&display=swap" rel="stylesheet">
    <style>
        :root { --nude: #FAF9F6; --silk: #FDF2F0; --espresso: #2D2424; }
        body { font-family: 'Tenor Sans', sans-serif; background-color: var(--nude); color: var(--espresso); overflow-x: hidden; }
        .font-serif { font-family: 'Playfair Display', serif; }
        
        .sheer { background: rgba(253, 242, 240, 0.4); backdrop-filter: blur(10px); }
        .drape-shadow { filter: drop-shadow(0 40px 60px rgba(45, 36, 36, 0.05)); }
        
        .fade-in { animation: fadeIn 2s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        
        .vertical-nav { position: fixed; left: 40px; top: 50%; transform: translateY(-50%); z-index: 100; writing-mode: vertical-rl; }
    </style>
</head>
<body class="selection:bg-pink-100">
    <div class="vertical-nav hidden md:flex items-center gap-12 text-[9px] uppercase tracking-[0.6em] opacity-40">
        <a href="#" class="hover:opacity-100 transition-opacity">Philosophy</a>
        <div class="h-20 w-px bg-espresso/20"></div>
        <a href="#" class="hover:opacity-100 transition-opacity">Archive</a>
    </div>

    <header class="p-6 md:p-12 absolute top-0 w-full flex justify-between items-center z-50">
        <div class="hidden md:block w-1/3"></div>
        <div class="text-xl md:text-3xl font-serif italic tracking-widest uppercase text-center w-auto md:w-1/3">Silk & Satin.</div>
        <div class="flex justify-end gap-6 md:gap-10 text-[9px] uppercase tracking-[0.4em] w-auto md:w-1/3">
            <a href="#" class="hover:opacity-50 transition-opacity">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
            </a>
            <a href="#" class="hover:opacity-50 transition-opacity hidden sm:block">Login</a>
        </div>
    </header>

    <main class="pt-40">
        <!-- Centered Hero -->
        <section class="max-w-4xl mx-auto px-6 md:px-10 min-h-[70vh] md:min-h-[80vh] flex flex-col items-center justify-center text-center space-y-8 md:space-y-12 fade-in">
            <span class="text-[9px] md:text-[10px] uppercase tracking-[0.6em] md:tracking-[0.8em] opacity-40">Crafted with infinite patience</span>
            <h1 class="font-serif text-5xl sm:text-[6rem] md:text-[8rem] leading-none italic drape-shadow">
                肌に、<br>語り継ぐ。
            </h1>
            <div class="max-w-xl text-base md:text-lg leading-relaxed opacity-60 font-serif italic px-4">
                纏うたびに、心がほどけていく。Silk & Satin は、最上級のオーガニックシルクと職人の技術によって、第二の肌のような優しさを届けます。
            </div>
            <div class="pt-10">
                <button class="px-16 py-6 border border-espresso text-[11px] uppercase tracking-[0.4em] hover:bg-espresso hover:text-white transition-all">View Our Process</button>
            </div>
        </section>

        <!-- Dynamic Product Showcase -->
        <section class="mt-40 max-w-[1400px] mx-auto px-10 pb-60">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($products as $product)
                <div class="group cursor-pointer">
                    <div class="aspect-[4/5] sheer mb-8 relative overflow-hidden flex items-center justify-center p-6 md:p-12">
                        <img src="https://images.unsplash.com/photo-1549439602-43bbcb4d701a?auto=format&fit=crop&q=80&w=800" alt="{{ $product->name }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-[4s] opacity-80 group-hover:opacity-100">
                    </div>
                    <div class="text-center space-y-2">
                        <h3 class="text-xl font-serif italic">{{ $product->name }}</h3>
                        <div class="flex flex-col items-center gap-2">
                            <span class="text-[9px] uppercase tracking-widest opacity-40">Available in Silk / Wool</span>
                            <span class="text-sm font-light">¥{{ number_format($product->price) }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        <!-- Atmospheric Image -->
        <section class="w-full h-[60vh] md:h-screen overflow-hidden relative">
            <img src="https://images.unsplash.com/photo-1518173946687-a4c8a9b749f5?auto=format&fit=crop&q=80&w=2000" alt="Silk & Satin アトモスフィア" class="w-full h-full object-cover grayscale opacity-20">
            <div class="absolute inset-0 flex items-center justify-center text-center p-6 md:p-10">
                <div class="sheer p-8 md:p-20 max-w-2xl border border-white/20">
                    <h2 class="text-3xl md:text-5xl font-serif italic mb-6 md:mb-8 italic">纏うことは、静寂を選ぶこと。</h2>
                    <p class="text-[13px] md:text-sm leading-loose opacity-60">
                        私たちは流行を追いません。時を超えて愛される、普遍的な心地よさだけを追求し続けます。
                    </p>
                </div>
            </div>
        </section>
    </main>

    <footer class="py-20 text-center opacity-30 mt-40 border-t border-espresso/5">
        <div class="text-[10px] uppercase font-light tracking-[1.5em] mb-4">Elegance is refusal.</div>
        <div class="text-[8px] uppercase tracking-[0.5em]">© 2026 Silk & Satin Atelier</div>
    </footer>
</body>
</html>
