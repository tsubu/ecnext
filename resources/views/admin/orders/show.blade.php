@extends('admin.layouts.base')

@section('title', __('order') . ' #' . $order->order_number)
@section('page_title', __('Order Fulfillment'))

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.orders.index') }}" class="p-3 bg-white/5 hover:bg-white/10 rounded-2xl text-slate-400 hover:text-white transition-all shadow-lg active:scale-95">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h3 class="text-xl font-black tracking-tight text-white">{{ __('Order') }} #{{ $order->order_number }}</h3>
            <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-0.5">{{ __('Received at') }} {{ $order->created_at->format('Y-m-d H:i') }}</p>
        </div>
    </div>
    <div class="flex gap-3">
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
        <span class="px-5 py-2.5 rounded-2xl text-xs font-black uppercase tracking-[0.2em] border {{ $colorClass }}">
            {{ __($order->status) }}
        </span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 text-white">
    <!-- Main Logistics -->
    <div class="lg:col-span-8 space-y-8">
        <!-- Purchased Items -->
        <div class="glass p-8 rounded-[3rem] border border-white/5 shadow-2xl space-y-8">
            <h4 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em]">{{ __('Inventory Allocation') }}</h4>
            
            <div class="space-y-4">
                @foreach($order->items as $item)
                    <div class="flex items-center gap-6 p-4 bg-white/5 rounded-3xl border border-white/5 group hover:bg-white/[0.08] transition-all">
                        <div class="w-16 h-16 bg-white/5 rounded-2xl overflow-hidden border border-white/5 flex-shrink-0">
                            @if($item->variant?->product?->images->count() > 0)
                                <img src="{{ asset('storage/' . $item->variant->product->images->first()->file_path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-600">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow">
                            <p class="text-sm font-bold text-white leading-tight">{{ $item->name }}</p>
                            <span class="text-[10px] text-slate-500 font-medium tracking-wider uppercase">{{ $item->variant?->sku ?? 'NO-SKU' }}</span>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-black text-indigo-400 tracking-tighter">¥ {{ number_format($item->price) }} <span class="text-slate-500 font-bold ml-1 text-xs">x {{ $item->quantity }}</span></p>
                            <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wider mt-0.5">{{ __('Subtotal') }}: ¥ {{ number_format($item->total_price) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Financial Summary -->
            <div class="pt-8 border-t border-white/5">
                <div class="max-w-xs ml-auto space-y-3">
                    <div class="flex justify-between text-xs font-bold text-slate-400">
                        <span>{{ __('Item Total') }}</span>
                        <span>¥ {{ number_format($order->item_total) }}</span>
                    </div>
                    <div class="flex justify-between text-xs font-bold text-slate-400">
                        <span>{{ __('Shipping Fee') }}</span>
                        <span>¥ {{ number_format($order->shipping_fee) }}</span>
                    </div>
                    <div class="flex justify-between text-xs font-bold text-slate-400">
                        <span>{{ __('Tax') }}</span>
                        <span>¥ {{ number_format($order->tax) }}</span>
                    </div>
                    <div class="flex justify-between pt-4 border-t border-white/5">
                        <span class="text-sm font-black uppercase tracking-widest">{{ __('Final Total') }}</span>
                        <span class="text-xl font-black text-indigo-400 tracking-tighter">¥ {{ number_format($order->total_price) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Segments -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="glass p-8 rounded-[3rem] border border-white/5 shadow-2xl space-y-6">
                <h4 class="text-xs font-black text-emerald-400 uppercase tracking-[0.2em]">{{ __('Identity & Contact') }}</h4>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center text-lg font-black text-indigo-400 border border-white/5 leading-none">
                        {{ Str::upper(Str::substr($order->user?->name ?? 'G', 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-white">{{ $order->user?->name ?? __('Guest') }}</p>
                        <p class="text-xs text-slate-500 font-medium tracking-tight">{{ $order->user?->email ?? __('Guest Customer') }}</p>
                    </div>
                </div>
                <div class="p-4 bg-white/5 rounded-2xl border border-white/5 space-y-4">
                    <div>
                        <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">{{ __('Customer Notes') }}</span>
                        <p class="text-xs text-slate-300 font-medium leading-relaxed mt-1">{{ $order->notes ?? __('No notes provided for this transaction.') }}</p>
                    </div>
                </div>
            </div>

            <div class="glass p-8 rounded-[3rem] border border-white/5 shadow-2xl space-y-6">
                <h4 class="text-xs font-black text-amber-400 uppercase tracking-[0.2em]">{{ __('Shipping Destination') }}</h4>
                @php $shipAddr = $order->addresses->where('type', 'shipping')->first(); @endphp
                @if($shipAddr)
                    <div class="space-y-1">
                        <p class="text-sm font-bold text-white group-hover:text-indigo-400 transition-colors">{{ $shipAddr->first_name }} {{ $shipAddr->last_name }}</p>
                        <p class="text-xs text-slate-400 leading-relaxed font-medium">
                            〒{{ $shipAddr->postal_code }}<br>
                            {{ $shipAddr->prefecture }} {{ $shipAddr->city }}<br>
                            {{ $shipAddr->address1 }} {{ $shipAddr->address2 }}
                        </p>
                        <p class="text-xs text-indigo-400 font-bold mt-2">TEL: {{ $shipAddr->phone }}</p>
                    </div>
                @else
                    <p class="text-xs text-slate-500 font-bold italic">{{ __('Shipping address not captured.') }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Fulfillment Controls -->
    <div class="lg:col-span-4 space-y-8">
        <div class="glass p-8 rounded-[3rem] shadow-2xl border border-white/5 space-y-8 sticky top-24">
            <h4 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em]">{{ __('Command Center') }}</h4>
            
            <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-2">
                    <label for="status" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">{{ __('Order Stage') }}</label>
                    <select name="status" id="status" 
                        class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white text-sm font-bold focus:ring-2 focus:ring-indigo-500/50 outline-none appearance-none cursor-pointer">
                        <option value="pending" class="bg-slate-900 text-amber-500" {{ $order->status == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                        <option value="processing" class="bg-slate-900 text-indigo-400" {{ $order->status == 'processing' ? 'selected' : '' }}>{{ __('Processing') }}</option>
                        <option value="shipped" class="bg-slate-900 text-emerald-400" {{ $order->status == 'shipped' ? 'selected' : '' }}>{{ __('Shipped') }}</option>
                        <option value="delivered" class="bg-slate-900 text-emerald-300" {{ $order->status == 'delivered' ? 'selected' : '' }}>{{ __('Delivered') }}</option>
                        <option value="completed" class="bg-slate-900 text-emerald-200" {{ $order->status == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                        <option value="cancelled" class="bg-slate-900 text-rose-400" {{ $order->status == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="payment_status" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">{{ __('Capital Clearance') }}</label>
                    <select name="payment_status" id="payment_status" 
                        class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white text-sm font-bold focus:ring-2 focus:ring-indigo-500/50 outline-none appearance-none cursor-pointer">
                        <option value="pending" class="bg-slate-900" {{ ($order->payment_status ?? 'pending') == 'pending' ? 'selected' : '' }}>{{ __('Payment Pending') }}</option>
                        <option value="paid" class="bg-slate-900 text-emerald-400" {{ ($order->payment_status ?? 'pending') == 'paid' ? 'selected' : '' }}>{{ __('Fully Paid') }}</option>
                        <option value="failed" class="bg-slate-900 text-rose-500" {{ ($order->payment_status ?? 'pending') == 'failed' ? 'selected' : '' }}>{{ __('Failed') }}</option>
                        <option value="refunded" class="bg-slate-900 text-indigo-400" {{ ($order->payment_status ?? 'pending') == 'refunded' ? 'selected' : '' }}>{{ __('Refunded') }}</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-4 mt-4 bg-indigo-600 hover:bg-indigo-500 text-white font-black rounded-[1.5rem] transition-all shadow-lg shadow-indigo-600/20 active:scale-95 text-xs uppercase tracking-[0.2em]">
                    {{ __('Apply Evolution') }}
                </button>
            </form>

            <div class="pt-8 border-t border-white/5 space-y-4">
                <h5 class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ __('Workflow Records') }}</h5>
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-xs font-bold text-slate-400">
                        <span>{{ __('Method') }}</span>
                        <span class="text-white">{{ $order->paymentMethod?->name ?? '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs font-bold text-slate-400">
                        <span>{{ __('Shipping') }}</span>
                        <span class="text-white">{{ $order->shippingMethod?->name ?? '—' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
