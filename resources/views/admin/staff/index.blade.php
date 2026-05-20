@extends('admin.layouts.base')

@section('title', __('Staff Management'))
@section('page_title', __('Staff Management'))

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div class="flex items-center gap-4">
        <div class="p-3 bg-indigo-500/10 rounded-2xl">
            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </div>
        <div>
            <h3 class="text-xl font-bold tracking-tight">{{ __('Administrators') }}</h3>
            <p class="text-sm text-slate-400">{{ __('Manage system access and roles') }}</p>
        </div>
    </div>
    
    <a href="{{ route('admin.staff.create') }}" class="flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-2xl transition-all shadow-lg shadow-indigo-600/20">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        {{ __('Add New Admin') }}
    </a>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400 text-sm font-bold flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl text-rose-400 text-sm font-bold flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('error') }}
    </div>
@endif

<div class="glass rounded-3xl overflow-hidden shadow-2xl border border-white/5">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-white/5 border-b border-white/5">
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Name') }}</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Email') }}</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Role') }}</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Created At') }}</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($staff as $member)
                <tr class="hover:bg-white/5 transition-colors group">
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-sm font-black text-indigo-400 border border-white/5 uppercase">
                                {{ substr($member->name, 0, 2) }}
                            </div>
                            <span class="font-bold text-white group-hover:text-indigo-400 transition-colors">{{ $member->name }}</span>
                            @if(auth('admin')->id() === $member->id)
                                <span class="px-2 py-0.5 bg-indigo-500/10 text-indigo-400 text-[10px] font-black rounded-lg uppercase tracking-tighter">{{ __('You') }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-5 text-sm text-slate-300 font-medium font-mono lowercase tracking-tight">{{ $member->email }}</td>
                    <td class="px-6 py-5">
                        <span class="px-3 py-1 bg-white/5 border border-white/10 rounded-full text-[10px] font-black uppercase tracking-widest text-slate-400">
                            {{ __($member->role->display_name ?? $member->role->name) }}
                        </span>
                    </td>
                    <td class="px-6 py-5 text-sm text-slate-400 font-medium">
                        {{ $member->created_at->format('Y/m/d H:i') }}
                    </td>
                    <td class="px-6 py-5 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.staff.edit', $member) }}" class="p-2 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white rounded-xl transition-all border border-white/5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            @if(auth('admin')->id() !== $member->id)
                            <form action="{{ route('admin.staff.destroy', $member) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this administrator?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-white/5 hover:bg-rose-500/20 text-slate-400 hover:text-rose-500 rounded-xl transition-all border border-white/5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if($staff->hasPages())
    <div class="px-6 py-4 bg-white/5 border-t border-white/5">
        {{ $staff->links() }}
    </div>
    @endif
</div>
@endsection
