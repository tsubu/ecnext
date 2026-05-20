@extends('admin.layouts.base')

@section('title', __('Marketing Coupons'))
@section('page_title', __('Coupons & Discounts'))

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h3 class="text-xl font-bold tracking-tight text-white">{{ __('Promotion Management') }}</h3>
        <p class="text-sm text-slate-400 mt-1">{{ __('Manage discount codes to drive sales and customer loyalty.') }}</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.coupons.create') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-2xl transition-all shadow-lg shadow-indigo-600/20 flex items-center gap-2 active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M12 4v16m8-8H4"/></svg>
            {{ __('Add New Coupon') }}
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
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Coupon Info') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Discount') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-center">{{ __('Validity') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-center">{{ __('Usage') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($coupons as $coupon)
                    <tr class="hover:bg-white/[0.02] transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500/20 to-purple-500/20 flex items-center justify-center text-indigo-400 border border-white/5 shadow-inner">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-white tracking-tight uppercase group-hover:text-indigo-400 transition-colors">{{ $coupon->code }}</span>
                                    <span class="text-[10px] text-slate-400 font-bold opacity-60 mt-0.5">{{ $coupon->name }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-lg font-black text-white">
                                    @if($coupon->discount_type === 'percentage')
                                        {{ number_format($coupon->discount_value, 0) }}%
                                    @else
                                        ¥{{ number_format($coupon->discount_value) }}
                                    @endif
                                </span>
                                <span class="text-[9px] text-slate-500 font-black uppercase tracking-widest">{{ $coupon->discount_type === 'percentage' ? __('Off %') : __('Flat Reduction') }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if(!$coupon->is_active)
                                <span class="px-3 py-1 bg-slate-500/10 text-slate-500 text-[9px] font-black rounded-full border border-white/5 uppercase tracking-widest">{{ __('Inactive') }}</span>
                            @elseif($coupon->expires_at && $coupon->expires_at->isPast())
                                <span class="px-3 py-1 bg-rose-500/10 text-rose-500 text-[9px] font-black rounded-full border border-rose-500/20 uppercase tracking-widest">{{ __('Expired') }}</span>
                            @else
                                <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 text-[9px] font-black rounded-full border border-emerald-500/20 uppercase tracking-widest">{{ __('Active') }}</span>
                            @endif
                            
                            @if($coupon->expires_at)
                                <p class="text-[9px] text-slate-600 font-bold mt-2 italic">{{ __('Ends') }} {{ $coupon->expires_at->diffForHumans() }}</p>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="flex flex-col items-center">
                                <span class="text-sm font-black text-white">{{ $coupon->usage_count }}</span>
                                <span class="text-[9px] text-slate-500 font-black uppercase tracking-widest">/ {{ $coupon->usage_limit ?: '∞' }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2 lg:opacity-0 lg:group-hover:opacity-100 transition-all scale-95 group-hover:scale-100">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="p-2 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white rounded-xl transition-all border border-white/5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this coupon code?') }}')">
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
                        <td colspan="5" class="px-8 py-20 text-center text-slate-500 font-medium italic">
                            {{ __('No coupons created yet.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
