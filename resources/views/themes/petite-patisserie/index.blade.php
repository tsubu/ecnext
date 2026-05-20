<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Petite Patisserie // 甘い魔法が解ける場所、究極のスイーツ・コレクション</title>
    <meta name="description" content="一口食べれば、そこはパリの街角。Petite Patisserie は、厳選された素材と遊び心から生まれる、宝石のように美しいスイーツと幸福な時間をお届けします。">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Quicksand:wght@300;700&display=swap" rel="stylesheet">
    <style>
        :root { --macaron-pink: #FFD1DC; --butter-cream: #FFFDD0; --strawberry: #FF4D6D; --vanilla: #FFFFFF; }
        body { font-family: 'Quicksand', sans-serif; background-color: #FFF9FA; color: #5D4037; overflow-x: hidden; }
        .font-sweet { font-family: 'Pacifico', cursive; }
        
        .soft-glass { background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(10px); border-radius: 50px; border: 2px solid rgba(255, 182, 193, 0.2); }
        .pill-button { border-radius: 9999px; background: var(--strawberry); color: white; padding: 12px 30px; font-weight: 700; transition: transform 0.3s; box-shadow: 0 10px 20px rgba(255, 77, 109, 0.2); }
        .pill-button:hover { transform: scale(1.05); }
        
        @keyframes float-gentle { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-15px); } }
        .floating-sweet { animation: float-gentle 4s ease-in-out infinite; }
        
        .confection-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 3rem; }
    </style>
</head>
<body class="selection:bg-pink-200">
    <header class="p-6 md:p-8 flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="hidden md:block w-32"></div>
        <div class="font-sweet text-4xl md:text-5xl text-pink-500">Petite Patisserie</div>
        <div class="flex items-center gap-6 text-[11px] font-bold uppercase tracking-widest text-pink-300">
            <a href="#" class="hover:text-pink-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
            </a>
            <a href="#" class="hover:text-pink-500 transition-colors">Login</a>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-6 pb-40">
        <!-- Whimsical Hero -->
        <section class="py-20 text-center space-y-12">
            <div class="relative inline-block">
                <div class="w-64 h-64 bg-pink-100 rounded-full absolute -top-10 -left-10 opacity-30 blur-2xl"></div>
                <div class="w-48 h-48 bg-yellow-100 rounded-full absolute -bottom-10 -right-10 opacity-30 blur-2xl"></div>
                <h1 class="font-sweet text-4xl sm:text-[5rem] md:text-[7rem] leading-tight md:leading-none text-pink-600 relative z-10 px-4">
                    甘い魔法、<br>はじめよう。
                </h1>
            </div>
            
            <p class="max-w-xl mx-auto text-base md:text-lg leading-relaxed text-pink-900/60 font-medium italic mt-8">
                一口食べれば、そこはパリの街角。Petite Patisserie は、厳選された素材と遊び心から生まれる、宝石のようなスイーツをお届けします。
            </p>
            
            <div class="pt-10 flex justify-center">
                <button class="pill-button text-sm uppercase tracking-widest">Get Your Sweets</button>
            </div>
        </section>

        <!-- Confection Product Grid -->
        <section class="mt-20 md:mt-40 confection-grid">
            @foreach($products as $product)
            <div class="soft-glass p-8 md:p-10 flex flex-col items-center text-center group cursor-pointer hover:bg-white transition-colors">
                <div class="floating-sweet mb-8 md:mb-10 w-full aspect-square flex items-center justify-center p-4 md:p-0">
                    <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&q=80&w=600" alt="{{ $product->name }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-700">
                </div>
                <div class="space-y-4 w-full">
                    <div class="w-12 h-1 bg-pink-200 mx-auto rounded-full"></div>
                    <h3 class="font-bold text-xl text-pink-900 leading-tight">{{ $product->name }}</h3>
                    <div class="text-xs font-bold text-pink-400 bg-pink-50 rounded-full py-2 px-6 inline-block">
                        Chef's Selection // ¥{{ number_format($product->price) }}
                    </div>
                </div>
            </div>
            @endforeach
        </section>
    </main>

    <footer class="py-20 text-center space-y-6 opacity-30 mt-40 border-t border-pink-100">
        <div class="font-sweet text-2xl text-pink-600">Sweet Moments.</div>
        <div class="text-[9px] uppercase tracking-[1em]">Handcrafted with love // 2026</div>
    </footer>
</body>
</html>
