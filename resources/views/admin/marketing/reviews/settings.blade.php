@extends('admin.layouts.base')

@section('title', __('Review Rewards'))
@section('page_title', __('Automated Reward Configuration'))

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="glass p-8 rounded-[3rem] border border-white/5 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/5 blur-[100px] pointer-events-none"></div>
        
        <form action="{{ route('admin.review-rewards.update') }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="flex items-center justify-between p-6 bg-white/5 rounded-3xl border border-white/5">
                <div>
                    <h4 class="text-sm font-bold text-white">{{ __('Enable Automatic Rewards') }}</h4>
                    <p class="text-[10px] text-slate-400 uppercase tracking-widest mt-1">{{ __('Rewards are issued upon review approval.') }}</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ $setting->is_active ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500 shadow-inner"></div>
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-4">
                    <label class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] ml-1">{{ __('Trigger Condition') }}</label>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-300">{{ __('Minimum Rating') }}</label>
                        <select name="min_rating" class="w-full px-5 py-4 bg-white/10 border border-white/10 rounded-2xl text-white font-bold focus:ring-2 focus:ring-emerald-500/50 outline-none transition-all">
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ $setting->min_rating == $i ? 'selected' : '' }}>{{ $i }} {{ __('Stars & Above') }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] ml-1">{{ __('Reward Type') }}</label>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-300">{{ __('Select Bonus Asset') }}</label>
                        <select name="reward_type" x-model="rewardType" class="w-full px-5 py-4 bg-white/10 border border-white/10 rounded-2xl text-white font-bold focus:ring-2 focus:ring-emerald-500/50 outline-none transition-all" x-data="{ rewardType: '{{ $setting->reward_type }}' }">
                            <option value="point">{{ __('Store Points') }}</option>
                            <option value="coupon">{{ __('Coupon Voucher') }}</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] ml-1">{{ __('Issuance Timing') }}</label>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-300">{{ __('Days from Shipping') }}</label>
                        <div class="relative">
                            <input type="number" name="reward_delay_days" value="{{ $setting->reward_delay_days ?? '7' }}" min="0" class="w-full px-5 py-4 bg-white/10 border border-white/10 rounded-2xl text-white font-black text-lg focus:ring-2 focus:ring-emerald-500/50 outline-none transition-all">
                            <span class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-500 font-bold tracking-widest">{{ __('DAYS') }}</span>
                        </div>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-2">{{ __('Counted from order shipping date.') }}</p>
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-white/5 space-y-6" x-data="{ type: '{{ $setting->reward_type }}' }" @change="type = $event.target.value">
                <div x-show="type === 'point'" class="space-y-4">
                    <label class="text-sm font-bold text-slate-300">{{ __('Point Amount') }}</label>
                    <div class="relative">
                        <input type="number" name="reward_value" value="{{ $setting->reward_type === 'point' ? $setting->reward_value : '0' }}" class="w-full px-5 py-4 bg-white/10 border border-white/10 rounded-2xl text-white font-black text-lg focus:ring-2 focus:ring-emerald-500/50 outline-none transition-all">
                        <span class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-500 font-bold tracking-widest">{{ __('PTS') }}</span>
                    </div>
                </div>

                <div x-show="type === 'coupon'" class="space-y-6">
                    <div class="space-y-4">
                        <label class="text-sm font-bold text-slate-300">{{ __('Template Coupon') }}</label>
                        <select name="reward_value" class="w-full px-5 py-4 bg-white/10 border border-white/10 rounded-2xl text-white font-bold focus:ring-2 focus:ring-emerald-500/50 outline-none transition-all">
                            @foreach($coupons as $coupon)
                                <option value="{{ $coupon->id }}" {{ ($setting->reward_type === 'coupon' && $setting->reward_value == $coupon->id) ? 'selected' : '' }}>
                                    {{ $coupon->name }} ({{ $coupon->code }}) - {{ $coupon->discount_type === 'percentage' ? $coupon->discount_value.'%' : '¥'.number_format($coupon->discount_value) }} {{ __('OFF') }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">{{ __('A unique, individual code will be generated based on this template.') }}</p>
                    </div>

                    <div class="space-y-4">
                        <label class="text-sm font-bold text-slate-300">{{ __('Code Expiry (Days)') }}</label>
                        <div class="relative">
                            <input type="number" name="coupon_expiry_days" value="{{ $setting->coupon_expiry_days ?? '30' }}" class="w-full px-5 py-4 bg-white/10 border border-white/10 rounded-2xl text-white font-black text-lg focus:ring-2 focus:ring-emerald-500/50 outline-none transition-all">
                            <span class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-500 font-bold tracking-widest">{{ __('DAYS') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-8 flex justify-end">
                <button type="submit" class="px-12 py-4 bg-emerald-600 hover:bg-emerald-500 text-white font-black rounded-2xl transition-all shadow-lg shadow-emerald-600/20 active:scale-95 flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    {{ __('Save Master Settings') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
