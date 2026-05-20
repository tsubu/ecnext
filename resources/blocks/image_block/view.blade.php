@php
    $width = $settings['width'] ?? '100%';
    $isFullWidth = $width === '100%';
@endphp

<figure class="py-8 {{ $isFullWidth ? '' : 'flex flex-col items-center' }}" data-aos="fade-up">
    @if(!empty($settings['image_url']))
        @if(!empty($settings['link_url']))
            <a href="{{ $settings['link_url'] }}" class="block">
        @endif

        <img
            src="{{ $settings['image_url'] }}"
            alt="{{ $settings['alt_text'] ?? '' }}"
            class="rounded-3xl shadow-sm border border-slate-100 transition-transform duration-500 hover:shadow-xl"
            style="width: {{ $width }}; max-width: 100%; height: auto;"
            loading="lazy"
        >

        @if(!empty($settings['link_url']))
            </a>
        @endif
    @else
        <div class="w-full aspect-[16/9] max-w-3xl mx-auto bg-slate-50 border-2 border-dashed border-slate-200 rounded-3xl flex items-center justify-center">
            <div class="text-center space-y-2 opacity-40">
                <svg class="w-12 h-12 text-slate-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">{{ block_trans('image_block', 'empty_hint') }}</p>
            </div>
        </div>
    @endif

    @if(!empty($settings['caption']))
        <figcaption class="mt-4 text-center text-sm text-slate-500">{{ $settings['caption'] }}</figcaption>
    @endif
</figure>
