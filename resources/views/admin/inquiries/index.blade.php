@extends('admin.layouts.base')

@section('title', __('Inquiries'))
@section('page_title', __('Customer Support'))

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h3 class="text-xl font-bold tracking-tight text-white">{{ __('Customer Inquiries') }}</h3>
        <p class="text-sm text-slate-400 mt-1">{{ __('Monitor and manage messages from your users.') }}</p>
    </div>
</div>

@if(session('success'))
    <div class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400 text-sm font-bold flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
@endif

<div class="glass rounded-[2rem] overflow-hidden border border-white/5 shadow-2xl">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-white/5 bg-white/5">
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Customer') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Subject') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Date') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Status') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($inquiries as $inquiry)
                    <tr class="hover:bg-white/[0.02] transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-indigo-500/10 rounded-xl flex items-center justify-center text-indigo-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div class="max-w-md truncate">
                                    <p class="text-sm font-bold text-white transition-colors truncate">{{ $inquiry->name }}</p>
                                    <p class="text-[10px] text-slate-500 mt-0.5 font-medium uppercase tracking-wider truncate">{{ $inquiry->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <p class="text-xs font-bold text-slate-300">{{ $inquiry->subject }}</p>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-400">{{ $inquiry->created_at->format('Y-m-d') }}</span>
                                <span class="text-[9px] text-slate-600 font-medium uppercase tracking-wider">{{ $inquiry->created_at->format('H:i') }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            @if($inquiry->status === 'pending')
                                <span class="px-3 py-1 bg-amber-500/10 text-amber-400 text-[10px] font-black rounded-full uppercase tracking-widest border border-amber-500/20">
                                    {{ __('Pending') }}
                                </span>
                            @elseif($inquiry->status === 'replied')
                                <span class="px-3 py-1 bg-indigo-500/10 text-indigo-400 text-[10px] font-black rounded-full uppercase tracking-widest border border-indigo-500/20">
                                    {{ __('Replied') }}
                                </span>
                            @else
                                <span class="px-3 py-1 bg-slate-500/10 text-slate-400 text-[10px] font-black rounded-full uppercase tracking-widest border border-white/5">
                                    {{ __('Closed') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="p-2 hover:bg-white/10 text-slate-400 hover:text-white rounded-lg transition-all" title="{{ __('view') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <form action="{{ route('admin.inquiries.destroy', $inquiry) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 hover:bg-rose-500/10 text-slate-400 hover:text-rose-500 rounded-lg transition-all" title="{{ __('delete') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="p-4 bg-white/5 rounded-full text-slate-500">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                </div>
                                <p class="text-slate-400 font-bold tracking-tight">{{ __('No inquiries found.') }}</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($inquiries->hasPages())
        <div class="px-8 py-6 bg-white/5 border-t border-white/5">
            {{ $inquiries->links() }}
        </div>
    @endif
</div>
@endsection
