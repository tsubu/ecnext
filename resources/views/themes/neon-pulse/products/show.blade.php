<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $product->name }} // Neon Pulse</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;800&family=Space+Grotesk:wght@300;700&display=swap" rel="stylesheet">
    <style>
        :root { --neon-cyan: #00fbfb; --neon-magenta: #ff00ff; --void: #0a0a0a; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--void); color: #fff; overflow-x: hidden; }
        .font-tech { font-family: 'Space Grotesk', sans-serif; }
        .hud-header { position: fixed; top: 10px; left: 50%; transform: translateX(-50%); z-index: 1000; width: calc(100% - 20px); max-width: 1400px; background: rgba(10, 10, 10, 0.9); backdrop-filter: blur(20px); border: 2px solid var(--neon-cyan); padding: 10px 40px; display: flex; justify-content: space-between; align-items: center; }
        .brutal-border { border: 4px solid var(--neon-cyan); position: relative; }
        @keyframes glitch { 0% { text-shadow: 2px 0 var(--neon-cyan), -2px 0 var(--neon-magenta); } 50% { text-shadow: -2px 0 var(--neon-cyan), 2px 0 var(--neon-magenta); } 100% { text-shadow: 2px 0 var(--neon-cyan), -2px 0 var(--neon-magenta); } }
        .glitch-text { animation: glitch 1s infinite; }
    </style>
</head>
<body>
    <header class="hud-header">
        <div class="font-tech text-2xl font-bold tracking-tighter text-cyan-400">
            <a href="{{ route('home') }}">PULSE // OS</a>
        </div>
        <div class="flex items-center gap-8">
             <div class="w-10 h-10 bg-cyan-500/10 border border-cyan-500 p-2 text-[8px] font-bold text-cyan-400 flex items-center justify-center cursor-pointer uppercase">USER</div>
        </div>
    </header>

    <main class="mt-40 max-w-[1400px] mx-auto px-10 pb-40">
        <div class="grid grid-cols-12 gap-16">
            <!-- Product Image -->
            <div class="col-span-12 lg:col-span-6">
                <div class="brutal-border aspect-square bg-slate-900 overflow-hidden">
                    <img src="{{ $product->images->first()?->url ?? 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&q=80&w=1200' }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-span-12 lg:col-span-6 space-y-10">
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <span class="text-[10px] bg-cyan-500 text-black px-3 py-1 font-black uppercase tracking-widest">PRODUCT_ID: {{ $product->sku }}</span>
                        <div class="h-px flex-grow bg-white/10"></div>
                    </div>
                    <h1 class="font-tech text-6xl font-bold tracking-tighter uppercase glitch-text">{{ $product->name }}</h1>
                    
                    <div class="flex items-center gap-2 mt-2">
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $averageRating ? 'text-cyan-400 fill-cyan-400' : 'text-slate-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            @endfor
                        </div>
                        <span class="text-[10px] font-bold text-slate-500 tracking-widest uppercase">({{ count($reviews) }} REVIEWS)</span>
                    </div>
                </div>

                <div class="p-8 bg-white/5 border-l-4 border-cyan-500 space-y-4">
                    <div class="flex items-baseline gap-4">
                        @if($product->defaultVariant && $product->defaultVariant->is_on_sale)
                            <span class="text-4xl font-tech font-bold text-rose-500">¥{{ number_format($product->defaultVariant->active_price) }}</span>
                            <span class="text-lg text-slate-600 line-through">¥{{ number_format($product->defaultVariant->price) }}</span>
                            <span class="text-[10px] bg-rose-600 text-white px-2 py-0.5 font-bold uppercase tracking-widest animate-pulse">SALE</span>
                        @else
                            <span class="text-4xl font-tech font-bold text-cyan-400">¥{{ number_format($product->price) }}</span>
                        @endif
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed">{{ $product->description }}</p>
                </div>

                <button class="w-full py-6 bg-cyan-500 hover:bg-white text-black font-black text-xl uppercase tracking-tighter transition-all flex items-center justify-center gap-4 group">
                    <span>SYNC_TO_CART</span>
                    <svg class="w-6 h-6 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </button>
            </div>
        </div>

        <!-- Dynamic Blocks Section -->
        @if($product->layouts->count() > 0)
        <div class="mt-20 space-y-8">
            @foreach($product->layouts as $layout)
                @if($layout->blockInstance && $layout->blockInstance->is_active)
                    <x-shop.renderer
                        :instance="$layout->blockInstance"
                        :settings="$layout->settings_override ?? []"
                        :contextProduct="$product"
                    />
                @endif
            @endforeach
        </div>
        @endif

        <!-- Reviews Section -->
        <div class="mt-40 space-y-12">
            <div class="flex items-center gap-6">
                <h2 class="font-tech text-4xl font-bold tracking-tighter uppercase whitespace-nowrap">Neural_Feedback</h2>
                <div class="h-px flex-grow bg-white/10"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @forelse($reviews as $review)
                <div class="p-8 bg-white/5 border border-white/10 rounded-3xl space-y-4">
                    <div class="flex justify-between items-start">
                        <div class="space-y-1">
                            <p class="text-xs font-bold text-cyan-400 uppercase tracking-widest">{{ $review->user->name }}</p>
                            <p class="text-[9px] text-slate-600 font-mono">{{ $review->created_at->format('Y.m.d H:i') }}</p>
                        </div>
                        <div class="flex gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-3 h-3 {{ $i <= $review->rating ? 'text-amber-400 fill-amber-400' : 'text-slate-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            @endfor
                        </div>
                    </div>
                    <p class="text-slate-300 text-sm leading-relaxed border-t border-white/5 pt-4 italic">"{{ $review->comment }}"</p>
                </div>
                @empty
                <div class="col-span-2 p-20 border-2 border-dashed border-white/5 text-center items-center justify-center opacity-30">
                    <p class="text-xs font-bold uppercase tracking-[0.5em]">NO_FEEDBACK_DATA_AVAILABLE</p>
                </div>
                @endforelse
            </div>
            
            <!-- Link to review submission will be here -->
            <div class="pt-10 flex justify-center">
                 <a href="{{ route('products.reviews.create', $product->slug) }}" class="px-8 py-4 bg-white/5 hover:bg-cyan-500/20 text-cyan-400 text-xs font-bold uppercase tracking-widest border border-cyan-500/50 transition-all">POST_NEURAL_LOG</a>
            </div>
        </div>
    </main>
</body>
</html>
