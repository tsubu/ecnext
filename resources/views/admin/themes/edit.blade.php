@extends('admin.layouts.base')

@section('title', isset($theme) ? __('Edit Theme') : __('Add Theme'))
@section('page_title', isset($theme) ? __('Edit Theme') : __('Add Theme'))

@section('content')
<div class="mb-8 flex items-center gap-4 text-white">
    <a href="{{ route('admin.themes.index') }}" class="p-3 bg-white/5 hover:bg-white/10 rounded-2xl text-slate-400 hover:text-white transition-all shadow-lg active:scale-95">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
        <h3 class="text-xl font-black tracking-tight">{{ isset($theme) ? __('Modify Appearance') : __('Register New Identity') }}</h3>
        <p class="text-sm text-slate-400 mt-0.5">{{ __('Configure template metadata and identification handles.') }}</p>
    </div>
</div>

<div class="max-w-4xl">
    <form action="{{ isset($theme) ? route('admin.themes.update', $theme) : route('admin.themes.store') }}" method="POST" class="space-y-8">
        @csrf
        @if(isset($theme)) @method('PUT') @endif

        <div class="glass p-10 rounded-[3rem] border border-white/5 shadow-2xl space-y-8">
            <!-- Basic Information -->
                <div class="space-y-6">
                    <h4 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em] mb-6">{{ __('Fundamental Metadata') }}</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-white">
                        <div class="space-y-2">
                            <label for="name" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">{{ __('Display Name') }}</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $theme->name ?? '') }}" required placeholder="e.g. Luxe Aura"
                                class="w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-white text-sm font-bold focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all placeholder:text-slate-600">
                            @error('name') <p class="text-rose-500 text-[10px] font-black uppercase tracking-tight ml-1 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="theme_key" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">{{ __('Theme Key (Handle)') }}</label>
                            <input type="text" name="theme_key" id="theme_key" value="{{ old('theme_key', $theme->theme_key ?? '') }}" required placeholder="e.g. luxe-aura"
                                class="w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-white text-sm font-bold focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all placeholder:text-slate-600">
                            <p class="text-[10px] text-slate-500 font-medium ml-1 mt-1">{{ __('Used for directory identification. Use lowercase, numbers, and dashes.') }}</p>
                            @error('theme_key') <p class="text-rose-500 text-[10px] font-black uppercase tracking-tight ml-1 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-white">
                        <div class="space-y-2">
                            <label for="preview_image" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">{{ __('Preview Image URL') }}</label>
                            <input type="text" name="preview_image" id="preview_image" value="{{ old('preview_image', $theme->preview_image ?? '') }}" placeholder="https://..."
                                class="w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-white text-sm font-bold focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all placeholder:text-slate-600 font-mono">
                            <p class="text-[10px] text-slate-500 font-medium ml-1 mt-1">{{ __('A visual thumbnail displayed in the theme gallery.') }}</p>
                            @error('preview_image') <p class="text-rose-500 text-[10px] font-black uppercase tracking-tight ml-1 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-3">
                            <label class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">{{ __('Supported Languages') }}</label>
                            <div class="flex flex-wrap gap-4 pt-1">
                                @foreach(['ja' => 'Japanese', 'en' => 'English', 'fr' => 'French'] as $code => $label)
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <div class="relative flex items-center justify-center">
                                            <input type="checkbox" name="languages[]" value="{{ $code }}" 
                                                {{ in_array($code, old('languages', $theme->languages ?? [])) ? 'checked' : '' }}
                                                class="peer appearance-none w-6 h-6 bg-white/5 border border-white/10 rounded-lg checked:bg-indigo-600 checked:border-transparent transition-all">
                                            <svg class="absolute w-4 h-4 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="3" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                        <span class="text-xs font-bold text-slate-400 group-hover:text-white transition-colors uppercase tracking-widest">{{ $code }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('languages') <p class="text-rose-500 text-[10px] font-black uppercase tracking-tight ml-1 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

            <!-- Visual Preview (Optional/Interactive) -->
            @if(isset($theme) && $theme->preview_image)
                <div class="p-6 bg-white/5 rounded-[2rem] border border-white/5">
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block mb-4">{{ __('Current Visual') }}</span>
                    <img src="{{ $theme->preview_image }}" class="w-full h-48 object-cover rounded-2xl shadow-xl">
                </div>
            @endif
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.themes.index') }}" class="px-8 py-4 text-slate-400 hover:text-white text-xs font-black uppercase tracking-widest transition-all">
                {{ __('Cancel') }}
            </a>
            <button type="submit" class="px-10 py-4 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-black rounded-2xl transition-all shadow-lg shadow-indigo-600/20 active:scale-95 uppercase tracking-widest">
                {{ isset($theme) ? __('Update Identity') : __('Save Theme Identity') }}
            </button>
        </div>
    </form>
</div>
@endsection
