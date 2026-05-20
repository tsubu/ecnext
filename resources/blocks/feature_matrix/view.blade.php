<div class="px-8 md:px-20 py-16 bg-white rounded-[4rem] border border-slate-100 shadow-sm" data-aos="fade-up">
    @if(!empty($settings['title']))
        <h2 class="text-2xl font-black text-slate-900 tracking-tight text-center mb-16 uppercase tracking-[0.2em]">{{ $settings['title'] }}</h2>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8">
        @for($i = 1; $i <= 4; $i++)
            @if(!empty($settings["f{$i}_title"]))
                <div class="space-y-6 group">
                    <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 transition-all group-hover:bg-indigo-600 group-hover:text-white group-hover:scale-110 shadow-sm">
                        @switch($i)
                            @case(1) <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> @break
                            @case(2) <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg> @break
                            @case(3) <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> @break
                            @case(4) <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg> @break
                        @endswitch
                    </div>
                    <div>
                        <h4 class="text-lg font-black text-slate-900 mb-2">{{ $settings["f{$i}_title"] }}</h4>
                        <p class="text-sm text-slate-500 leading-relaxed">{{ $settings["f{$i}_desc"] ?? '' }}</p>
                    </div>
                </div>
            @endif
        @endfor
    </div>
</div>
