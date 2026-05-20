<div class="relative overflow-hidden rounded-[3rem] bg-indigo-900 text-white p-12 md:p-24 shadow-2xl">
    <div class="relative z-10 max-w-2xl">
        <span class="inline-block px-4 py-1.5 bg-indigo-500/30 backdrop-blur-md rounded-full text-[10px] font-black uppercase tracking-[0.3em] mb-6">
            {{ $settings['badge'] ?? __('Special Feature') }}
        </span>
        <h2 class="text-4xl md:text-7xl font-black tracking-tighter leading-none mb-8">
            {{ $settings['title'] ?? $instance->name }}
        </h2>
        <p class="text-lg md:text-xl text-indigo-100 opacity-80 leading-relaxed max-w-lg mb-12 italic">
            {{ $settings['description'] ?? '' }}
        </p>
        
        @if(!empty($settings['button_url']))
            <a href="{{ $settings['button_url'] }}" class="inline-flex items-center gap-4 px-10 py-5 bg-white text-indigo-900 text-sm font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-indigo-50 transition-all shadow-xl shadow-white/10 active:scale-95">
                {{ $settings['button_text'] ?? __('Explore Now') }}
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        @endif
    </div>

    {{-- Decor --}}
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-indigo-400 rounded-full blur-[120px] opacity-20"></div>
    <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-blue-400 rounded-full blur-[100px] opacity-20"></div>
</div>
