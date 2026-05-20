<section class="py-12" data-aos="fade-up">
    @if(!empty($settings['title']))
        <h4 class="text-center text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-10">{{ $settings['title'] }}</h4>
    @endif
    <div class="flex flex-wrap justify-center items-center gap-10 md:gap-16 opacity-40 grayscale hover:opacity-100 hover:grayscale-0 transition-all duration-500">
        @for($i = 1; $i <= 6; $i++)
            @if(!empty($settings["logo{$i}"]))
                <img src="{{ $settings["logo{$i}"] }}" class="h-8 md:h-10 w-auto object-contain" alt="">
            @endif
        @endfor
    </div>
</section>
