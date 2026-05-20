@extends('admin.layouts.base')

@section('title', __('Payment Gateways'))
@section('page_title', __('Modular Payment Plugins'))

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach($providers as $provider)
            <div class="group relative glass p-8 rounded-[2.5rem] border border-white/5 bg-white/5 transition-all hover:bg-white/10 overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 blur-[60px] pointer-events-none"></div>
                
                <div class="flex flex-col h-full">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center border border-white/5">
                            <span class="text-xl font-black text-emerald-400 capitalize">{{ substr($provider->provider_key, 0, 1) }}</span>
                        </div>
                        <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $provider->is_active ? 'bg-emerald-500/20 text-emerald-400' : 'bg-slate-700/50 text-slate-400' }}">
                            {{ $provider->is_active ? __('Active') : __('Inactive') }}
                        </span>
                    </div>

                    <h3 class="text-xl font-black text-white mb-2 tracking-tight">{{ $provider->name }}</h3>
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-8">{{ __('Plugin ID') }}: {{ $provider->provider_key }}</p>

                    <div class="mt-auto pt-6 border-t border-white/5 flex items-center justify-between">
                        <a href="{{ route('admin.payments.edit', $provider->id) }}" class="text-xs font-black text-emerald-400 hover:text-white transition-all uppercase tracking-widest flex items-center gap-2">
                            {{ __('Configure Module') }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
