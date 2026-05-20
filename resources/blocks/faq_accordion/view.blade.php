<div class="max-w-4xl mx-auto px-8 py-20 bg-slate-50/50 rounded-[4rem] border border-slate-100" data-aos="fade-up">
    @if(!empty($settings['title']))
        <div class="text-center mb-16 space-y-3">
            <h2 class="text-3xl font-black text-slate-900 tracking-tighter">{{ $settings['title'] }}</h2>
            <div class="flex justify-center gap-1">
                <div class="w-8 h-1 bg-indigo-600 rounded-full"></div>
                <div class="w-2 h-1 bg-indigo-300 rounded-full"></div>
            </div>
        </div>
    @endif

    <div class="space-y-4" x-data="{ active: null }">
        @for($i = 1; $i <= 3; $i++)
            @if(!empty($settings["q{$i}"]))
                <div class="glass bg-white border border-slate-100 rounded-[2rem] overflow-hidden transition-all duration-300" :class="active === {{ $i }} ? 'shadow-2xl shadow-indigo-600/10 border-indigo-100' : 'hover:border-slate-200'">
                    <button 
                        @click="active = (active === {{ $i }} ? null : {{ $i }})"
                        class="w-full flex items-center justify-between p-8 text-left outline-none group"
                    >
                        <span class="text-sm font-black text-slate-800 transition-colors group-hover:text-indigo-600">{{ $settings["q{$i}"] }}</span>
                        <div class="w-8 h-8 rounded-xl bg-slate-50 flex items-center justify-center transition-all group-hover:bg-indigo-600 group-hover:text-white" :class="active === {{ $i }} ? 'rotate-180 bg-indigo-600 text-white' : ''">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>
                    
                    <div 
                        x-show="active === {{ $i }}" 
                        x-collapse 
                        x-cloak
                    >
                        <div class="px-8 pb-8 pt-0">
                            <div class="h-[1px] bg-slate-100 mb-6"></div>
                            <p class="text-sm text-slate-500 leading-relaxed font-medium">
                                {{ $settings["a{$i}"] ?? '' }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        @endfor
    </div>
</div>
