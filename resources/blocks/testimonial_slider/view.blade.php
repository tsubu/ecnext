<section class="py-20 bg-void rounded-[4rem] overflow-hidden relative" data-aos="fade-up">
    <!-- Starfield Mock -->
    <div class="absolute inset-0 opacity-20 pointer-events-none">
        @for($i=0; $i<20; $i++)
            <div class="absolute w-1 h-1 bg-white rounded-full" style="top: {{ rand(0,100) }}%; left: {{ rand(0,100) }}%"></div>
        @endfor
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-6 text-center space-y-12">
        <div class="inline-flex gap-1">
            @for($j=0; $j<5; $j++)
                <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
            @endfor
        </div>

        <blockquote class="text-2xl md:text-4xl font-black text-white leading-tight italic tracking-tighter">
            “{{ $settings['t1_quote'] ?? 'The predictive delivery system changed how we perceive commerce. It is like living in the future, today.' }}”
        </blockquote>

        <div class="flex items-center justify-center gap-4">
            <div class="w-12 h-12 rounded-full bg-indigo-500 flex items-center justify-center font-black text-white border-2 border-white/20">
                {{ substr($settings['t1_name'] ?? 'JL', 0, 1) }}
            </div>
            <div class="text-left">
                <p class="text-white text-sm font-black uppercase tracking-widest">{{ $settings['t1_name'] ?? 'Julian Laurent' }}</p>
                <p class="text-indigo-400 text-[10px] font-black uppercase tracking-widest">{{ block_trans('testimonial_slider', 'verified') }}</p>
            </div>
        </div>
    </div>
</section>
