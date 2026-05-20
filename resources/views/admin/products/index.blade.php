@extends('admin.layouts.base')

@section('title', __('products'))
@section('page_title', __('Product Catalog'))

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h3 class="text-xl font-bold tracking-tight text-white">{{ __('Inventory & Merchandising') }}</h3>
        <p class="text-sm text-slate-400 mt-1">{{ __('Monitor and manage your store performance and product visibility.') }}</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-2xl transition-all shadow-lg shadow-indigo-600/20 flex items-center gap-2 active:scale-95">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M12 4v16m8-8H4"/></svg>
        {{ __('Add Product') }}
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
        <table class="w-full text-left border-collapse text-white">
            <thead>
                <tr class="border-b border-white/5 bg-white/5">
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Product Identity') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('SKU / Variant') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Financials') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Inventory') }}</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($products as $product)
                    <tr class="hover:bg-white/[0.02] transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-white/5 rounded-2xl overflow-hidden border border-white/5 flex-shrink-0 relative group-hover:border-indigo-500/30 transition-all">
                                    @if($product->images->count() > 0)
                                        <img src="{{ asset('storage/' . $product->images->first()->file_path) }}" class="w-full h-full object-cover grayscale-[0.5] group-hover:grayscale-0 transition-all duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-600">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                    @endif
                                    <div class="absolute inset-x-0 bottom-0 p-1">
                                        @if($product->is_active)
                                            <div class="w-full h-1 bg-emerald-500/50 rounded-full shadow-[0_0_8px_rgba(16,185,129,0.5)]"></div>
                                        @else
                                            <div class="w-full h-1 bg-slate-500/50 rounded-full"></div>
                                        @endif
                                    </div>
                                </div>
                                <div class="max-w-xs">
                                    <p class="text-sm font-bold text-white group-hover:text-indigo-400 transition-colors leading-snug">{{ $product->name }}</p>
                                    <div class="flex flex-wrap gap-1 mt-1.5">
                                        @foreach($product->categories as $category)
                                            <span class="text-[9px] font-black px-2 py-0.5 bg-indigo-500/10 text-indigo-400 rounded-md uppercase tracking-wider border border-indigo-500/10">{{ $category->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-300">{{ $product->defaultVariant?->sku ?? '—' }}</span>
                                <span class="text-[10px] text-slate-500 font-medium uppercase tracking-wider mt-0.5">{{ __('Core Stock Entry') }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-indigo-400 tracking-tighter">¥ {{ number_format($product->defaultVariant?->price ?? 0) }}</span>
                                <span class="text-[10px] text-slate-500 font-medium uppercase tracking-wider mt-0.5">{{ __('Base Retail') }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full {{ ($product->defaultVariant?->stock_quantity ?? 0) > 0 ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]' : 'bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.5)]' }}"></div>
                                <span class="text-xs font-bold text-slate-300">{{ $product->defaultVariant?->stock_quantity ?? 0 }}</span>
                                <span class="text-[10px] text-slate-500 font-medium uppercase tracking-wider ml-1">{{ __('units') }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2 lg:opacity-0 lg:group-hover:opacity-100 transition-all lg:scale-95 lg:group-hover:scale-100">
                                <a href="{{ route('admin.products.edit', $product) }}" class="p-3 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white rounded-xl transition-all" title="{{ __('edit') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this product?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-3 bg-white/5 hover:bg-rose-500/10 text-slate-400 hover:text-rose-500 rounded-xl transition-all" title="{{ __('delete') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-8 py-32 text-center text-slate-500 font-medium">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-20 h-20 bg-white/5 rounded-[2rem] flex items-center justify-center border border-white/5">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </div>
                                <p class="text-slate-400 font-bold tracking-tight text-lg">{{ __('Your warehouse is currently empty.') }}</p>
                                <a href="{{ route('admin.products.create') }}" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-2xl transition-all shadow-lg shadow-indigo-600/20 active:scale-95">
                                    {{ __('Create Your First Product') }}
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
        <div class="p-8 bg-white/5 border-t border-white/5">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
