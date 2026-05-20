<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Luxe Aura // 永遠の輝きを纏う、最高級ジュエリーアトリエ</title>
    <meta name="description" content="一点の妥協も許さない職人の手によって、光は形を得る。Luxe Aura は、あなたの中に眠る本質を輝かせるための、光り輝くジュエリーと特別な体験を約束します。">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:ital,wght@0,400;0,900;1,400&family=Montserrat:wght@200;600&display=swap" rel="stylesheet">
    <style>
        :root { --atlier-black: #161213; --atlier-gold: #e9c349; --atlier-silk: #301934; }
        body { font-family: 'Montserrat', sans-serif; background-color: var(--atlier-black); color: #fff; overflow-x: hidden; }
        .font-display { font-family: 'Bodoni Moda', serif; }
        
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .gold-gradient { background: linear-gradient(45deg, #af8d11, #e9c349, #af8d11); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .silk-overlay { background: radial-gradient(circle at top right, rgba(48, 25, 52, 0.4), transparent); }
        
        .asymmetric-grid { display: grid; grid-template-columns: repeat(12, 1fr); gap: 2rem; }
        .float-image { transform: translateY(0); transition: transform 2s cubic-bezier(0.16, 1, 0.3, 1); }
        .float-image:hover { transform: translateY(-30px); }
    </style>
</head>
<body class="selection:bg-amber-900 silk-overlay">
    <header class="p-6 md:p-12 absolute top-0 w-full flex justify-between items-center z-50">
        <div class="font-display text-3xl md:text-4xl italic gold-gradient tracking-tighter">Luxe Aura.</div>
        <div class="flex items-center gap-6 md:gap-10 text-[10px] font-bold uppercase tracking-[0.4em]">
            <a href="#" class="hover:text-amber-400 hidden sm:block">Atelier</a>
            <a href="#" class="hover:text-amber-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
            </a>
            <a href="#" class="hover:text-amber-400">Login</a>
        </div>
    </header>

    <main class="pt-40">
        <!-- Editorial Hero -->
        <section class="max-w-[1600px] mx-auto px-10 min-h-screen flex items-center relative">
            <div class="asymmetric-grid w-full">
                <div class="col-span-12 lg:col-span-6 relative z-10 pt-10 md:pt-20">
                    <span class="text-[10px] md:text-xs uppercase tracking-[0.8em] text-amber-500 mb-6 md:mb-8 block">Collection // Eternal Bloom</span>
                    <h1 class="font-display text-6xl sm:text-[7rem] md:text-[9rem] leading-[0.85] italic mb-8 md:mb-12">
                        光を、<br><span class="gold-gradient">纏う。</span>
                    </h1>
                    <div class="glass p-8 md:p-12 max-w-lg mt-10 md:mt-20">
                        <p class="text-[13px] md:text-sm leading-relaxed font-light opacity-60">
                            一点の妥協も許さない職人の手によって、光は形を得る。Luxe Aura は、あなたの中に眠る本質を輝かせるための、光り輝く相棒であることを約束します。
                        </p>
                        <button class="mt-8 md:mt-10 px-8 py-4 md:px-10 md:py-5 bg-white text-black text-[10px] font-bold uppercase tracking-[0.4em] hover:bg-amber-400 transition-colors">Private Viewing</button>
                    </div>
                </div>
                <!-- Overlapping Image Container -->
                <div class="absolute right-0 top-0 w-full lg:w-1/2 h-[80vh] lg:h-[120%] z-0 overflow-hidden opacity-30 lg:opacity-100">
                    <div class="float-image w-full h-full">
                        <img src="https://images.unsplash.com/photo-1515562141207-7a88bb7ce338?auto=format&fit=crop&q=80&w=1600" alt="Luxe Aura エターナルブルーム・ダイヤモンド" class="w-full h-full object-cover grayscale opacity-40 hover:grayscale-0 hover:opacity-80 transition-all duration-[3s]">
                    </div>
                </div>
            </div>
        </section>

        <!-- Product Magazine Layout -->
        <section class="mt-20 md:mt-40 max-w-7xl mx-auto px-6 md:px-10 pb-40 md:pb-60">
            <div class="flex flex-col md:flex-row justify-between items-baseline mb-20 md:mb-32 border-b border-white/10 pb-8 md:pb-12 gap-4">
                <h2 class="font-display text-4xl md:text-6xl italic">Selected Works.</h2>
                <span class="text-[9px] uppercase tracking-[1em] opacity-30">Archive 026 / Winter</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-20 md:gap-40">
                @foreach($products as $index => $product)
                <div class="group {{ $index % 2 != 0 ? 'lg:mt-32' : '' }}">
                    <div class="relative mb-8 md:mb-12 overflow-hidden glass">
                        <div class="aspect-[3/4] p-8 md:p-12">
                            <img src="https://images.unsplash.com/photo-1599643446519-71ad4c6f33d0?auto=format&fit=crop&q=80&w=800" alt="{{ $product->name }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-[2s]">
                        </div>
                        <div class="absolute top-8 left-8 text-[10px] font-bold text-amber-500 tracking-tighter">REF_{{ $product->sku }}</div>
                    </div>
                    <div class="space-y-4">
                        <h3 class="font-display text-3xl font-bold">{{ $product->name }}</h3>
                        <div class="flex justify-between items-center">
                            <div class="h-px w-20 bg-amber-500/30"></div>
                            <span class="text-lg font-light italic opacity-60">¥{{ number_format($product->price) }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
    </main>

    <footer class="py-20 border-t border-white/5 opacity-40 text-center">
        <div class="font-display text-xl gold-gradient mb-4">Luxe Aura Atelier.</div>
        <div class="text-[9px] uppercase tracking-[0.5em]">Craftmanship is the ultimate luxury // 2026</div>
    </footer>
</body>
</html>
