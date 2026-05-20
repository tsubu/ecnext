@php
    $limit = $settings['limit'] ?? 5;
    $pages = \App\Models\Page::where('is_published', true)
        ->orderByDesc('updated_at')
        ->limit($limit)
        ->get();
@endphp

<section class="py-12" data-aos="fade-up">
    @if(!empty($settings['title']))
        <h3 class="text-xl font-black text-slate-900 tracking-tighter mb-8">{{ $settings['title'] }}</h3>
    @endif

    <div class="divide-y divide-slate-100 border border-slate-100 rounded-2xl overflow-hidden bg-white">
        @forelse($pages as $page)
            <a href="/pages/{{ $page->slug }}" class="flex items-center justify-between px-6 py-4 hover:bg-slate-50 transition-colors group">
                <div class="flex items-center gap-4">
                    <span class="text-[10px] font-bold text-slate-400 whitespace-nowrap">{{ $page->updated_at->format('Y.m.d') }}</span>
                    <span class="text-sm text-slate-700 font-medium group-hover:text-indigo-600 transition-colors">{{ $page->title }}</span>
                </div>
                <svg class="w-4 h-4 text-slate-300 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        @empty
            <div class="px-6 py-8 text-center text-sm text-slate-400">{{ block_trans('news_list', 'empty') }}</div>
        @endforelse
    </div>
</section>
