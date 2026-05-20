@extends('admin.layouts.base')

@section('title', __('Customer Management'))
@section('page_title', __('Customer Management'))

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div class="flex items-center gap-4">
        <div class="p-3 bg-indigo-500/10 rounded-2xl">
            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <div>
            <h3 class="text-xl font-bold tracking-tight">{{ __('Members & Customers') }}</h3>
            <p class="text-sm text-slate-400">{{ __('Search and manage your shop customers') }}</p>
        </div>
    </div>
    
    <a href="{{ route('admin.customers.create') }}" class="flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-2xl transition-all shadow-lg shadow-indigo-600/20">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        {{ __('Add New Customer') }}
    </a>
</div>

<!-- Search Bar -->
<div class="mb-8">
    <form action="{{ route('admin.customers.index') }}" method="GET" class="relative group">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search for name, email or phone...') }}" 
               class="w-full pl-12 pr-6 py-4 bg-white/5 border border-white/10 rounded-3xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-500">
        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-indigo-400 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <button type="submit" class="hidden"></button>
    </form>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400 text-sm font-bold flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
@endif

<div class="glass rounded-3xl overflow-hidden shadow-2xl border border-white/5">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5 border-b border-white/5">
                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">{{ __('Customer') }}</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">{{ __('Contact Info') }}</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">{{ __('Status') }}</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">{{ __('Last Login') }}</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($customers as $customer)
                <tr class="hover:bg-white/5 transition-colors group">
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-xs font-black text-indigo-400 border border-white/5 uppercase">
                                {{ substr($customer->name, 0, 2) }}
                            </div>
                            <div>
                                <p class="font-bold text-white group-hover:text-indigo-400 transition-colors">{{ $customer->name }}</p>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">{{ __('Member Since') }}: {{ $customer->created_at->format('Y/m/d') }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-xs text-slate-300 font-medium">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                {{ $customer->email }}
                            </div>
                            @if($customer->phone)
                            <div class="flex items-center gap-2 text-xs text-slate-500">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                {{ $customer->phone }}
                            </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        @if($customer->status === 'active')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                                {{ __('Active') }}
                            </span>
                        @elseif($customer->status === 'pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-amber-500/10 text-amber-500 border border-amber-500/20">
                                {{ __('Pending') }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-rose-500/10 text-rose-500 border border-rose-500/20">
                                {{ __('Restricted') }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-5 text-sm font-medium text-slate-400">
                        {{ $customer->last_login_at ? $customer->last_login_at->diffForHumans() : __('Never') }}
                    </td>
                    <td class="px-6 py-5 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.customers.edit', $customer) }}" class="p-2 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white rounded-xl transition-all border border-white/5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <svg class="w-12 h-12 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <p class="text-slate-500 font-bold uppercase tracking-widest text-xs">{{ __('No customers found') }}</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($customers->hasPages())
    <div class="px-6 py-4 bg-white/5 border-t border-white/5">
        {{ $customers->links() }}
    </div>
    @endif
</div>
@endsection
