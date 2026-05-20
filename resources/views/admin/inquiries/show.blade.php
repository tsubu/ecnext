@extends('admin.layouts.base')

@section('title', __('Inquiry') . ': ' . $inquiry->name)
@section('page_title', __('Inquiry Detail'))

@section('content')
<div class="mb-8 flex items-center gap-4">
    <a href="{{ route('admin.inquiries.index') }}" class="p-3 bg-white/5 hover:bg-white/10 rounded-2xl text-slate-400 hover:text-white transition-all">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
        <h3 class="text-xl font-bold tracking-tight text-white">{{ __('Message Analysis') }}</h3>
        <p class="text-sm text-slate-400">{{ __('Review customer message and update processing status.') }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- Main Message Card -->
    <div class="lg:col-span-8">
        <div class="glass p-10 rounded-[3rem] border border-white/5 shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 p-8">
                <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center text-slate-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                </div>
            </div>

            <div class="mb-10">
                <h4 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em] mb-4">{{ __('Subject') }}</h4>
                <h2 class="text-3xl font-black text-white tracking-tighter leading-tight">{{ $inquiry->subject }}</h2>
            </div>

            <div class="space-y-8">
                <div>
                    <h4 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-4">{{ __('Message Body') }}</h4>
                    <div class="bg-white/5 p-8 rounded-[2rem] border border-white/5 text-slate-200 leading-relaxed whitespace-pre-wrap">
                        {{ $inquiry->message }}
                    </div>
                </div>

                <div class="flex flex-wrap gap-8 pt-4 border-t border-white/5">
                    <div>
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">{{ __('Sender Name') }}</h4>
                        <p class="text-sm font-bold text-white">{{ $inquiry->name }}</p>
                    </div>
                    <div>
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">{{ __('Email Address') }}</h4>
                        <p class="text-sm font-bold text-indigo-400">{{ $inquiry->email }}</p>
                    </div>
                    <div>
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">{{ __('IP Address') }}</h4>
                        <p class="text-sm font-medium text-slate-400">{{ $inquiry->ip_address ?? 'Unknown' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar / Status Controls -->
    <div class="lg:col-span-4 space-y-8">
        <div class="glass p-8 rounded-[2.5rem] border border-white/5 shadow-2xl">
            <h4 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-6">{{ __('Status Management') }}</h4>
            
            <form action="{{ route('admin.inquiries.update', $inquiry) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <label class="block group cursor-pointer">
                        <input type="radio" name="status" value="pending" class="peer hidden" {{ $inquiry->status === 'pending' ? 'checked' : '' }}>
                        <div class="p-4 bg-white/5 border border-white/10 rounded-2xl transition-all group-hover:bg-white/10 peer-checked:border-amber-500 peer-checked:bg-amber-500/10">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-white">{{ __('Pending') }}</span>
                                <div class="w-4 h-4 rounded-full border-2 border-slate-700 peer-checked:border-amber-500 peer-checked:bg-amber-500 flex items-center justify-center">
                                    <div class="w-1.5 h-1.5 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                </div>
                            </div>
                        </div>
                    </label>

                    <label class="block group cursor-pointer">
                        <input type="radio" name="status" value="replied" class="peer hidden" {{ $inquiry->status === 'replied' ? 'checked' : '' }}>
                        <div class="p-4 bg-white/5 border border-white/10 rounded-2xl transition-all group-hover:bg-white/10 peer-checked:border-indigo-500 peer-checked:bg-indigo-500/10">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-white">{{ __('Replied') }}</span>
                                <div class="w-4 h-4 rounded-full border-2 border-slate-700 peer-checked:border-indigo-500 peer-checked:bg-indigo-500 flex items-center justify-center"></div>
                            </div>
                        </div>
                    </label>

                    <label class="block group cursor-pointer">
                        <input type="radio" name="status" value="closed" class="peer hidden" {{ $inquiry->status === 'closed' ? 'checked' : '' }}>
                        <div class="p-4 bg-white/5 border border-white/10 rounded-2xl transition-all group-hover:bg-white/10 peer-checked:border-slate-500 peer-checked:bg-slate-500/10">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-white">{{ __('Closed') }}</span>
                                <div class="w-4 h-4 rounded-full border-2 border-slate-700 flex items-center justify-center"></div>
                            </div>
                        </div>
                    </label>
                </div>

                <button type="submit" class="w-full mt-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-black uppercase tracking-[0.2em] rounded-2xl transition-all shadow-xl shadow-indigo-600/20 active:scale-95">
                    {{ __('Update Status') }}
                </button>
            </form>
        </div>

        <div class="glass p-8 rounded-[2.5rem] border border-white/5 shadow-2xl">
            <h4 class="text-xs font-black text-rose-500 uppercase tracking-[0.2em] mb-4">{{ __('Danger Zone') }}</h4>
            <p class="text-[10px] text-slate-500 font-bold mb-6 uppercase tracking-widest leading-relaxed">{{ __('Permanent removal from database cannot be undone.') }}</p>
            
            <form action="{{ route('admin.inquiries.destroy', $inquiry) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this inquiry?') }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-4 bg-rose-500/10 hover:bg-rose-500 text-rose-500 hover:text-white font-black uppercase tracking-[0.2em] rounded-2xl transition-all border border-rose-500/20 active:scale-95">
                    {{ __('Delete Permanently') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
