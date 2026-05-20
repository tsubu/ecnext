@php
    $style = $settings['style'] ?? 'card';
    $categories = \App\Models\Category::whereNull('parent_id')->limit(8)->get();
@endphp

<section class="py-12" data-aos="fade-up">
    @if(!empty($settings['title']))
        <h3 class="text-xl font-black text-slate-900 tracking-tighter mb-8 {{ $style === 'circle' ? 'text-center' : '' }}">{{ $settings['title'] }}</h3>
    @endif

    @if($style === 'circle')
        <div class="flex flex-wrap justify-center gap-8">
            @foreach($categories as $cat)
                <a href="#" class="group flex flex-col items-center gap-3 transition-transform hover:-translate-y-1">
                    <div class="w-20 h-20 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden group-hover:border-indigo-400 group-hover:shadow-lg transition-all">
                        @if($cat->image_url)
                            <img src="{{ $cat->image_url }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-lg font-black text-slate-300">{{ mb_substr($cat->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <span class="text-xs font-bold text-slate-600 group-hover:text-indigo-600 transition-colors">{{ $cat->name }}</span>
                </a>
            @endforeach
        </div>
    @elseif($style === 'list')
        <div class="divide-y divide-slate-100 border border-slate-100 rounded-2xl overflow-hidden bg-white">
            @foreach($categories as $cat)
                <a href="#" class="flex items-center justify-between px-6 py-4 hover:bg-slate-50 transition-colors group">
                    <span class="text-sm font-medium text-slate-700 group-hover:text-indigo-600">{{ $cat->name }}</span>
                    <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @endforeach
        </div>
    @else
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($categories as $cat)
                <a href="#" class="group relative aspect-[4/3] rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 hover:border-indigo-400 transition-all hover:shadow-lg">
                    @if($cat->image_url)
                        <img src="{{ $cat->image_url }}" class="w-full h-full object-cover transition-transform group-hover:scale-105">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent flex items-end p-4">
                        <span class="text-white font-bold text-sm">{{ $cat->name }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</section>
