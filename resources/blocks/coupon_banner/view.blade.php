<div class="py-8" data-aos="fade-up" x-data="{ copied: false }">
    <div class="max-w-2xl mx-auto bg-gradient-to-r from-indigo-600 to-violet-600 rounded-3xl p-8 md:p-12 text-center text-white relative overflow-hidden shadow-xl shadow-indigo-600/20">
        {{-- 装飾パターン --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-4 left-4 w-32 h-32 border border-white rounded-full"></div>
            <div class="absolute bottom-4 right-4 w-48 h-48 border border-white rounded-full"></div>
        </div>

        <div class="relative z-10 space-y-4">
            <h4 class="text-2xl font-black tracking-tight">{{ $settings['title'] ?? block_trans('coupon_banner', 'default_title') }}</h4>
            <p class="text-indigo-100 text-sm">{{ $settings['description'] ?? block_trans('coupon_banner', 'default_description') }}</p>

            {{-- クーポンコード --}}
            <div class="inline-flex items-center gap-3 bg-white/10 backdrop-blur-sm border-2 border-dashed border-white/40 rounded-2xl px-8 py-4 mt-4">
                <span class="text-2xl font-black tracking-[0.15em] font-mono">{{ $settings['code'] ?? 'COUPON' }}</span>
                <button type="button"
                    @click="navigator.clipboard.writeText('{{ $settings['code'] ?? 'COUPON' }}'); copied = true; setTimeout(() => copied = false, 2000)"
                    class="ml-2 p-2 bg-white/20 rounded-lg hover:bg-white/30 transition-colors">
                    <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    <svg x-show="copied" x-cloak class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </button>
            </div>
            <p x-show="copied" x-cloak class="text-emerald-300 text-xs font-bold animate-bounce">{{ block_trans('coupon_banner', 'copied') }}</p>

            @if(!empty($settings['expires']))
                <p class="text-indigo-200 text-xs mt-4">{{ block_trans('coupon_banner', 'expires_prefix') }} {{ $settings['expires'] }}</p>
            @endif
        </div>
    </div>
</div>
