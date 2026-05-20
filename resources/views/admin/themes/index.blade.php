@extends('admin.layouts.base')

@section('title', __('Storefront Themes'))
@section('page_title', __('Storefront Themes'))

@section('content')
<div class="mb-8 flex justify-between items-end text-white">
    <div>
        <h3 class="text-xl font-black tracking-tight">{{ __('Identity Gallery') }}</h3>
        <p class="text-sm text-slate-400 mt-1">{{ __('Manage your shop\'s visual atmosphere and active templates.') }}</p>
    </div>
    <a href="{{ route('admin.themes.create') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-black rounded-2xl transition-all shadow-lg shadow-indigo-600/20 flex items-center gap-2 active:scale-95 uppercase tracking-widest">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M12 4v16m8-8H4"/></svg>
        {{ __('Add Theme') }}
    </a>
</div>

@if(session('success'))
    <div class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400 text-sm font-bold flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-8 p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl text-rose-400 text-sm font-bold flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('error') }}
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @forelse($themes as $theme)
        <div class="glass group rounded-[2.5rem] overflow-hidden border border-white/5 shadow-2xl transition-all hover:border-indigo-500/30 flex flex-col">
            <!-- Preview Area -->
            <div class="aspect-[16/10] bg-slate-900 relative overflow-hidden">
                @if($theme->preview_image)
                    <img src="{{ $theme->preview_image }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-500/10 to-purple-500/10">
                        <svg class="w-16 h-16 text-white/10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                @endif
                
                @if($theme->is_active)
                    <div class="absolute top-6 left-6">
                        <span class="px-4 py-1.5 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-full shadow-lg shadow-emerald-500/40">
                            {{ __('Active') }}
                        </span>
                    </div>
                @endif

                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent opacity-60"></div>
            </div>

            <!-- Content Area -->
            <div class="p-8 flex-grow space-y-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="text-lg font-black text-white group-hover:text-indigo-400 transition-colors tracking-tight">{{ $theme->name }}</h4>
                        <div class="flex items-center gap-3 mt-1.5 caps">
                            <code class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $theme->theme_key }}</code>
                            @if($theme->languages)
                                <div class="flex gap-1.5 ml-2 border-l border-white/10 pl-3">
                                    @foreach($theme->languages as $lang)
                                        <span class="text-[9px] font-black px-1.5 py-0.5 rounded bg-white/5 text-slate-400 uppercase tracking-tighter">{{ $lang }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex items-center gap-2">
                    @if(!$theme->is_active)
                        <form action="{{ route('admin.themes.activate', $theme) }}" method="POST" class="flex-grow">
                            @csrf
                            <button type="submit" class="w-full py-3 bg-white/5 hover:bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl border border-white/5 transition-all active:scale-95">
                                {{ __('Activate') }}
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('admin.themes.edit', $theme) }}" class="p-3 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white rounded-2xl transition-all border border-white/5 active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>

                    @if(!$theme->is_active)
                        <form action="{{ route('admin.themes.destroy', $theme) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to remove this theme record?') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-3 bg-white/5 hover:bg-rose-500/10 text-slate-400 hover:text-rose-500 rounded-2xl transition-all border border-white/5 active:scale-95">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full py-32 glass rounded-[3rem] border border-white/5 text-center">
            <div class="flex flex-col items-center gap-6">
                <div class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center text-slate-600 border border-white/5">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <h5 class="text-xl font-black text-white leading-tight transition-colors tracking-tight">{{ __('Your theme gallery is empty.') }}</h5>
                    <p class="text-slate-500 mt-2 font-medium max-w-sm mx-auto leading-relaxed">{{ __('Add your first storefront theme to begin customizing your shop\'s identity.') }}</p>
                </div>
                <a href="{{ route('admin.themes.create') }}" class="text-indigo-400 font-black text-xs uppercase tracking-[0.2em] hover:text-indigo-300 transition-colors">{{ __('Register First Theme') }}</a>
            </div>
        </div>
    @endforelse
</div>
@endsection
