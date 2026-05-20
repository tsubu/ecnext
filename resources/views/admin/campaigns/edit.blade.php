@extends('admin.layouts.base')

@section('title', __('Edit Campaign'))
@section('page_title', __('Optimize Promotion'))

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-8 flex justify-between items-center">
        <a href="{{ route('admin.campaigns.index') }}" class="text-xs font-bold text-slate-500 uppercase tracking-widest hover:text-white transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M15 19l-7-7 7-7"/></svg>
            {{ __('Back to Campaigns') }}
        </a>
        
        <form action="{{ route('admin.campaigns.destroy', $campaign) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this campaign event?') }}')">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-[10px] font-black text-rose-500/50 hover:text-rose-500 uppercase tracking-[0.2em] transition-colors">
                {{ __('Terminate Event') }}
            </button>
        </form>
    </div>

    <form action="{{ route('admin.campaigns.update', $campaign) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8 text-glow-indigo">
                <div class="glass p-10 rounded-[2.5rem] border border-white/5 space-y-10 shadow-2xl">
                    <!-- Basic Info -->
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">{{ __('Campaign Title') }}</label>
                        <input type="text" name="name" value="{{ old('name', $campaign->name) }}" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-black text-xl tracking-tight focus:ring-2 focus:ring-rose-500/50 outline-none transition-all">
                        @error('name') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">{{ __('Event Description') }}</label>
                        <textarea name="description" rows="4" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-medium focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all">{{ old('description', $campaign->description) }}</textarea>
                    </div>

                    <!-- Discount Logic -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-8 bg-rose-500/5 rounded-3xl border border-rose-500/10 shadow-inner">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-rose-400 uppercase tracking-[0.2em] ml-1">{{ __('Discount Application') }}</label>
                            <select name="discount_type" required class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-white text-xs font-bold outline-none focus:border-rose-500">
                                <option value="percentage" {{ old('discount_type', $campaign->discount_type) == 'percentage' ? 'selected' : '' }}>{{ __('Percentage Reduction (%)') }}</option>
                                <option value="fixed" {{ old('discount_type', $campaign->discount_type) == 'fixed' ? 'selected' : '' }}>{{ __('Fixed Amount Off (¥)') }}</option>
                            </select>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-rose-400 uppercase tracking-[0.2em] ml-1">{{ __('Reduction Value') }}</label>
                            <input type="number" name="discount_value" value="{{ old('discount_value', $campaign->discount_value) }}" required class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-rose-500 text-lg font-black outline-none focus:border-rose-500">
                        </div>
                    </div>

                    <!-- Product Selection -->
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">{{ __('Target Products') }}</label>
                        <div class="max-h-64 overflow-y-auto pr-4 space-y-2 custom-scrollbar">
                            @foreach($products as $product)
                                <label class="flex items-center gap-4 p-4 bg-white/5 border border-white/5 rounded-2xl hover:bg-white/10 cursor-pointer group transition-all">
                                    <input type="checkbox" name="products[]" value="{{ $product->id }}" class="peer hidden" {{ in_array($product->id, $selectedProducts) ? 'checked' : '' }}>
                                    <div class="w-5 h-5 bg-white/10 border border-white/10 rounded-lg group-hover:bg-white/20 peer-checked:bg-rose-600 peer-checked:border-rose-500 transition-all flex items-center justify-center text-glow-rose">
                                        <svg class="w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="4" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <span class="text-sm font-bold text-slate-300 group-hover:text-white transition-colors">{{ $product->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-8">
                <div class="glass p-8 rounded-[2.5rem] border border-white/5 space-y-8 shadow-2xl">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">{{ __('Schedule') }}</label>
                        <div class="space-y-4">
                            <div>
                                <label class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-2 block">{{ __('Starts At') }}</label>
                                <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $campaign->starts_at?->format('Y-m-d\TH:i')) }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-xs outline-none focus:ring-1 focus:ring-rose-500">
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-2 block">{{ __('Ends At') }}</label>
                                <input type="datetime-local" name="expires_at" value="{{ old('expires_at', $campaign->expires_at?->format('Y-m-d\TH:i')) }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-xs outline-none focus:ring-1 focus:ring-rose-500">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 px-1">
                        <div class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $campaign->is_active) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-600 transition-all shadow-[0_0_10px_rgba(225,29,72,0.1)]"></div>
                        </div>
                        <span class="text-xs font-bold text-slate-400 select-none">{{ __('Activate Campaign') }}</span>
                    </div>
                </div>

                <div class="glass p-8 rounded-[2.5rem] border border-white/5 space-y-4 shadow-xl">
                    <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest">{{ __('Campaign Stats') }}</p>
                    <div class="space-y-4">
                        <div class="flex justify-between items-end">
                            <span class="text-[10px] text-slate-600 font-bold uppercase tracking-widest">{{ __('Enrolled Products') }}</span>
                            <span class="text-2xl font-black text-white leading-none">{{ count($selectedProducts) }}</span>
                        </div>
                        <div class="h-1 bg-white/5 rounded-full overflow-hidden">
                            <div class="h-full bg-rose-500" style="width: {{ count($products) > 0 ? (count($selectedProducts) / count($products)) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-5 bg-rose-600 hover:bg-rose-500 text-white text-sm font-black uppercase tracking-[0.2em] rounded-2xl transition-all shadow-xl shadow-rose-600/20 active:scale-95">
                    {{ __('Update Campaign') }}
                </button>
            </div>
        </div>
    </form>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.05); border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(225, 29, 72, 0.5); }
</style>
@endsection
