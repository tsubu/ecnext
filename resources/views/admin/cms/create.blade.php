@extends('admin.layouts.base')

@section('title', __('Add New Block'))
@section('page_title', __('Create CMS Block'))

@section('content')
<div class="mb-8 flex items-center gap-4">
    <a href="{{ route('admin.cms-blocks.index') }}" class="p-3 bg-white/5 hover:bg-white/10 rounded-2xl text-slate-400 hover:text-white transition-all">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
        <h3 class="text-xl font-bold tracking-tight text-white">{{ __('Create New Component') }}</h3>
        <p class="text-sm text-slate-400">{{ __('Define a new reusable block with a unique identifier and custom JSON data.') }}</p>
    </div>
</div>

<form action="{{ route('admin.cms-blocks.store') }}" method="POST">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 text-white">
        <!-- Main Form -->
        <div class="lg:col-span-8 space-y-8">
            <div class="glass p-8 rounded-[2rem] shadow-2xl border border-white/5 space-y-6">
                <h4 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em]">{{ __('General Information') }}</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="title" class="text-sm font-bold text-slate-300 ml-1">{{ __('Block Title') }}</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="{{ __('e.g. Free Shipping Banner') }}" required
                            class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-600">
                        @error('title') <p class="text-xs text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="slug" class="text-sm font-bold text-slate-300 ml-1">{{ __('Identifier (Slug)') }}</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}" placeholder="{{ __('e.g. top-free-shipping') }}" required
                            class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-600">
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest ml-1">{{ __('Must be lowercase and unique') }}</p>
                        @error('slug') <p class="text-xs text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-2 pt-2">
                    <label for="content_json" class="text-sm font-bold text-slate-300 ml-1">{{ __('Content Data (JSON)') }}</label>
                    <div class="relative group">
                        <textarea name="content_json" id="content_json" rows="12" placeholder='{ "text": "Free shipping on orders over $50", "bg_color": "indigo" }'
                            class="w-full px-5 py-4 bg-slate-900/50 border border-white/10 rounded-3xl text-indigo-200 font-mono text-xs focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all resize-none">{{ old('content_json') }}</textarea>
                        <div class="absolute top-4 right-4 text-[10px] font-black text-white/20 uppercase tracking-widest pointer-events-none group-hover:text-white/40 transition-colors">{{ __('JSON Editor') }}</div>
                    </div>
                    @error('content_json') <p class="text-xs text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Sidebar / Publishing -->
        <div class="lg:col-span-4 space-y-8">
            <div class="glass p-8 rounded-[2rem] shadow-2xl border border-white/5 space-y-6 sticky top-24">
                <h4 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em] mb-4">{{ __('Initial Status') }}</h4>
                
                <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl border border-white/5">
                    <div class="flex flex-col">
                        <span class="text-xs font-bold">{{ __('Active Status') }}</span>
                        <span class="text-[10px] text-slate-500 font-medium uppercase tracking-wider">{{ __('Public on Storefront') }}</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', true) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                </div>

                <div class="pt-4 flex flex-col gap-3">
                    <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-2xl transition-all shadow-lg shadow-indigo-600/20 active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M12 4v16m8-8H4"/></svg>
                        {{ __('Create Block') }}
                    </button>
                    <a href="{{ route('admin.cms-blocks.index') }}" class="w-full py-4 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white text-center font-bold rounded-2xl transition-all active:scale-95 text-xs uppercase tracking-widest">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
