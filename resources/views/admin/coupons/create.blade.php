@extends('admin.layouts.base')

@section('title', __('Create Coupon'))
@section('page_title', __('Forge New Discount Code'))

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('admin.coupons.index') }}" class="text-xs font-bold text-slate-500 uppercase tracking-widest hover:text-white transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M15 19l-7-7 7-7"/></svg>
            {{ __('Back to Promotions') }}
        </a>
    </div>

    <form action="{{ route('admin.coupons.store') }}" method="POST" class="space-y-8">
        @csrf
        
        <div class="glass p-10 rounded-[2.5rem] border border-white/5 space-y-10 shadow-2xl">
            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">{{ __('Coupon Code') }}</label>
                    <input type="text" name="code" value="{{ old('code') }}" required placeholder="e.g. SUMMER2026" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-black uppercase tracking-tighter focus:ring-2 focus:ring-indigo-500 outline-none transition-all placeholder:text-white/10">
                    @error('code') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">{{ __('Internal Name') }}</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Summer Clearance Promotion" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-medium focus:ring-2 focus:ring-indigo-500 outline-none transition-all placeholder:text-white/10">
                    @error('name') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Discount Logic -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 p-8 bg-white/5 rounded-3xl border border-white/5">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em] ml-1">{{ __('Discount Type') }}</label>
                    <select name="discount_type" required class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-white text-xs font-bold outline-none focus:border-indigo-500">
                        <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>{{ __('Fixed Amount (¥)') }}</option>
                        <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>{{ __('Percentage (%)') }}</option>
                    </select>
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em] ml-1">{{ __('Discount Value') }}</label>
                    <input type="number" name="discount_value" value="{{ old('discount_value') }}" required placeholder="0" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-white text-xs font-bold outline-none focus:border-indigo-500">
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em] ml-1">{{ __('Min. Order amount') }}</label>
                    <input type="number" name="min_order_amount" value="{{ old('min_order_amount') }}" placeholder="0" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-white text-xs font-bold outline-none focus:border-indigo-500">
                </div>
            </div>

            <!-- Limits & Scheduling -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">{{ __('Start Date') }}</label>
                    <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-xs outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">{{ __('Expiry Date') }}</label>
                    <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-xs outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">{{ __('Usage Limit') }}</label>
                    <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" placeholder="No limit" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-medium focus:ring-2 focus:ring-indigo-500 outline-none transition-all placeholder:text-white/10">
                </div>
            </div>

            <!-- Active Switch -->
            <div class="flex items-center gap-4 px-1">
                <div class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', true) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600 transition-all"></div>
                </div>
                <span class="text-xs font-bold text-slate-400 select-none">{{ __('Activate this coupon immediately') }}</span>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="px-10 py-4 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-black rounded-2xl transition-all shadow-xl shadow-indigo-600/20 active:scale-95">
                {{ __('Create Promotion Code') }}
            </button>
        </div>
    </form>
</div>
@endsection
