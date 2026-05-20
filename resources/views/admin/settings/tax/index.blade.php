@extends('admin.layouts.base')

@section('title', __('Taxation Governance'))
@section('page_title', __('Tax Rules'))

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h3 class="text-xl font-bold tracking-tight text-white">{{ __('Fiscal Regulations') }}</h3>
        <p class="text-sm text-slate-400">{{ __('Define and schedule period-based tax rates for dynamic system-wide synchronization.') }}</p>
    </div>
    <a href="{{ route('admin.tax_rules.create') }}" class="px-6 py-4 bg-indigo-600 hover:bg-indigo-400 text-white font-black rounded-2xl transition-all shadow-lg shadow-indigo-600/20 active:scale-95 flex items-center gap-3 text-sm">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M12 4v16m8-8H4"/></svg>
        {{ __('New Regulation') }}
    </a>
</div>

<div class="glass rounded-[2.5rem] border border-white/5 shadow-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-indigo-600/10">
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-indigo-400 border-b border-white/5">{{ __('Regulation Name') }}</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-indigo-400 border-b border-white/5">{{ __('Rate') }}</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-indigo-400 border-b border-white/5">{{ __('Active Period') }}</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-indigo-400 border-b border-white/5">{{ __('Status') }}</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-indigo-400 border-b border-white/5 text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($taxRules as $rule)
                    <tr class="group hover:bg-white/[0.02] transition-colors">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-white">{{ $rule->name }}</p>
                                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-0.5">ID: #{{ $rule->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-lg font-black text-white tracking-tighter">{{ $rule->rate }} <span class="text-xs text-indigo-400">%</span></span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400">
                                    <span class="px-1.5 py-0.5 bg-slate-800 rounded uppercase text-[8px] text-slate-500">{{ __('From') }}</span>
                                    {{ $rule->starts_at->format('Y/m/d H:i') }}
                                </div>
                                <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400">
                                    <span class="px-1.5 py-0.5 bg-slate-800 rounded uppercase text-[8px] text-slate-500">{{ __('To') }}</span>
                                    {{ $rule->expires_at ? $rule->expires_at->format('Y/m/d H:i') : __('Permanent') }}
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            @if($rule->starts_at->isFuture())
                                <span class="px-3 py-1 bg-amber-500/10 text-amber-500 text-[10px] font-black rounded-full uppercase tracking-widest">{{ __('Scheduled') }}</span>
                            @elseif($rule->expires_at && $rule->expires_at->isPast())
                                <span class="px-3 py-1 bg-slate-500/10 text-slate-500 text-[10px] font-black rounded-full uppercase tracking-widest">{{ __('Expired') }}</span>
                            @elseif($rule->is_active)
                                <span class="px-3 py-1 bg-emerald-500/10 text-emerald-500 text-[10px] font-black rounded-full uppercase tracking-widest">{{ __('Active') }}</span>
                            @else
                                <span class="px-3 py-1 bg-rose-500/10 text-rose-500 text-[10px] font-black rounded-full uppercase tracking-widest">{{ __('Disabled') }}</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all transform translate-x-2 group-hover:translate-x-0">
                                <a href="{{ route('admin.tax_rules.edit', $rule) }}" class="p-2.5 bg-white/5 hover:bg-white/10 rounded-xl text-slate-400 hover:text-white transition-all shadow-lg active:scale-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.tax_rules.destroy', $rule) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure? This may affect products relying on this regulation.') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2.5 bg-white/5 hover:bg-rose-500/20 rounded-xl text-slate-400 hover:text-rose-500 transition-all shadow-lg active:scale-95">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-8 py-24 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-20 h-20 rounded-3xl bg-white/5 flex items-center justify-center text-slate-600">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <p class="text-slate-500 font-bold uppercase tracking-widest text-xs">{{ __('No tax regulations defined.') }}</p>
                                <a href="{{ route('admin.tax_rules.create') }}" class="mt-4 text-indigo-400 font-black uppercase tracking-widest text-[10px] hover:text-indigo-300 transition-colors">
                                    {{ __('Establish First Rule') }} →
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($taxRules->hasPages())
        <div class="px-8 py-6 bg-white/[0.02] border-t border-white/5">
            {{ $taxRules->links() }}
        </div>
    @endif
</div>
@endsection
