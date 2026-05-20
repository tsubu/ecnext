@extends('admin.layouts.base')

@section('title', __('orders'))
@section('page_title', __('Order Management'))

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h3 class="text-xl font-bold tracking-tight text-white">{{ __('Fulfillment Workflow') }}</h3>
        <p class="text-sm text-slate-400 mt-1">{{ __('Process orders, manage shipments, and ensure customer satisfaction.') }}</p>
    </div>
    <div class="flex gap-3">
        <button class="px-6 py-3 bg-white/5 hover:bg-white/10 text-white text-sm font-bold rounded-2xl transition-all border border-white/5 flex items-center gap-2 active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            {{ __('Export CSV') }}
        </button>
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
        <table class="w-full text-left border-collapse text-white">
            <thead>
                <tr class="border-b border-white/5 bg-white/5">
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Identity') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Purchaser') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-center">{{ __('Status') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Economics') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($orders as $order)
                    <tr class="hover:bg-white/[0.02] transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-white group-hover:text-indigo-400 transition-colors">#{{ $order->order_number }}</span>
                                <span class="text-[10px] text-slate-500 font-medium tracking-wider mt-0.5">{{ $order->created_at->format('Y-m-d H:i') }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-white/5 rounded-xl flex items-center justify-center text-xs font-black text-slate-400 border border-white/5">
                                    {{ Str::upper(Str::substr($order->user?->name ?? 'Guest', 0, 2)) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold">{{ $order->user?->name ?? __('Guest Customer') }}</span>
                                    <span class="text-[10px] text-slate-500 font-medium">{{ $order->user?->email ?? '—' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center justify-center">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                        'processing' => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
                                        'shipped' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                        'delivered' => 'bg-emerald-500/20 text-emerald-300 border-emerald-300/20',
                                        'completed' => 'bg-emerald-600/20 text-emerald-200 border-emerald-200/20',
                                        'cancelled' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                    ];
                                    $colorClass = $statusColors[$order->status] ?? 'bg-slate-500/10 text-slate-400 border-white/5';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $colorClass }}">
                                    {{ __($order->status) }}
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-indigo-400 tracking-tighter">¥ {{ number_format($order->total_price) }}</span>
                                <span class="text-[10px] text-slate-500 font-medium uppercase tracking-wider mt-0.5">{{ $order->paymentMethod?->name ?? __('Unknown') }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2 lg:opacity-0 lg:group-hover:opacity-100 transition-all lg:scale-95 lg:group-hover:scale-100">
                                <a href="{{ route('admin.orders.show', $order) }}" class="px-4 py-2 bg-white/5 hover:bg-white/10 text-xs font-black uppercase tracking-widest text-slate-300 hover:text-white rounded-xl border border-white/5 transition-all">
                                    {{ __('Details') }}
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-8 py-32 text-center text-slate-500 font-medium">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-20 h-20 bg-white/5 rounded-[2rem] flex items-center justify-center border border-white/5">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <p class="text-slate-400 font-bold tracking-tight text-lg">{{ __('Your order book is currently silent.') }}</p>
                                <p class="text-sm text-slate-500 max-w-sm mx-auto leading-relaxed">{{ __('New orders from your storefront will appear here once customers start checkout.') }}</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
        <div class="p-8 bg-white/5 border-t border-white/5">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
