@extends('admin.layouts.base')

@section('title', __('edit') . ': ' . $product->name)
@section('page_title', __('Edit Product'))

@section('content')
<div class="mb-8 flex items-center gap-4">
    <a href="{{ route('admin.products.index') }}" class="p-3 bg-white/5 hover:bg-white/10 rounded-2xl text-slate-400 hover:text-white transition-all shadow-lg active:scale-95">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
        <h3 class="text-xl font-bold tracking-tight text-white">{{ __('Modify Registry') }}</h3>
        <p class="text-sm text-slate-400">{{ __('Update product attributes, synchronize inventory, and manage visual assets.') }}</p>
    </div>
</div>

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" x-data="{ tab: 'general' }">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 text-white">
        <!-- Sidebar Navigation (Tabs) -->
        <div class="lg:col-span-3 space-y-4">
            <div class="glass p-4 rounded-[2rem] border border-white/5 sticky top-24">
                <nav class="flex flex-col gap-2">
                    <button type="button" @click="tab = 'general'" :class="tab === 'general' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-400 hover:bg-white/5'" 
                        class="w-full flex items-center gap-3 px-5 py-4 rounded-2xl text-sm font-bold transition-all text-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        {{ __('General Info') }}
                    </button>
                    <button type="button" @click="tab = 'inventory'" :class="tab === 'inventory' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-400 hover:bg-white/5'"
                        class="w-full flex items-center gap-3 px-5 py-4 rounded-2xl text-sm font-bold transition-all text-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ __('Inventory & Price') }}
                    </button>
                    <button type="button" @click="tab = 'organization'" :class="tab === 'organization' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-400 hover:bg-white/5'"
                        class="w-full flex items-center gap-3 px-5 py-4 rounded-2xl text-sm font-bold transition-all text-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        {{ __('Organization') }}
                    </button>
                    <button type="button" @click="tab = 'media'" :class="tab === 'media' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-400 hover:bg-white/5'"
                        class="w-full flex items-center gap-3 px-5 py-4 rounded-2xl text-sm font-bold transition-all text-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ __('Media Assets') }}
                    </button>
                    <button type="button" @click="tab = 'layout'" :class="tab === 'layout' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-400 hover:bg-white/5'"
                        class="w-full flex items-center gap-3 px-5 py-4 rounded-2xl text-sm font-bold transition-all text-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                        {{ __('Page Layout') }}
                    </button>
                    <button type="button" @click="tab = 'seo'" :class="tab === 'seo' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-400 hover:bg-white/5'"
                        class="w-full flex items-center gap-3 px-5 py-4 rounded-2xl text-sm font-bold transition-all text-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        {{ __('SEO Settings') }}
                    </button>
                </nav>

                <div class="mt-8 pt-8 border-t border-white/5 px-2">
                    <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-400 text-white font-bold rounded-2xl transition-all shadow-lg shadow-indigo-600/20 active:scale-95 flex items-center justify-center gap-2 text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        {{ __('Update Registry') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="lg:col-span-9">
            <!-- General Info Tab -->
            <div x-show="tab === 'general'" x-cloak class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="glass p-8 rounded-[3rem] border border-white/5 shadow-2xl space-y-8">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em]">{{ __('Identity Definition') }}</h4>
                        <div class="flex items-center gap-3 bg-white/5 px-4 py-2 rounded-xl border border-white/5">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">{{ __('Live on Shop') }}</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ $product->is_active ? 'checked' : '' }}>
                                <div class="w-10 h-5 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="name" class="text-sm font-bold text-slate-300 ml-1">{{ __('Commercial Name') }}</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                                class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label for="slug" class="text-sm font-bold text-slate-300 ml-1">{{ __('URL Permalink') }}</label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $product->slug) }}" required
                                class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-indigo-400 font-bold focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all font-mono">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="short_description" class="text-sm font-bold text-slate-300 ml-1">{{ __('Teaser / Short Summary') }}</label>
                        <input type="text" name="short_description" id="short_description" value="{{ old('short_description', $product->short_description) }}"
                            class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-slate-300 font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all">
                    </div>

                    <div class="space-y-2">
                        <label for="description" class="text-sm font-bold text-slate-300 ml-1">{{ __('Detailed Product Story') }}</label>
                        <textarea name="description" id="description" rows="10" required
                            class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-[2rem] text-slate-300 font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Inventory Tab -->
            <div x-show="tab === 'inventory'" x-cloak class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="glass p-8 rounded-[3rem] border border-white/5 shadow-2xl space-y-8">
                    <h4 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em]">{{ __('Economics & Availability') }}</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label for="sku" class="text-sm font-bold text-slate-300 ml-1">{{ __('SKU Identifier') }}</label>
                            <input type="text" name="sku" id="sku" value="{{ old('sku', $product->defaultVariant?->sku) }}" required
                                class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-mono focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label for="price" class="text-sm font-bold text-slate-300 ml-1">{{ __('Unit Price (JPY)') }}</label>
                            <div class="relative">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-500 font-bold">¥</span>
                                <input type="number" name="price" id="price" value="{{ old('price', $product->defaultVariant?->price) }}" required
                                    class="w-full pl-10 pr-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-black text-lg focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all tracking-tighter">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label for="stock_quantity" class="text-sm font-bold text-slate-300 ml-1">{{ __('Warehouse Stock') }}</label>
                            <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', $product->defaultVariant?->stock_quantity ?? 0) }}" min="0" required
                                class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-bold focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all">
                        </div>
                    </div>

                    <!-- Taxation Policy -->
                    <div class="pt-8 border-t border-white/5 space-y-6">
                        <div class="flex items-center justify-between">
                            <h5 class="text-sm font-bold text-amber-300">{{ __('Taxation Policy') }}</h5>
                            <span class="px-3 py-1 bg-amber-500/10 text-amber-400 text-[10px] font-black rounded-full uppercase tracking-widest">{{ __('Individual Governance') }}</span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-2">
                                <label for="tax_rule_id" class="text-sm font-bold text-slate-300 ml-1">{{ __('Period-based Rule') }}</label>
                                <select name="tax_rule_id" id="tax_rule_id" 
                                    class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 outline-none appearance-none cursor-pointer">
                                    <option value="">{{ __('None (Use Global Default)') }}</option>
                                    @foreach($taxRules as $rule)
                                        <option value="{{ $rule->id }}" {{ old('tax_rule_id', $product->tax_rule_id) == $rule->id ? 'selected' : '' }}>
                                            {{ $rule->name }} ({{ $rule->rate }}%)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label for="individual_tax_rate" class="text-sm font-bold text-slate-300 ml-1">{{ __('Custom Fixed Rate (%)') }}</label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="individual_tax_rate" id="individual_tax_rate" value="{{ old('individual_tax_rate', $product->individual_tax_rate) }}" placeholder="{{ __('Override') }}"
                                        class="w-full pl-5 pr-12 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all">
                                    <span class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-500 font-black text-xs">%</span>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="tax_type" class="text-sm font-bold text-slate-300 ml-1">{{ __('Taxation Mode') }}</label>
                                <select name="tax_type" id="tax_type" 
                                    class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 outline-none appearance-none cursor-pointer">
                                    <option value="inherit" {{ old('tax_type', $product->tax_type) == 'inherit' ? 'selected' : '' }}>{{ __('Inherit Global') }}</option>
                                    <option value="exclusive" {{ old('tax_type', $product->tax_type) == 'exclusive' ? 'selected' : '' }}>{{ __('Exclusive') }}</option>
                                    <option value="inclusive" {{ old('tax_type', $product->tax_type) == 'inclusive' ? 'selected' : '' }}>{{ __('Inclusive') }}</option>
                                </select>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest pl-1">{{ __('Prioritizes: Custom Rate > Period Rule > Global Default') }}</p>
                    </div>

                    <!-- Sale Price Schedule -->
                    <div class="pt-8 border-t border-white/5 space-y-6" x-data="{ 
                        sales: {{ json_encode($product->sales->map(fn($s) => [
                            'price' => $s->price,
                            'starts_at' => $s->starts_at->format('Y-m-d\TH:i'),
                            'expires_at' => $s->expires_at->format('Y-m-d\TH:i'),
                            'is_active' => $s->is_active
                        ])) }},
                        addSale() {
                            this.sales.push({ price: '', starts_at: '', expires_at: '', is_active: true });
                        },
                        removeSale(index) {
                            this.sales.splice(index, 1);
                        }
                    }">
                        <div class="flex items-center justify-between">
                            <h5 class="text-sm font-bold text-indigo-300">{{ __('Sale Price Schedule') }}</h5>
                            <button type="button" @click="addSale()" class="px-4 py-2 bg-indigo-600/20 hover:bg-indigo-600/40 text-indigo-400 text-[10px] font-black uppercase tracking-widest rounded-xl border border-indigo-500/20 transition-all flex items-center gap-2">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="3" d="M12 4v16m8-8H4"/></svg>
                                {{ __('Add Schedule') }}
                            </button>
                        </div>
                        
                        <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">{{ __('Define specific periods for promotional pricing. Periods must not overlap.') }}</p>

                        <div class="space-y-4">
                            <template x-for="(sale, index) in sales" :key="index">
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 p-6 bg-white/5 border border-white/10 rounded-[2rem] relative group/sale">
                                    <div class="md:col-span-3 space-y-1">
                                        <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest">{{ __('Sale Price') }}</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-xs">¥</span>
                                            <input type="number" :name="'sales['+index+'][price]'" x-model="sale.price" required
                                                class="w-full pl-8 pr-4 py-3 bg-black/20 border border-white/5 rounded-xl text-white font-black text-sm focus:ring-1 focus:ring-indigo-500 outline-none transition-all">
                                        </div>
                                    </div>
                                    <div class="md:col-span-4 space-y-1">
                                        <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest">{{ __('Starts At') }}</label>
                                        <input type="datetime-local" :name="'sales['+index+'][starts_at]'" x-model="sale.starts_at" required
                                            class="w-full px-4 py-3 bg-black/20 border border-white/5 rounded-xl text-white font-bold text-xs focus:ring-1 focus:ring-indigo-500 outline-none transition-all">
                                    </div>
                                    <div class="md:col-span-4 space-y-1">
                                        <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest">{{ __('Expires At') }}</label>
                                        <input type="datetime-local" :name="'sales['+index+'][expires_at]'" x-model="sale.expires_at" required
                                            class="w-full px-4 py-3 bg-black/20 border border-white/5 rounded-xl text-white font-bold text-xs focus:ring-1 focus:ring-indigo-500 outline-none transition-all">
                                    </div>
                                    <div class="md:col-span-1 flex items-end justify-center pb-2">
                                        <button type="button" @click="removeSale(index)" class="p-2 text-rose-500/50 hover:text-rose-500 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                    
                                    <input type="hidden" :name="'sales['+index+'][is_active]'" value="1">
                                </div>
                            </template>
                        </div>

                        @if($errors->has('sales'))
                            <div class="p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl text-rose-500 text-xs font-bold">
                                {{ $errors->first('sales') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Organization Tab -->
            <div x-show="tab === 'organization'" x-cloak class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="glass p-8 rounded-[3rem] border border-white/5 shadow-2xl space-y-8">
                    <h4 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em]">{{ __('Storefront Categorization') }}</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <label class="text-sm font-bold text-slate-300 ml-1">{{ __('Department Select') }}</label>
                            <div class="space-y-2 max-h-64 overflow-y-auto pr-4 custom-scrollbar">
                                @php $productCategoryIds = $product->categories->pluck('id')->toArray(); @endphp
                                @foreach($categories as $category)
                                    <label class="flex items-center gap-3 p-3 bg-white/5 hover:bg-white/10 rounded-xl border border-white/5 cursor-pointer transition-all">
                                        <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" class="w-5 h-5 rounded border-white/10 bg-white/5 text-indigo-600 focus:ring-indigo-500 @if(in_array($category->id, $productCategoryIds)) checked @endif" @if(in_array($category->id, $productCategoryIds)) checked @endif>
                                        <span class="text-xs font-bold">{{ $category->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="space-y-4">
                            <label class="text-sm font-bold text-slate-300 ml-1">{{ __('Attribute Tags') }}</label>
                            <div class="flex flex-wrap gap-2">
                                @php $productTagIds = $product->tags->pluck('id')->toArray(); @endphp
                                @foreach($tags as $tag)
                                    <label class="relative cursor-pointer group">
                                        <input type="checkbox" name="tag_ids[]" value="{{ $tag->id }}" class="sr-only peer" @if(in_array($tag->id, $productTagIds)) checked @endif>
                                        <div class="px-4 py-2 bg-white/5 border border-white/10 rounded-full text-[10px] font-black uppercase tracking-widest text-slate-400 peer-checked:bg-white/20 peer-checked:text-white peer-checked:border-white/30 transition-all select-none">
                                            {{ $tag->name }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Media Tab -->
            <div x-show="tab === 'media'" x-cloak class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="glass p-8 rounded-[3rem] border border-white/5 shadow-2xl space-y-8">
                    <h4 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em]">{{ __('Gallery Management') }}</h4>
                    
                    @if($product->images->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-8">
                            @foreach($product->images as $image)
                                <div class="relative aspect-square bg-white/5 rounded-2xl overflow-hidden border border-white/10 group/img">
                                    <img src="{{ asset('storage/' . $image->file_path) }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-rose-600/60 opacity-0 group-hover/img:opacity-100 transition-all flex items-center justify-center">
                                        <span class="text-[10px] font-black text-white uppercase tracking-widest">{{ __('Saved') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="border-2 border-dashed border-white/10 rounded-[2rem] p-12 text-center hover:border-indigo-500/50 transition-all group">
                        <input type="file" name="images[]" id="images" multiple class="hidden">
                        <label for="images" class="cursor-pointer flex flex-col items-center gap-4">
                            <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center text-slate-500 group-hover:text-indigo-400 transition-colors">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-300">{{ __('Add more imagery here') }}</p>
                                <p class="text-[10px] text-slate-500 mt-1 uppercase tracking-widest font-medium">{{ __('High-res PNG, JPG or WebP (max 5MB)') }}</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Layout Tab -->
            <div x-show="tab === 'layout'" x-cloak class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <x-admin.layout-builder 
                    :assignedLayouts="$assignedLayouts" 
                    :sharedBlocks="$blockInstances" 
                    :blockTypes="$blockTypes ?? collect()" 
                />
            </div>

            <!-- SEO Tab -->
            <div x-show="tab === 'seo'" x-cloak class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="glass p-8 rounded-[3rem] border border-white/5 shadow-2xl space-y-8">
                    <h4 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em]">{{ __('Google & Search Synergy') }}</h4>
                    
                    <div class="space-y-2">
                        <label for="meta_title" class="text-sm font-bold text-slate-300 ml-1">{{ __('Search Title Override') }}</label>
                        <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $product->meta_title) }}" placeholder="{{ __('Recommended: Name | Brand') }}"
                            class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all">
                    </div>

                    <div class="space-y-2">
                        <label for="meta_description" class="text-sm font-bold text-slate-300 ml-1">{{ __('Search Meta Snippet') }}</label>
                        <textarea name="meta_description" id="meta_description" rows="4" placeholder="{{ __('Optimal: 155-160 characters describing your product value.') }}"
                            class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-slate-300 font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all">{{ old('meta_description', $product->meta_description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
[x-cloak] { display: none !important; }
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.05); border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.1); }
</style>
@endsection
