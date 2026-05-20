@extends('admin.layouts.base')

@section('title', __('notices'))
@section('page_title', __('News & Notices'))

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h3 class="text-xl font-bold tracking-tight text-white">{{ __('Manage Storefront News') }}</h3>
        <p class="text-sm text-slate-400 mt-1">{{ __('Publish announcements, updates, and news for your customers.') }}</p>
    </div>
    <a href="{{ route('admin.notices.create') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-2xl transition-all shadow-lg shadow-indigo-600/20 flex items-center gap-2 active:scale-95">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M12 4v16m8-8H4"/></svg>
        {{ __('Add News Entry') }}
    </a>
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
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('News Subject') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Schedule') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Status') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($notices as $notice)
                    <tr class="hover:bg-white/[0.02] transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-rose-500/10 rounded-xl flex items-center justify-center text-rose-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                </div>
                                <div class="max-w-md truncate">
                                    <p class="text-sm font-bold text-white group-hover:text-rose-400 transition-colors truncate">{{ $notice->title }}</p>
                                    <p class="text-[10px] text-slate-500 mt-0.5 font-medium uppercase tracking-wider truncate">{{ Str::limit(strip_tags($notice->content), 60) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-[8px] font-black text-slate-500 uppercase tracking-widest bg-white/5 px-2 py-0.5 rounded">{{ __('From') }}</span>
                                    <span class="text-xs font-bold text-slate-300">{{ $notice->published_at ? $notice->published_at->format('Y-m-d H:i') : '-' }}</span>
                                </div>
                                @if($notice->expired_at)
                                <div class="flex items-center gap-2">
                                    <span class="text-[8px] font-black text-rose-500/50 uppercase tracking-widest bg-rose-500/5 px-2 py-0.5 rounded">{{ __('To') }}</span>
                                    <span class="text-xs font-bold text-slate-400">{{ $notice->expired_at->format('Y-m-d H:i') }}</span>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            @if($notice->is_active)
                                <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 text-[10px] font-black rounded-full uppercase tracking-widest border border-emerald-500/20">
                                    {{ __('Public') }}
                                </span>
                            @else
                                <span class="px-3 py-1 bg-slate-500/10 text-slate-400 text-[10px] font-black rounded-full uppercase tracking-widest border border-white/5">
                                    {{ __('Draft') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.notices.edit', $notice) }}" class="p-2 hover:bg-white/10 text-slate-400 hover:text-white rounded-lg transition-all" title="{{ __('edit') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.notices.destroy', $notice) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this news?') }}')">
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
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="p-4 bg-white/5 rounded-full text-slate-500">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2zM12 11l-3 3m0 0l3 3m-3-3h12"/></svg>
                                </div>
                                <p class="text-slate-400 font-bold tracking-tight">{{ __('No news entries found.') }}</p>
                                <a href="{{ route('admin.notices.create') }}" class="text-indigo-400 font-black text-xs uppercase tracking-widest hover:text-indigo-300 transition-colors">{{ __('Create your first announcement') }}</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($notices->hasPages())
        <div class="px-8 py-6 bg-white/5 border-t border-white/5">
            {{ $notices->links() }}
        </div>
    @endif
</div>
@endsection
