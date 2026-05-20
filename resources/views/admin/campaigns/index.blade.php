@extends('admin.layouts.base')

@section('title', __('Sales Campaigns'))
@section('page_title', __('Marketing & Promotions'))

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h3 class="text-xl font-bold tracking-tight text-white">{{ __('Campaign Events') }}</h3>
        <p class="text-sm text-slate-400 mt-1">{{ __('Schedule bulk discounts and highlight seasonal sale events.') }}</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.campaigns.create') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-2xl transition-all shadow-lg shadow-indigo-600/20 flex items-center gap-2 active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M12 4v16m8-8H4"/></svg>
            {{ __('New Campaign') }}
        </a>
    </div>
</div>

@if(session('success'))
    <div class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400 text-sm font-bold flex items-center gap-3 text-glow-emerald">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
@endif

<div class="glass rounded-[2rem] overflow-hidden border border-white/5 shadow-2xl">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-white/5 bg-white/5">
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Campaign Event') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Offers') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-center">{{ __('Schedule') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-center">{{ __('Status') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($campaigns as $campaign)
                    <tr class="hover:bg-white/[0.02] transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-rose-500 to-orange-500 p-[1px]">
                                    <div class="w-full h-full rounded-[0.9rem] bg-slate-900 flex items-center justify-center text-rose-500">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 11V9a2 2 0 00-2-2m2 4v4a2 2 0 104 0v-1m-4-3H9m2 0h4m6 1a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-base font-black text-white tracking-tight group-hover:text-rose-400 transition-colors">{{ $campaign->name }}</span>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[9px] px-2 py-0.5 bg-white/5 rounded-md text-slate-500 font-bold uppercase tracking-widest">{{ $campaign->slug }}</span>
                                        <span class="text-[9px] text-slate-600 font-bold">{{ __('ID: :id', ['id' => $campaign->id]) }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-xl font-black text-rose-500">
                                    @if($campaign->discount_type === 'percentage')
                                        {{ number_format($campaign->discount_value, 0) }}% OFF
                                    @else
                                        -¥{{ number_format($campaign->discount_value) }}
                                    @endif
                                </span>
                                <span class="text-[9px] text-slate-500 font-black uppercase tracking-[0.2em] mt-0.5">{{ __('Applied to selected products') }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($campaign->starts_at || $campaign->expires_at)
                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-[10px] font-bold text-white">{{ $campaign->starts_at ? $campaign->starts_at->format('M d, Y') : 'Anytime' }}</span>
                                    <svg class="w-3 h-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="3" d="M19 9l-7 7-7-7"/></svg>
                                    <span class="text-[10px] font-bold text-slate-400">{{ $campaign->expires_at ? $campaign->expires_at->format('M d, Y') : 'Infinity' }}</span>
                                </div>
                            @else
                                <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">— {{ __('Permanent') }} —</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-center">
                            @php
                                $now = now();
                                $isActive = $campaign->is_active;
                                $isUpcoming = $campaign->starts_at && $now->lt($campaign->starts_at);
                                $isExpired = $campaign->expires_at && $now->gt($campaign->expires_at);
                            @endphp

                            @if(!$isActive)
                                <div class="inline-flex items-center gap-2 px-3 py-1 bg-slate-500/10 text-slate-500 rounded-full border border-white/5">
                                    <div class="w-1.5 h-1.5 rounded-full bg-slate-500"></div>
                                    <span class="text-[9px] font-black uppercase tracking-[0.15em]">{{ __('Paused') }}</span>
                                </div>
                            @elseif($isUpcoming)
                                <div class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-500/10 text-indigo-400 rounded-full border border-indigo-500/20">
                                    <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></div>
                                    <span class="text-[9px] font-black uppercase tracking-[0.15em]">{{ __('Upcoming') }}</span>
                                </div>
                            @elseif($isExpired)
                                <div class="inline-flex items-center gap-2 px-3 py-1 bg-rose-500/10 text-rose-500 rounded-full border border-rose-500/20">
                                    <div class="w-1.5 h-1.5 rounded-full bg-rose-500"></div>
                                    <span class="text-[9px] font-black uppercase tracking-[0.15em]">{{ __('Closed') }}</span>
                                </div>
                            @else
                                <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/10 text-emerald-400 rounded-full border border-emerald-500/20 shadow-[0_0_10px_rgba(16,185,129,0.1)]">
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_5px_#10b981]"></div>
                                    <span class="text-[9px] font-black uppercase tracking-[0.15em]">{{ __('Live Now') }}</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2 lg:opacity-0 lg:group-hover:opacity-100 transition-all scale-95 group-hover:scale-100">
                                <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="p-2 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white rounded-xl transition-all border border-white/5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.campaigns.destroy', $campaign) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to end this campaign?') }}')">
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
                        <td colspan="5" class="px-8 py-20 text-center text-slate-600 font-medium italic">
                            {{ __('No promotional campaigns active.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
