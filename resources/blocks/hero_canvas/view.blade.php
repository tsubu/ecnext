@php
    $overlayOpacity = $settings['overlay_opacity'] ?? 0.4;
    $bgImage = $settings['bg_image'] ?? 'https://images.unsplash.com/photo-1635776062127-d379bfcba9f8?auto=format&fit=crop&q=80&w=2000';
@endphp

<section class="relative min-h-[80vh] flex items-center justify-center overflow-hidden rounded-[4rem] group" data-aos="fade-up">
    <!-- Background Layer -->
    <div class="absolute inset-0 z-0 transition-transform duration-[10s] group-hover:scale-105">
        <img src="{{ $bgImage }}" alt="Hero Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-void" style="opacity: {{ $overlayOpacity }}"></div>
        <!-- Gradient Wash -->
        <div class="absolute inset-0 bg-gradient-to-t from-void via-transparent to-transparent opacity-80"></div>
    </div>

    <!-- Content Floating Layer -->
    <div class="relative z-10 w-full max-w-7xl px-8 md:px-20 py-20 text-center">
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/5 backdrop-blur-md border border-white/10 rounded-full text-[10px] font-black text-indigo-400 uppercase tracking-[0.3em] mb-8 animate-in slide-in-from-top duration-700">
            <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
            {{ block_trans('hero_canvas', 'badge_new') }}
        </div>

        <h1 class="text-5xl md:text-8xl font-black text-white tracking-tighter leading-[0.9] mb-8 drop-shadow-2xl">
            {!! nl2br(e($settings['headline'] ?? 'Elevate Your Reality')) !!}
        </h1>

        <p class="max-w-2xl mx-auto text-lg md:text-xl text-slate-300 font-medium leading-relaxed mb-12 opacity-80">
            {{ $settings['lead_text'] ?? '' }}
        </p>

        <!-- CTA Cluster -->
        <div class="flex flex-wrap items-center justify-center gap-6">
            @if(!empty($settings['cta_primary_label']))
                <a href="{{ $settings['cta_primary_link'] ?? '#' }}" class="group/btn relative px-10 py-5 bg-indigo-600 text-white text-sm font-black rounded-2xl hover:bg-indigo-500 transition-all shadow-2xl shadow-indigo-600/40 active:scale-95 flex items-center gap-3 overflow-hidden">
                    <span class="relative z-10">{{ $settings['cta_primary_label'] }}</span>
                    <svg class="relative z-10 w-4 h-4 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    <div class="absolute inset-x-0 bottom-0 h-0 group-hover/btn:h-full bg-indigo-400/20 transition-all duration-300"></div>
                </a>
            @endif

            @if(!empty($settings['cta_secondary_label']))
                <a href="{{ $settings['cta_secondary_link'] ?? '#' }}" class="px-10 py-5 bg-white/5 backdrop-blur-xl border border-white/10 text-white text-sm font-black rounded-2xl hover:bg-white/10 transition-all active:scale-95">
                    {{ $settings['cta_secondary_label'] }}
                </a>
            @endif
        </div>
    </div>

    <!-- Decorative Elements -->
    <div class="absolute bottom-12 left-1/2 -translate-x-1/2 flex flex-col items-center gap-4 opacity-30">
        <p class="text-[9px] font-black text-white uppercase tracking-[1em] ml-4">{{ block_trans('hero_canvas', 'scroll_hint') }}</p>
        <div class="w-[1px] h-12 bg-gradient-to-t from-white to-transparent"></div>
    </div>
</section>
