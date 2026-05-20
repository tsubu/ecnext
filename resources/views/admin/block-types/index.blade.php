@extends('admin.layouts.base')

@section('title', __('Cross-Theme Components'))
@section('page_title', __('Components Library'))

@section('content')
<div class="mb-8 flex justify-between items-end text-white">
    <div>
        <h3 class="text-xl font-black tracking-tight">{{ __('Architectural Blocks') }}</h3>
        <p class="text-sm text-slate-400 mt-1">{{ __('Manage reusable UI components and their configuration schemas across all themes.') }}</p>
    </div>
    <a href="{{ route('admin.block-types.create') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-black rounded-2xl transition-all shadow-lg shadow-indigo-600/20 flex items-center gap-2 active:scale-95 uppercase tracking-widest">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M12 4v16m8-8H4"/></svg>
        {{ __('New Component') }}
    </a>
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

<div class="glass rounded-[3rem] overflow-hidden border border-white/5 shadow-2xl">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-white">
            <thead>
                <tr class="border-b border-white/5 bg-white/5">
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Component Archetype') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Key / Type') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-center">{{ __('JSON Schema') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-center">{{ __('Availability') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($types as $type)
                    <tr class="hover:bg-white/[0.02] transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center border border-white/5 text-indigo-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-sm font-black group-hover:text-indigo-400 transition-colors">{{ $type->resolvedName() }}</span>
                                    @if($type->is_system)
                                        <span class="ml-2 px-2 py-0.5 bg-indigo-500/10 text-indigo-400 text-[8px] font-black uppercase tracking-widest rounded border border-indigo-500/20">{{ __('System') }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <code class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $type->type_key }}</code>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($type->schema)
                                <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 text-[10px] font-black rounded-full border border-emerald-500/20 uppercase tracking-widest">
                                    {{ __('Defined') }}
                                </span>
                            @else
                                <span class="px-3 py-1 bg-slate-500/10 text-slate-500 text-[10px] font-black rounded-full border border-white/5 uppercase tracking-widest">
                                    {{ __('Empty') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-center">
                            <span class="px-3 py-1 bg-white/5 text-slate-400 text-[10px] font-black rounded-full border border-white/5 uppercase tracking-widest">
                                {{ __('All Themes') }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2 lg:opacity-0 lg:group-hover:opacity-100 transition-all scale-95 group-hover:scale-100">
                                <a href="{{ route('admin.block-types.edit', $type) }}" class="p-2.5 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white rounded-xl transition-all border border-white/5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                @if(!$type->is_system)
                                    <form action="{{ route('admin.block-types.destroy', $type) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure? Existing instances of this component will break.') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2.5 bg-white/5 hover:bg-rose-500/10 text-slate-400 hover:text-rose-500 rounded-xl transition-all border border-white/5">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center text-slate-500">
                            {{ __('No components found in the library.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
