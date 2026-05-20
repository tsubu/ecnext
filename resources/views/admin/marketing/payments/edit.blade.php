@extends('admin.layouts.base')

@section('title', __('Configure Payment Gateway'))
@section('page_title', $provider->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="glass p-12 rounded-[3.5rem] border border-white/5 bg-white/5 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-500/5 blur-[120px] pointer-events-none"></div>
        
        <form action="{{ route('admin.payments.update', $provider->id) }}" method="POST" class="space-y-12">
            @csrf
            @method('PUT')

            <div class="flex items-center justify-between p-8 bg-white/5 rounded-3xl border border-white/5">
                <div>
                    <h4 class="text-sm font-bold text-white">{{ __('Enable this Provider') }}</h4>
                    <p class="text-[10px] text-slate-400 uppercase tracking-widest mt-1">{{ __('When enabled, this payment method will be visible to customers.') }}</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ $provider->is_active ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500 shadow-inner"></div>
                </label>
            </div>

            <div class="space-y-8 pt-8 border-t border-white/5">
                <h3 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] ml-1">{{ __('Module Configuration') }}</h3>
                
                <div class="grid grid-cols-1 gap-8">
                    @foreach($schema as $field)
                        <div class="space-y-4">
                            <label class="text-sm font-bold text-slate-300">{{ $field['label'] }}</label>
                            @if($field['type'] === 'text')
                                <input type="text" name="config[{{ $field['key'] }}]" value="{{ $provider->config[$field['key'] ] ?? '' }}" class="w-full px-6 py-5 bg-white/10 border border-white/10 rounded-2xl text-white font-bold focus:ring-2 focus:ring-emerald-500/50 outline-none transition-all" placeholder="{{ $field['placeholder'] ?? '' }}">
                            @elseif($field['type'] === 'password' || ($field['is_secret'] ?? false) === true)
                                <input type="password" name="config[{{ $field['key'] }}]" value="{{ $provider->config[$field['key'] ] ?? '' }}" class="w-full px-6 py-5 bg-white/10 border border-white/10 rounded-2xl text-white font-bold focus:ring-2 focus:ring-emerald-500/50 outline-none transition-all" placeholder="••••••••">
                            @elseif($field['type'] === 'select')
                                <select name="config[{{ $field['key'] }}]" class="w-full px-6 py-5 bg-white/10 border border-white/10 rounded-2xl text-white font-bold focus:ring-2 focus:ring-emerald-500/50 outline-none transition-all">
                                    @foreach($field['options'] as $value => $label)
                                        <option value="{{ $value }}" {{ ($provider->config[$field['key']] ?? '') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            @endif
                            @if(isset($field['help']))
                                <p class="text-[10px] text-slate-500 font-bold tracking-wider">{{ $field['help'] }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-12 flex justify-end gap-6">
                <a href="{{ route('admin.payments.index') }}" class="px-8 py-4 text-slate-300 font-bold text-xs uppercase tracking-widest hover:text-white transition-all">{{ __('Cancel') }}</a>
                <button type="submit" class="px-12 py-4 bg-emerald-600 hover:bg-emerald-500 text-white font-black rounded-2xl transition-all shadow-lg shadow-emerald-600/20 active:scale-95 flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    {{ __('Save Plugin Configuration') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
