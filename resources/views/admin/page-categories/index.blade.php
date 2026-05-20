@extends('admin.layouts.base')

@section('title', __('Page Categories'))
@section('page_title', __('Classification Taxonomy'))

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h3 class="text-xl font-bold tracking-tight text-white">{{ __('CMS Taxonomy') }}</h3>
        <p class="text-sm text-slate-400 mt-1">{{ __('Organize your static pages into logical groups like Legal or Features.') }}</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.pages.index') }}" class="px-6 py-3 bg-white/5 hover:bg-white/10 text-white text-sm font-bold rounded-2xl transition-all border border-white/5 active:scale-95">
            {{ __('Back to Pages') }}
        </a>
        <a href="{{ route('admin.page-categories.create') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-2xl transition-all shadow-lg shadow-indigo-600/20 flex items-center gap-2 active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M12 4v16m8-8H4"/></svg>
            {{ __('New Category') }}
        </a>
    </div>
</div>

@if(session('success'))
    <div class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400 text-sm font-bold flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-8 p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl text-rose-400 text-sm font-bold flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('error') }}
    </div>
@endif

<div class="glass rounded-[2rem] overflow-hidden border border-white/5 shadow-2xl">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-white/5 bg-white/5">
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Category Identity') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Description') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-center">{{ __('Sort Order') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($categories as $category)
                    <tr class="hover:bg-white/[0.02] transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-white group-hover:text-indigo-400 transition-colors">{{ $category->name }}</span>
                                <span class="text-[10px] text-slate-500 mt-0.5 font-mono uppercase tracking-widest">{{ $category->slug }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-xs text-slate-400 font-medium">{{ $category->description ?? '—' }}</span>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <span class="px-2 py-1 bg-white/5 border border-white/10 rounded-md text-[10px] font-black text-slate-500">
                                {{ $category->sort_order }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2 lg:opacity-0 lg:group-hover:opacity-100 transition-all scale-95 group-hover:scale-100">
                                <a href="{{ route('admin.page-categories.edit', $category) }}" class="p-2 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white rounded-xl transition-all border border-white/5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.page-categories.destroy', $category) }}" method="POST" onsubmit="return confirm('{{ __('Delete this category?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-white/5 hover:bg-rose-500/10 text-slate-400 hover:text-rose-500 rounded-xl transition-all border border-white/5">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center text-slate-500 font-medium">
                            <p>{{ __('No page categories defined.') }}</p>
                            <a href="{{ route('admin.page-categories.create') }}" class="text-indigo-400 text-xs font-black uppercase tracking-widest hover:text-indigo-300 transition-colors">{{ __('Register First Category') }}</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
