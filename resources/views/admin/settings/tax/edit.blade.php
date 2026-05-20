@extends('admin.layouts.base')

@section('title', __('Refine Regulation') . ': ' . $taxRule->name)
@section('page_title', __('Edit Tax Rule'))

@section('content')
<div class="mb-8 flex items-center gap-4">
    <a href="{{ route('admin.tax_rules.index') }}" class="p-3 bg-white/5 hover:bg-white/10 rounded-2xl text-slate-400 hover:text-white transition-all shadow-lg active:scale-95">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
        <h3 class="text-xl font-bold tracking-tight text-white">{{ __('Modify Fiscal Directive') }}</h3>
        <p class="text-sm text-slate-400">{{ __('Update temporal boundaries and rate metrics for active system synchronization.') }}</p>
    </div>
</div>

<form action="{{ route('admin.tax_rules.update', $taxRule) }}" method="POST" class="max-w-4xl">
    @csrf
    @method('PUT')
    
    <div class="glass p-8 rounded-[3rem] border border-white/5 shadow-2xl space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label for="name" class="text-sm font-bold text-slate-300 ml-1">{{ __('Regulation Name') }}</label>
                <input type="text" name="name" id="name" value="{{ old('name', $taxRule->name) }}" required
                    class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all">
                @error('name') <p class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div class="space-y-2">
                <label for="rate" class="text-sm font-bold text-slate-300 ml-1">{{ __('Applicable Rate (%)') }}</label>
                <div class="relative">
                    <input type="number" step="0.01" name="rate" id="rate" value="{{ old('rate', $taxRule->rate) }}" required
                        class="w-full pl-5 pr-12 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-black text-lg focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all tracking-tighter">
                    <span class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-500 font-black text-xs">%</span>
                </div>
                @error('rate') <p class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label for="starts_at" class="text-sm font-bold text-slate-300 ml-1">{{ __('Effective Start Date') }}</label>
                <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at', $taxRule->starts_at->format('Y-m-d\TH:i')) }}" required
                    class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-bold focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all">
                @error('starts_at') <p class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div class="space-y-2">
                <label for="expires_at" class="text-sm font-bold text-slate-300 ml-1">{{ __('Expiration Date (Optional)') }}</label>
                <input type="datetime-local" name="expires_at" id="expires_at" value="{{ old('expires_at', $taxRule->expires_at ? $taxRule->expires_at->format('Y-m-d\TH:i') : '') }}"
                    class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-bold focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all">
                @error('expires_at') <p class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="pt-8 border-t border-white/5 flex items-center justify-between">
            <div class="flex items-center gap-3 bg-white/5 px-4 py-2 rounded-xl border border-white/5">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">{{ __('Regulation Active') }}</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ $taxRule->is_active ? 'checked' : '' }}>
                    <div class="w-10 h-5 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                </label>
            </div>

            <button type="submit" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-400 text-white font-black rounded-2xl transition-all shadow-lg shadow-indigo-600/20 active:scale-95 flex items-center gap-3 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                {{ __('Update Directive') }}
            </button>
        </div>
    </div>
</form>
@endsection
