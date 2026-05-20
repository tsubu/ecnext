@php $bentoT = 'bento_grid'; @endphp
<div class="space-y-12" data-aos="fade-up">
    @if(!empty($settings['title']) || !empty($settings['subtitle']))
        <div class="text-center space-y-3">
            <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter">{{ $settings['title'] ?? '' }}</h2>
            <p class="text-sm font-black text-indigo-600 uppercase tracking-widest">{{ $settings['subtitle'] ?? '' }}</p>
        </div>
    @endif

    <div class="grid grid-cols-12 gap-6 md:h-[600px]">
        <!-- Main Big Feature -->
        <div class="col-span-12 md:col-span-7 relative group overflow-hidden rounded-[3rem] bg-indigo-900 shadow-2xl transition-all duration-700 hover:shadow-indigo-600/20">
            <img src="{{ $settings['item_1_image'] ?? 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&q=80&w=800' }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-[6s] group-hover:scale-110 opacity-60">
            <div class="absolute inset-x-0 bottom-0 p-10 bg-gradient-to-t from-indigo-950 via-indigo-950/80 to-transparent">
                <span class="inline-block px-3 py-1 bg-indigo-500/20 border border-indigo-400/30 text-indigo-400 text-[9px] font-black uppercase tracking-widest rounded-full mb-4">{{ block_trans($bentoT, 'badge_core') }}</span>
                <h3 class="text-3xl font-black text-white mb-3">{{ $settings['item_1_title'] ?? block_trans($bentoT, 'default_item_1_title') }}</h3>
                <p class="text-slate-300 text-sm leading-relaxed max-w-md">{{ $settings['item_1_text'] ?? '' }}</p>
            </div>
        </div>

        <!-- Right Stack -->
        <div class="col-span-12 md:col-span-5 grid grid-rows-2 gap-6">
            <!-- Top Medium Card -->
            <div class="relative group overflow-hidden rounded-[3rem] bg-slate-100 border border-slate-200 p-10 flex flex-col justify-end transition-all duration-500 hover:border-indigo-200">
                <div class="absolute top-10 left-10 w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center transform group-hover:rotate-12 transition-transform shadow-lg shadow-indigo-600/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mb-2">{{ $settings['item_2_title'] ?? block_trans($bentoT, 'default_item_2_title') }}</h3>
                <p class="text-slate-500 text-xs leading-relaxed">{{ $settings['item_2_text'] ?? '' }}</p>
            </div>

            <!-- Bottom Small Grid -->
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-indigo-600 rounded-[2.5rem] p-8 flex flex-col justify-center text-center shadow-xl shadow-indigo-600/20 hover:scale-[1.02] transition-transform">
                    <h4 class="text-white font-black text-sm uppercase tracking-widest mb-2">{{ $settings['item_3_title'] ?? '99.9%' }}</h4>
                    <p class="text-indigo-200 text-[10px] font-bold uppercase tracking-widest">{{ block_trans($bentoT, 'label_efficiency') }}</p>
                </div>
                <div class="bg-white border border-slate-200 rounded-[2.5rem] p-8 flex flex-col justify-center text-center hover:border-indigo-400 transition-colors">
                    <h4 class="text-slate-900 font-black text-sm uppercase tracking-widest mb-2">{{ $settings['item_4_title'] ?? block_trans($bentoT, 'default_item_4_title') }}</h4>
                    <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest">{{ block_trans($bentoT, 'label_expansion') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
