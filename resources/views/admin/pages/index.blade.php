@extends('admin.layouts.base')

@section('title', __('CMS Pages'))
@section('page_title', __('Static Content Management'))

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h3 class="text-xl font-bold tracking-tight text-white">{{ __('Storefront Pages') }}</h3>
        <p class="text-sm text-slate-400 mt-1">{{ __('Manage legal documents, sales features, and custom content.') }}</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.page-categories.index') }}" class="px-6 py-3 bg-white/5 hover:bg-white/10 text-white text-sm font-bold rounded-2xl transition-all border border-white/5 active:scale-95">
            {{ __('Manage Categories') }}
        </a>
        <a href="{{ route('admin.pages.create') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-2xl transition-all shadow-lg shadow-indigo-600/20 flex items-center gap-2 active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M12 4v16m8-8H4"/></svg>
            {{ __('Add New Page') }}
        </a>
    </div>
</div>

@if(session('success'))
    <div class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400 text-sm font-bold flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
@endif

<div class="glass rounded-[2rem] overflow-hidden border border-white/5 shadow-2xl">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-white/5 bg-white/5">
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Page Identity') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Category') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Type') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-center">{{ __('Status') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($pages as $page)
                    <tr class="hover:bg-white/[0.02] transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-white group-hover:text-indigo-400 transition-colors">{{ $page->title }}</span>
                                <span class="text-[10px] text-slate-500 mt-0.5 font-mono uppercase tracking-widest">/pages/{{ $page->slug }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            @if($page->category)
                                <span class="px-3 py-1 bg-white/5 border border-white/10 rounded-full text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    {{ $page->category->name }}
                                </span>
                            @else
                                <span class="text-[10px] text-slate-600 font-bold uppercase tracking-widest">—</span>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            @if($page->type === 'legal')
                                <span class="text-[10px] font-black uppercase tracking-widest text-amber-500 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    {{ __('Legal Form') }}
                                </span>
                            @else
                                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ __('Standard') }}</span>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col items-center gap-1">
                                @if($page->is_published)
                                    @if($page->expired_at && $page->expired_at->isPast())
                                        <span class="px-3 py-1 bg-rose-500/10 text-rose-500 text-[9px] font-black rounded-full border border-rose-500/20 uppercase tracking-widest">
                                            {{ __('Expired') }}
                                        </span>
                                    @elseif($page->published_at && $page->published_at->isFuture())
                                        <span class="px-3 py-1 bg-amber-500/10 text-amber-500 text-[9px] font-black rounded-full border border-amber-500/20 uppercase tracking-widest">
                                            {{ __('Scheduled') }}
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 text-[9px] font-black rounded-full border border-emerald-500/20 uppercase tracking-widest">
                                            {{ __('Active') }}
                                        </span>
                                    @endif
                                @else
                                    <span class="px-3 py-1 bg-slate-500/10 text-slate-500 text-[9px] font-black rounded-full border border-white/5 uppercase tracking-widest">
                                        {{ __('Draft') }}
                                    </span>
                                @endif
                                
                                @if($page->expired_at || $page->published_at)
                                    <span class="text-[8px] text-slate-600 font-mono uppercase tracking-tighter">
                                        {{ $page->published_at?->format('m/d') ?? '∞' }} - {{ $page->expired_at?->format('m/d') ?? '∞' }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2 lg:opacity-0 lg:group-hover:opacity-100 transition-all lg:scale-95 lg:group-hover:scale-100">
                                <a href="{{ route('admin.pages.edit', $page) }}" class="p-2 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white rounded-xl transition-all border border-white/5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                @if(!$page->is_system)
                                    <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this page?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-white/5 hover:bg-rose-500/10 text-slate-400 hover:text-rose-500 rounded-xl transition-all border border-white/5">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center text-slate-500 font-medium">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-white/5 rounded-3xl flex items-center justify-center border border-white/5">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <p>{{ __('No static pages created yet.') }}</p>
                                <a href="{{ route('admin.pages.create') }}" class="text-indigo-400 text-xs font-black uppercase tracking-widest hover:text-indigo-300 transition-colors">{{ __('Create First Page') }}</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
