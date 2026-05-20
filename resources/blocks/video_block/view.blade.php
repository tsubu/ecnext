{{-- 統合: YouTube動画 + アップロード動画 --}}
@php
    $hasYoutube = !empty($settings['youtube_id']);
    $hasVideo = !empty($settings['video_url']);
    $autoplay = ($settings['autoplay'] ?? 'no') === 'yes';
    $loop = ($settings['loop'] ?? 'no') === 'yes';
@endphp

<section class="py-12 space-y-6" data-aos="fade-up">
    @if(!empty($settings['title']))
        <h3 class="text-2xl font-black text-slate-900 tracking-tighter text-center">{{ $settings['title'] }}</h3>
    @endif

    @if($hasYoutube)
        {{-- YouTube埋め込み --}}
        <div class="relative w-full max-w-4xl mx-auto aspect-video rounded-3xl overflow-hidden shadow-2xl border border-slate-100">
            <iframe
                src="https://www.youtube.com/embed/{{ $settings['youtube_id'] }}?rel=0&modestbranding=1"
                class="absolute inset-0 w-full h-full"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen
            ></iframe>
        </div>
    @elseif($hasVideo)
        {{-- 直接動画ファイル --}}
        <div class="max-w-4xl mx-auto rounded-3xl overflow-hidden shadow-lg border border-slate-100 bg-black">
            <video
                class="w-full"
                {{ $autoplay ? 'autoplay muted' : '' }}
                {{ $loop ? 'loop' : '' }}
                controls playsinline preload="metadata"
                @if(!empty($settings['poster_image'])) poster="{{ $settings['poster_image'] }}" @endif
            >
                <source src="{{ $settings['video_url'] }}" type="video/mp4">
            </video>
        </div>
    @else
        <div class="w-full max-w-4xl mx-auto aspect-video rounded-3xl bg-slate-50 border-2 border-dashed border-slate-200 flex items-center justify-center">
            <div class="text-center space-y-2 opacity-40">
                <svg class="w-12 h-12 text-slate-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">{{ block_trans('video_block', 'empty_hint') }}</p>
            </div>
        </div>
    @endif

    @if(!empty($settings['caption']))
        <p class="text-sm text-slate-500 text-center max-w-2xl mx-auto leading-relaxed">{{ $settings['caption'] }}</p>
    @endif
</section>
