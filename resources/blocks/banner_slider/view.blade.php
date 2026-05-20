@php
    $interval = ($settings['interval'] ?? 5) * 1000;
    $bsT = 'banner_slider';
@endphp

<div class="relative overflow-hidden rounded-3xl shadow-lg" data-aos="fade-up"
     x-data="{ current: 0, slides: [
        @for($i = 1; $i <= 3; $i++)
            @if(!empty($settings["slide{$i}_image"]))
                { img: '{{ $settings["slide{$i}_image"] }}', link: '{{ $settings["slide{$i}_link"] ?? '#' }}', alt: '{{ $settings["slide{$i}_alt"] ?? '' }}' },
            @endif
        @endfor
     ] }"
     x-init="setInterval(() => { current = (current + 1) % slides.length }, {{ $interval }})">

    <div class="relative aspect-[21/9] md:aspect-[3/1] bg-slate-100">
        <template x-for="(slide, i) in slides" :key="i">
            <a :href="slide.link" class="absolute inset-0 transition-opacity duration-700" :class="current === i ? 'opacity-100 z-10' : 'opacity-0 z-0'">
                <img :src="slide.img" :alt="slide.alt" class="w-full h-full object-cover">
            </a>
        </template>
    </div>

    {{-- ドットインジケーター --}}
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-20 flex gap-2">
        <template x-for="(slide, i) in slides" :key="'dot'+i">
            <button @click="current = i" class="w-2.5 h-2.5 rounded-full transition-all" :class="current === i ? 'bg-white w-6' : 'bg-white/40'"></button>
        </template>
    </div>

    {{-- 前へ/次へ --}}
    <button type="button" aria-label="{{ block_trans($bsT, 'slide_prev') }}" @click="current = (current - 1 + slides.length) % slides.length" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-black/20 backdrop-blur-sm text-white rounded-full flex items-center justify-center hover:bg-black/40 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <button type="button" aria-label="{{ block_trans($bsT, 'slide_next') }}" @click="current = (current + 1) % slides.length" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-black/20 backdrop-blur-sm text-white rounded-full flex items-center justify-center hover:bg-black/40 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
    </button>
</div>
