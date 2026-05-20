@extends('admin.layouts.base')

@section('title', __('cms_blocks'))
@section('page_title', __('CMS Blocks'))

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h3 class="text-xl font-bold tracking-tight text-white">{{ __('Manage Content Blocks') }}</h3>
        <p class="text-sm text-slate-400 mt-1">{{ __('Create and maintain reusable components for your storefront.') }}</p>
    </div>
    <a href="{{ route('admin.cms-blocks.create') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-2xl transition-all shadow-lg shadow-indigo-600/20 flex items-center gap-2 active:scale-95">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M12 4v16m8-8H4"/></svg>
        {{ __('Add New Block') }}
    </a>
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
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Block Details') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Slug') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Status') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Last Updated') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($blocks as $block)
                    <tr class="hover:bg-white/[0.02] transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-indigo-500/10 rounded-xl flex items-center justify-center text-indigo-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-white group-hover:text-indigo-400 transition-colors">{{ $block->title }}</p>
                                    <p class="text-[10px] text-slate-500 mt-0.5 font-medium uppercase tracking-wider">{{ __('Reusable Content') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <code class="px-2 py-1 bg-white/5 border border-white/10 rounded text-[10px] font-mono text-indigo-300">
                                {{ $block->slug }}
                            </code>
                        </td>
                        <td class="px-8 py-6">
                            @if($block->is_active)
                                <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 text-[10px] font-black rounded-full uppercase tracking-widest border border-emerald-500/20">
                                    {{ __('Active') }}
                                </span>
                            @else
                                <span class="px-3 py-1 bg-slate-500/10 text-slate-400 text-[10px] font-black rounded-full uppercase tracking-widest border border-white/5">
                                    {{ __('Disabled') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-xs font-bold text-slate-400 tracking-tight">
                            {{ $block->updated_at->diffForHumans() }}
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.cms-blocks.edit', $block) }}" class="p-2 hover:bg-white/10 text-slate-400 hover:text-white rounded-lg transition-all" title="{{ __('edit') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.cms-blocks.destroy', $block) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this block?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 hover:bg-rose-500/10 text-slate-400 hover:text-rose-500 rounded-lg transition-all" title="{{ __('delete') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="p-4 bg-white/5 rounded-full text-slate-500">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                </div>
                                <p class="text-slate-400 font-bold tracking-tight">{{ __('No blocks found.') }}</p>
                                <a href="{{ route('admin.cms-blocks.create') }}" class="text-indigo-400 font-black text-xs uppercase tracking-widest hover:text-indigo-300 transition-colors">{{ __('Create your first block') }}</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($blocks->hasPages())
        <div class="px-8 py-6 bg-white/5 border-t border-white/5">
            {{ $blocks->links() }}
        </div>
    @endif
</div>
@endsection
