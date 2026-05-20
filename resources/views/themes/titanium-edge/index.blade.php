<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Titanium Edge // 高精度・産業用プロダクト・ソリューション</title>
    <meta name="description" content="公差 0.001mm への挑戦。Titanium Edge は、過酷な環境下でのパフォーマンスを保証するために設計された、高精度な産業用電子部品・ハードウェアを提供します。">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono&family=Inter:wght@300;700&display=swap" rel="stylesheet">
    <style>
        :root { --steel: #1a1c1e; --caution: #ff6b00; --blueprint: rgba(255, 255, 255, 0.05); }
        body { font-family: 'Inter', sans-serif; background-color: var(--steel); color: #e1e1e1; background-image: 
            linear-gradient(var(--blueprint) 1px, transparent 1px),
            linear-gradient(90deg, var(--blueprint) 1px, transparent 1px);
            background-size: 40px 40px; }
        .font-mono { font-family: 'Space Mono', monospace; }
        
        .spec-grid { border: 1px solid rgba(255, 255, 255, 0.1); }
        .caution-tag { background: var(--caution); color: #000; font-weight: 800; padding: 2px 8px; font-size: 10px; }
        .data-point { background: rgba(255, 255, 255, 0.03); border-left: 2px solid var(--caution); }
        
        .industrial-btn { border: 1px solid var(--caution); color: var(--caution); transition: all 0.3s ease; }
        .industrial-btn:hover { background: var(--caution); color: #000; }
        
        .measure-line { position: relative; }
        .measure-line::before { content: ''; position: absolute; left: 0; top: 50%; width: 100%; height: 1px; background: rgba(255,255,255,0.1); }
    </style>
</head>
<body class="selection:bg-orange-500/30">
    <header class="border-b border-white/10 bg-black/80 backdrop-blur sticky top-0 z-50">
        <div class="max-w-[1800px] mx-auto px-6 md:px-10 h-20 flex justify-between items-center">
            <div class="flex items-center gap-4 md:gap-6">
                <div class="w-8 h-8 md:w-10 md:h-10 border-2 border-orange-500 flex items-center justify-center font-mono text-orange-500 font-bold text-sm">TE</div>
                <div class="font-mono text-sm md:text-xl font-bold tracking-tighter uppercase truncate max-w-[150px] md:max-w-none">Titanium Edge</div>
            </div>
            <nav class="flex gap-4 md:gap-10 font-mono text-[10px] items-center">
                <a href="#" class="opacity-50 hover:opacity-100 hidden sm:block">[
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                ]</a>
                <a href="#" class="opacity-50 hover:opacity-100">[ LOGIN ]</a>
                <div class="h-6 w-px bg-white/20 hidden md:block"></div>
                <div class="text-orange-500 animate-pulse hidden md:block">SYS_ONLINE</div>
            </nav>
        </div>
    </header>

    <main class="max-w-[1800px] mx-auto px-6 md:px-10 pb-40">
        <!-- Technical Hero -->
        <section class="py-12 md:py-32 grid grid-cols-12 gap-10">
            <div class="col-span-12 lg:col-span-7 flex flex-col justify-center space-y-8 md:space-y-12 order-2 lg:order-1">
                <div class="inline-flex items-center gap-4">
                    <span class="caution-tag">INDUSTRIAL_GRADE</span>
                    <span class="font-mono text-[10px] opacity-40">UTC_LOG: {{ date('H:i:s') }}</span>
                </div>
                <h1 class="text-5xl md:text-8xl font-black uppercase leading-[0.9] tracking-tighter">
                    精度を、<br><span class="text-orange-500">極限まで。</span>
                </h1>
                <p class="max-w-xl text-base md:text-lg opacity-60 leading-relaxed">
                    公差 0.001mm への挑戦。Titanium Edge は、過酷な環境下でのパフォーマンスを保証するために設計された、高精度プロダクト・スイートです。
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <button class="industrial-btn px-10 py-5 font-mono text-xs uppercase tracking-widest">Execute Inquiry</button>
                    <button class="bg-white/5 border border-white/10 px-10 py-5 font-mono text-xs uppercase tracking-widest opacity-60 hover:opacity-100 transition-opacity">Technical Spec</button>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-5 relative order-1 lg:order-2">
                <div class="aspect-square border border-white/10 p-4 bg-black/20">
                    <div class="w-full h-full border border-white/5 relative overflow-hidden flex items-center justify-center">
                        <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&q=80&w=1200" alt="Titanium Edge 高精度産業用コンポーネント" class="w-full h-full object-cover opacity-60 grayscale hover:grayscale-0 transition-all duration-700">
                        <!-- Blueprint Overlays -->
                        <div class="absolute inset-x-0 top-1/2 h-px bg-orange-500/30"></div>
                        <div class="absolute inset-y-0 left-1/2 w-px bg-orange-500/30"></div>
                    </div>
                </div>
                <div class="absolute -bottom-10 -right-4 font-mono text-[8px] flex flex-col items-end opacity-20">
                    <div>SCAN_RADIUS: 450.00</div>
                    <div>TOLERANCE: ±0.005</div>
                    <div>MATERIAL: Ti-6Al-4V</div>
                </div>
            </div>
        </section>

        <!-- Parametric Product Grid -->
        <div class="mt-40 measure-line mb-32">
            <div class="bg-steel px-6 inline-block font-mono text-xs tracking-[0.3em] font-bold">COMPONENT_INVENTORY_V1</div>
        </div>

        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-px bg-white/10 border border-white/10">
            @foreach($products as $product)
            <div class="bg-steel p-6 md:p-8 space-y-6 hover:bg-white/[0.02] transition-colors group cursor-pointer">
                <div class="aspect-square bg-black border border-white/5 relative overflow-hidden flex items-center justify-center p-8">
                    <img src="https://images.unsplash.com/photo-1537462715879-360eeb61a0ad?auto=format&fit=crop&q=80&w=600" alt="{{ $product->name }}" class="w-full h-full object-contain opacity-40 group-hover:opacity-100 transition-all duration-[2s]">
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between items-start">
                        <h3 class="font-mono font-bold text-sm tracking-tighter uppercase leading-tight">{{ $product->name }}</h3>
                        <span class="text-orange-500 font-mono text-xs">¥{{ number_format($product->price) }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="data-point p-2">
                            <div class="text-[8px] opacity-40">SKU_REF</div>
                            <div class="text-[10px] font-mono">{{ $product->sku }}</div>
                        </div>
                        <div class="data-point p-2">
                            <div class="text-[8px] opacity-40">STATUS</div>
                            <div class="text-[10px] font-mono text-green-500">IN_STOCK</div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </section>
    </main>

    <footer class="mt-60 border-t border-white/10 py-20 bg-black/40">
        <div class="max-w-[1800px] mx-auto px-10 flex justify-between items-center font-mono text-[8px] opacity-20 uppercase tracking-[0.5em]">
            <div>Core Protocol // 2026 Titanium Edge</div>
            <div>All Specifications Subject to Engineering Change</div>
        </div>
    </footer>
</body>
</html>
