@extends('admin.layouts.base')

@section('title', __('edit') . ': ' . $tag->name)
@section('page_title', __('Edit Tag'))

@section('content')
<div class="mb-8 flex items-center gap-4">
    <a href="{{ route('admin.tags.index') }}" class="p-3 bg-white/5 hover:bg-white/10 rounded-2xl text-slate-400 hover:text-white transition-all">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
        <h3 class="text-xl font-bold tracking-tight text-white">{{ __('Modify Label') }}</h3>
        <p class="text-sm text-slate-400">{{ __('Update the name and visual indicator for this product tag.') }}</p>
    </div>
</div>

<form action="{{ route('admin.tags.update', $tag) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="max-w-xl mx-auto">
        <div class="glass p-8 rounded-[2rem] shadow-2xl border border-white/5 space-y-8">
            <h4 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em] mb-4">{{ __('Tag Attributes') }}</h4>
            
            <div class="space-y-2">
                <label for="name" class="text-sm font-bold text-slate-300 ml-1">{{ __('Tag Name') }}</label>
                <input type="text" name="name" id="name" value="{{ old('name', $tag->name) }}" required
                    class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all">
                @error('name') <p class="text-xs text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-4">
                <label for="color" class="text-sm font-bold text-slate-300 ml-1">{{ __('Representative Color') }}</label>
                <div class="flex items-center gap-4 p-4 bg-white/5 rounded-2xl border border-white/10">
                    <input type="color" name="color" id="color" value="{{ old('color', $tag->color ?? '#6366f1') }}"
                        class="w-12 h-12 bg-transparent border-none rounded-lg cursor-pointer overflow-hidden">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-slate-500 font-black uppercase tracking-widest">{{ __('Pick a Color') }}</span>
                        <p class="text-xs text-slate-400 font-medium">{{ __('Update the visual branding indicator for this label.') }}</p>
                    </div>
                </div>
                @error('color') <p class="text-xs text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 flex flex-col gap-3">
                <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-2xl transition-all shadow-lg shadow-indigo-600/20 active:scale-95 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    {{ __('Save Changes') }}
                </button>
                <a href="{{ route('admin.tags.index') }}" class="w-full py-4 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white text-center font-bold rounded-2xl transition-all active:scale-95 text-xs uppercase tracking-widest">
                    {{ __('Cancel') }}
                </a>
            </div>
        </div>
    </div>
</form>
@endsection
