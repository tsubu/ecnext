@extends('admin.layouts.base')

@section('title', isset($block_type) ? __('Edit Component') : __('New Component'))
@section('page_title', isset($block_type) ? __('Edit Component') : __('New Component'))

@section('content')
<div class="mb-8 flex items-center gap-4 text-white">
    <a href="{{ route('admin.block-types.index') }}" class="p-3 bg-white/5 hover:bg-white/10 rounded-2xl text-slate-400 hover:text-white transition-all shadow-lg active:scale-95">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
        <h3 class="text-xl font-black tracking-tight">{{ isset($block_type) ? __('Modify Architecture') : __('Define New Pattern') }}</h3>
        <p class="text-sm text-slate-400 mt-0.5">{{ __('Define how this component behaves and what settings it exposes.') }}</p>
    </div>
</div>

<div class="max-w-5xl">
    <form action="{{ isset($block_type) ? route('admin.block-types.update', $block_type) : route('admin.block-types.store') }}" method="POST" class="space-y-8">
        @csrf
        @if(isset($block_type)) @method('PUT') @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Basic Settings -->
            <div class="lg:col-span-1 space-y-8">
                <div class="glass p-8 rounded-[2.5rem] border border-white/5 shadow-2xl space-y-6">
                    <h4 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em] mb-4">{{ __('Identity') }}</h4>
                    
                    <div class="space-y-2">
                        <label for="name" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1 text-white">{{ __('Readable Name') }}</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $block_type->name ?? '') }}" required placeholder="{{ __('e.g. Hero Section') }}"
                            class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm font-bold focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all">
                    </div>

                    <div class="space-y-2">
                        <label for="type_key" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1 text-white">{{ __('Type Key (Unique)') }}</label>
                        <input type="text" name="type_key" id="type_key" value="{{ old('type_key', $block_type->type_key ?? '') }}" required placeholder="{{ __('e.g. hero') }}"
                            class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm font-bold focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all {{ isset($block_type) ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ isset($block_type) ? 'readonly' : '' }}>
                        <p class="text-[9px] text-slate-500 font-medium ml-1 mt-1">{{ __('Used in templates for rendering identification.') }}</p>
                    </div>

                    <div class="space-y-2">
                        <label for="icon" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1 text-white">{{ __('Icon Handle') }}</label>
                        <input type="text" name="icon" id="icon" value="{{ old('icon', $block_type->icon ?? 'cube') }}"
                            class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm font-bold focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all">
                    </div>
                </div>
            </div>

            <!-- Right Column: Schema Editor -->
            <div class="lg:col-span-2 space-y-8 text-white">
                <div class="glass p-8 rounded-[2.5rem] border border-white/5 shadow-2xl space-y-6">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-black text-emerald-400 uppercase tracking-[0.2em]">{{ __('Configuration Schema (JSON)') }}</h4>
                        <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 text-[8px] font-black uppercase tracking-widest rounded border border-emerald-500/20">JSON Schema v4+</span>
                    </div>
                    
                    <div class="space-y-2">
                        <textarea name="schema" id="schema" rows="18" 
                            class="w-full px-6 py-6 bg-slate-900 border border-white/10 rounded-2xl text-indigo-300 text-xs font-mono focus:ring-2 focus:ring-emerald-500/50 outline-none transition-all resize-none leading-relaxed"
                            placeholder='{ "title": { "type": "string" }, "settings": { ... } }'>{{ old('schema', isset($block_type->schema) ? json_encode($block_type->schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '') }}</textarea>
                        @error('schema') <p class="text-rose-500 text-[10px] font-black uppercase tracking-tight ml-1 mt-1">{{ $message }}</p> @enderror
                        <p class="text-[10px] text-slate-500 font-medium ml-1 mt-1">{{ __('Define the fields administrators will fill when placing this block.') }}</p>
                    </div>
                </div>

                <div class="flex justify-end gap-4 mt-8">
                    <a href="{{ route('admin.block-types.index') }}" class="px-8 py-4 text-slate-400 hover:text-white text-xs font-black uppercase tracking-widest transition-all">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="px-10 py-4 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-black rounded-2xl transition-all shadow-lg shadow-indigo-600/20 active:scale-95 uppercase tracking-widest">
                        {{ isset($block_type) ? __('Update Definition') : __('Save Component Definition') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
