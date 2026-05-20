@extends('admin.layouts.base')

@section('title', __('Add News Entry'))
@section('page_title', __('Create Notice'))

@section('content')
<div class="mb-8 flex items-center gap-4">
    <a href="{{ route('admin.notices.index') }}" class="p-3 bg-white/5 hover:bg-white/10 rounded-2xl text-slate-400 hover:text-white transition-all">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
        <h3 class="text-xl font-bold tracking-tight text-white">{{ __('Draft New Announcement') }}</h3>
        <p class="text-sm text-slate-400">{{ __('Create a new news entry to be displayed on the storefront.') }}</p>
    </div>
</div>

<form action="{{ route('admin.notices.store') }}" method="POST">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 text-white">
        <!-- Main Form -->
        <div class="lg:col-span-8 space-y-8">
            <div class="glass p-8 rounded-[2rem] shadow-2xl border border-white/5 space-y-6">
                <h4 class="text-xs font-black text-rose-400 uppercase tracking-[0.2em]">{{ __('Content Details') }}</h4>
                
                <div class="space-y-2">
                    <label for="title" class="text-sm font-bold text-slate-300 ml-1">{{ __('Subject / Title') }}</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="{{ __('e.g. New Seasonal Collection Launch') }}" required
                        class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-rose-500/50 focus:border-rose-500 outline-none transition-all">
                    @error('title') <p class="text-xs text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2 pt-2">
                    <label for="content" class="text-sm font-bold text-slate-300 ml-1">{{ __('News Content') }}</label>
                    <textarea name="content" id="content" rows="12" placeholder="{{ __('Type your announcement here...') }}" required
                        class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-3xl text-slate-200 font-medium focus:ring-2 focus:ring-rose-500/50 focus:border-rose-500 outline-none transition-all">{{ old('content') }}</textarea>
                    @error('content') <p class="text-xs text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Sidebar / Publishing -->
        <div class="lg:col-span-4 space-y-8">
            <div class="glass p-8 rounded-[2rem] shadow-2xl border border-white/5 space-y-6 sticky top-24">
                <h4 class="text-xs font-black text-rose-400 uppercase tracking-[0.2em] mb-4">{{ __('Publishing Options') }}</h4>
                
                <div class="space-y-2">
                    <label for="published_at" class="text-sm font-bold text-slate-300 ml-1">{{ __('Schedule Start') }}</label>
                    <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}"
                        class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:ring-2 focus:ring-rose-500/50 focus:border-rose-500 outline-none transition-all">
                    <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wider ml-1 mt-1">{{ __('Set when this news becomes visible.') }}</p>
                </div>

                <div class="space-y-2 pt-2">
                    <label for="expired_at" class="text-sm font-bold text-slate-300 ml-1">{{ __('Schedule End') }}</label>
                    <input type="datetime-local" name="expired_at" id="expired_at" value="{{ old('expired_at') }}"
                        class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:ring-2 focus:ring-rose-500/50 focus:border-rose-500 outline-none transition-all">
                    <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wider ml-1 mt-1">{{ __('Optional. Set when this news expires.') }}</p>
                </div>

                <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl border border-white/5 mt-6">
                    <div class="flex flex-col">
                        <span class="text-xs font-bold">{{ __('Active Status') }}</span>
                        <span class="text-[10px] text-slate-500 font-medium uppercase tracking-wider">{{ __('Visible to Customers') }}</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', true) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-500"></div>
                    </label>
                </div>

                <div class="pt-4 flex flex-col gap-3">
                    <button type="submit" class="w-full py-4 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded-2xl transition-all shadow-lg shadow-rose-600/20 active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M12 19l7-7-7-7m5 7H3"/></svg>
                        {{ __('Publish News') }}
                    </button>
                    <a href="{{ route('admin.notices.index') }}" class="w-full py-4 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white text-center font-bold rounded-2xl transition-all active:scale-95 text-xs uppercase tracking-widest">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
