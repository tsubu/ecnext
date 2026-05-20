@extends('admin.layouts.base')

@section('title', __('Block Library'))
@section('page_title', __('Block Library'))

@section('content')
<div x-data="{
    activeTab: {{ json_encode(request('tab') === 'advanced' ? 'advanced' : 'library') }},
    showCreateMenu: false,
    setTab(tab) {
        this.activeTab = tab;
        const u = new URL(window.location.href);
        if (tab === 'advanced') {
            u.searchParams.set('tab', 'advanced');
        } else {
            u.searchParams.delete('tab');
        }
        window.history.replaceState({}, '', u.pathname + u.search + u.hash);
    }
}">
    <!-- Header Area -->
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-6 text-white">
        <div>
            <h3 class="text-2xl font-black tracking-tight">{{ __('Block Library') }}</h3>
            <p class="text-sm text-slate-400 mt-1" x-show="activeTab === 'library'" x-cloak>{{ __('Manage all your visual components and content fragments in one place.') }}</p>
            <p class="text-sm text-slate-400 mt-1" x-show="activeTab === 'advanced'" x-cloak>{{ __('block_management_advanced_lead') }}</p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <div class="relative">
                <button @click="showCreateMenu = !showCreateMenu" @click.away="showCreateMenu = false"
                        class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-black rounded-2xl transition-all shadow-lg shadow-indigo-600/20 flex items-center gap-2 active:scale-95 uppercase tracking-widest">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    {{ __('Create Block') }}
                </button>
                <div x-show="showCreateMenu"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute right-0 mt-3 w-64 bg-slate-900 border border-white/10 rounded-[2rem] shadow-2xl z-50 overflow-hidden py-2 backdrop-blur-xl">
                    <div class="px-5 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-white/5 mb-2">{{ __('Select Template') }}</div>
                    @foreach($types as $type)
                        <a href="{{ route('admin.block-instances.create', ['type_id' => $type->id]) }}" class="flex items-center gap-3 px-5 py-3 hover:bg-white/5 transition-colors group">
                            <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-indigo-400 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                            <span class="text-sm font-bold text-slate-300 group-hover:text-white">{{ $type->resolvedName() }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs: separate page feel -->
    <nav class="mb-8 border-b border-white/10" role="tablist" aria-label="{{ __('Block management sections') }}">
        <div class="flex gap-0 sm:gap-2 -mb-px overflow-x-auto pb-px">
            <button type="button"
                    role="tab"
                    id="tab-library"
                    :aria-selected="activeTab === 'library'"
                    @click="setTab('library')"
                    :class="activeTab === 'library'
                        ? 'border-indigo-500 text-white bg-indigo-600/15'
                        : 'border-transparent text-slate-500 hover:text-slate-300 hover:bg-white/5'"
                    class="shrink-0 px-5 sm:px-8 py-3.5 text-xs font-black uppercase tracking-widest rounded-t-2xl border-b-2 border-t border-x border-white/5 transition-all">
                {{ __('block_management_tab_library') }}
            </button>
            <button type="button"
                    role="tab"
                    id="tab-advanced"
                    :aria-selected="activeTab === 'advanced'"
                    @click="setTab('advanced')"
                    :class="activeTab === 'advanced'
                        ? 'border-indigo-500 text-white bg-indigo-600/15'
                        : 'border-transparent text-slate-500 hover:text-slate-300 hover:bg-white/5'"
                    class="shrink-0 px-5 sm:px-8 py-3.5 text-xs font-black uppercase tracking-widest rounded-t-2xl border-b-2 border-t border-x border-white/5 transition-all">
                {{ __('Advanced') }}
            </button>
        </div>
    </nav>

    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400 text-sm font-bold flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Tab panel: Library -->
    <div x-show="activeTab === 'library'"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         role="tabpanel"
         aria-labelledby="tab-library"
         class="min-h-[50vh]"
         x-cloak>
        <div class="mb-8 flex flex-wrap gap-4">
            <form action="{{ route('admin.block-center.index') }}" method="GET" class="flex-1 min-w-[300px] flex gap-2">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search blocks by name or slug...') }}"
                           class="w-full bg-white/5 border border-white/10 rounded-2xl px-12 py-3 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <select name="type" class="bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-sm text-white focus:border-indigo-500 transition-all cursor-pointer">
                    <option value="">{{ __('All Types') }}</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>{{ $type->resolvedName() }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-6 py-3 bg-white/5 hover:bg-white/10 text-white text-sm font-black rounded-2xl transition-all border border-white/10 uppercase tracking-widest">
                    {{ __('Filter') }}
                </button>
            </form>
        </div>

        <div class="glass rounded-[3rem] overflow-hidden border border-white/5 shadow-2xl">
            <div class="p-8 border-b border-white/5 bg-white/[0.02] flex justify-between items-center">
                <h4 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em]">{{ __('Active Blocks & Content') }}</h4>
                <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">{{ $blocks->total() }} {{ __('Total Blocks') }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-white">
                    <thead>
                        <tr class="border-b border-white/5 bg-white/5">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Block Identity') }}</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Template (Type)') }}</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Unique Slug') }}</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-center">{{ __('Status') }}</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($blocks as $block)
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-indigo-400 border border-white/5 group-hover:scale-110 transition-transform" title="{{ e($block->blockType->type_key) }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold group-hover:text-indigo-400 transition-colors">{{ $block->resolvedName() }}</div>
                                            <div class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-0.5">{{ __('id_label') }}: #{{ $block->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="px-2.5 py-1 bg-white/5 border border-white/10 rounded-lg text-[10px] font-black text-indigo-300 uppercase tracking-widest">{{ $block->blockType->resolvedName() }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    @if($block->slug)
                                        <code class="px-2 py-0.5 bg-indigo-500/10 border border-indigo-500/20 rounded text-[10px] font-mono text-indigo-400">@block('{{ $block->slug }}')</code>
                                    @else
                                        <span class="text-[10px] text-slate-600 font-bold italic">{{ __('No slug assigned') }}</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-center">
                                     @if($block->is_active)
                                        <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 text-[9px] font-black rounded-full border border-emerald-500/20 uppercase tracking-widest">{{ __('Active') }}</span>
                                    @else
                                        <span class="px-3 py-1 bg-slate-500/10 text-slate-500 text-[9px] font-black rounded-full border border-white/5 uppercase tracking-widest">{{ __('Inactive') }}</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-2 lg:opacity-0 lg:group-hover:opacity-100 transition-all">
                                        <a href="{{ route('admin.block-instances.edit', $block) }}" class="p-2 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white rounded-xl border border-white/5 transition-all" title="{{ __('Edit') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-8 py-20 text-center text-slate-500">{{ __('No blocks found in your library.') }}</td></tr>
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
    </div>

    <!-- Tab panel: Advanced (templates / blueprints) -->
    <div x-show="activeTab === 'advanced'"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         role="tabpanel"
         aria-labelledby="tab-advanced"
         class="min-h-[50vh]"
         x-cloak>
        <div class="glass rounded-[3rem] border border-white/5 shadow-2xl overflow-hidden">
            <div class="p-8 sm:p-10 border-b border-white/5 bg-gradient-to-br from-indigo-600/10 to-transparent">
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
                    <div class="space-y-2">
                        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.25em]">{{ __('Advanced') }}</p>
                        <h4 class="text-2xl font-black text-white tracking-tight">{{ __('Templates & Blueprints') }}</h4>
                        <p class="text-sm text-slate-400 max-w-xl">{{ __('Define the architectural structure and settings schema for your blocks.') }}</p>
                    </div>
                    <a href="{{ route('admin.block-types.create') }}" class="inline-flex justify-center px-6 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-black rounded-2xl transition-all border border-indigo-500/30 shadow-lg shadow-indigo-600/20 uppercase tracking-widest shrink-0">
                        {{ __('New Blueprint') }}
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-white">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-8 py-4 text-[9px] font-black text-slate-500 uppercase tracking-widest">{{ __('Template Name') }}</th>
                            <th class="px-8 py-4 text-[9px] font-black text-slate-500 uppercase tracking-widest">{{ __('Key') }}</th>
                            <th class="px-8 py-4 text-[9px] font-black text-slate-500 uppercase tracking-widest text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($types as $type)
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="px-8 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-indigo-400/50 border border-white/5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                        </div>
                                        <span class="text-xs font-bold text-slate-400">{{ $type->resolvedName() }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-4">
                                    <code class="text-[9px] font-bold text-slate-500 uppercase">{{ $type->type_key }}</code>
                                </td>
                                <td class="px-8 py-4 text-right">
                                    <a href="{{ route('admin.block-types.edit', $type) }}" class="inline-flex p-1.5 bg-white/5 hover:bg-white/10 text-slate-500 hover:text-white rounded-lg transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<style>[x-cloak]{display:none!important}</style>
@endsection
