@extends('admin.layouts.base')

@section('title', __('Shop Settings'))
@section('page_title', __('Identity & Branding'))

@section('content')
<div class="mb-8 flex items-center gap-4">
    <div class="p-3 bg-indigo-500/10 rounded-2xl text-indigo-400">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
    </div>
    <div>
        <h3 class="text-xl font-bold tracking-tight">{{ __('Shop Information & Legal') }}</h3>
        <p class="text-sm text-slate-400">{{ __('Configure your brand identity and compliance details') }}</p>
    </div>
</div>

@if(session('success'))
    <div class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400 text-sm font-bold flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
@endif

<form action="{{ route('admin.settings.shop.update') }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Main Form Column -->
        <div class="lg:col-span-8 space-y-8">
            <!-- Basic Branding Section -->
            <div class="glass p-8 rounded-[2rem] shadow-2xl border border-white/5 space-y-6 text-white">
                <h4 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em]">{{ __('Identity & Contact') }}</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="shop_name" class="text-sm font-bold text-slate-300 ml-1">{{ __('Shop Name') }}</label>
                        <input type="text" name="shop_name" id="shop_name" value="{{ old('shop_name', $setting->shop_name) }}" required
                            class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all">
                    </div>

                    <div class="space-y-2">
                        <label for="company_name" class="text-sm font-bold text-slate-300 ml-1">{{ __('Company Name') }}</label>
                        <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $setting->company_name) }}"
                            class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="email" class="text-sm font-bold text-slate-300 ml-1">{{ __('Support Email') }}</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $setting->email) }}"
                            class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all">
                    </div>

                    <div class="space-y-2">
                        <label for="phone" class="text-sm font-bold text-slate-300 ml-1">{{ __('Inquiry Phone') }}</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $setting->phone) }}"
                            class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all">
                    </div>
                </div>

                <div class="space-y-4 pt-4 border-t border-white/5">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">{{ __('Headquarters Physical Address') }}</label>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="md:col-span-1 space-y-2">
                            <label for="postal_code" class="text-xs font-bold text-slate-400 ml-1">{{ __('Postal Code') }}</label>
                            <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $setting->postal_code) }}" placeholder="100-0001"
                                class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all">
                        </div>
                        <div class="md:col-span-3 space-y-2">
                            <label for="address1" class="text-xs font-bold text-slate-400 ml-1">{{ __('Prefecture & City') }}</label>
                            <input type="text" name="address1" id="address1" value="{{ old('address1', $setting->address1) }}" placeholder="東京都千代田区..."
                                class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label for="address2" class="text-xs font-bold text-slate-400 ml-1">{{ __('Street Address & Building') }}</label>
                        <input type="text" name="address2" id="address2" value="{{ old('address2', $setting->address2) }}"
                            class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all">
                    </div>
                </div>
            </div>

            <!-- Regional & Global Section -->
            <div class="glass p-8 rounded-[2rem] shadow-2xl border border-white/5 space-y-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="text-xs font-black text-emerald-400 uppercase tracking-[0.2em]">{{ __('Regional & Global') }}</h4>
                    <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 text-[10px] font-black rounded-full uppercase tracking-widest">{{ __('International Sync') }}</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label for="country_code" class="text-sm font-bold text-slate-300 ml-1">{{ __('Usage Country') }}</label>
                        <select name="country_code" id="country_code" 
                            class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 outline-none appearance-none cursor-pointer">
                            <option value="JP" {{ old('country_code', $setting->country_code) == 'JP' ? 'selected' : '' }}>Japan (JP)</option>
                            <option value="US" {{ old('country_code', $setting->country_code) == 'US' ? 'selected' : '' }}>United States (US)</option>
                            <option value="GB" {{ old('country_code', $setting->country_code) == 'GB' ? 'selected' : '' }}>United Kingdom (GB)</option>
                            <option value="CN" {{ old('country_code', $setting->country_code) == 'CN' ? 'selected' : '' }}>China (CN) </option>
                            <option value="FR" {{ old('country_code', $setting->country_code) == 'FR' ? 'selected' : '' }}>France (FR)</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="currency_code" class="text-sm font-bold text-slate-300 ml-1">{{ __('System Currency') }}</label>
                        <select name="currency_code" id="currency_code" 
                            class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 outline-none appearance-none cursor-pointer">
                            <option value="JPY" {{ old('currency_code', $setting->currency_code) == 'JPY' ? 'selected' : '' }}>Japanese Yen (¥)</option>
                            <option value="USD" {{ old('currency_code', $setting->currency_code) == 'USD' ? 'selected' : '' }}>US Dollar ($)</option>
                            <option value="GBP" {{ old('currency_code', $setting->currency_code) == 'GBP' ? 'selected' : '' }}>British Pound (£)</option>
                            <option value="EUR" {{ old('currency_code', $setting->currency_code) == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                            <option value="CNY" {{ old('currency_code', $setting->currency_code) == 'CNY' ? 'selected' : '' }}>Chinese Yuan (¥)</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="timezone" class="text-sm font-bold text-slate-300 ml-1">{{ __('System Timezone') }}</label>
                        <select name="timezone" id="timezone" 
                            class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 outline-none appearance-none cursor-pointer">
                            <option value="Asia/Tokyo" {{ old('timezone', $setting->timezone) == 'Asia/Tokyo' ? 'selected' : '' }}>Asia/Tokyo</option>
                            <option value="UTC" {{ old('timezone', $setting->timezone) == 'UTC' ? 'selected' : '' }}>UTC (Standard Time)</option>
                            <option value="America/New_York" {{ old('timezone', $setting->timezone) == 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                            <option value="Europe/London" {{ old('timezone', $setting->timezone) == 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                        </select>
                    </div>
                </div>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest pl-1">{{ __('Aligns all logs, currency symbols, and schedules to this region.') }}</p>
            </div>

            <!-- Economics & Taxation Section -->
            <div class="glass p-8 rounded-[2rem] shadow-2xl border border-white/5 space-y-6 text-white">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-black text-amber-400 uppercase tracking-[0.2em]">{{ __('Economics & Taxation') }}</h4>
                    <a href="{{ route('admin.tax_rules.index') }}" class="px-4 py-2 bg-amber-500/10 hover:bg-amber-500/20 text-amber-500 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all border border-amber-500/20">
                        {{ __('Manage Tax Periods') }}
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-white/5">
                    <div class="space-y-2">
                        <label for="global_tax_type" class="text-sm font-bold text-slate-300 ml-1">{{ __('Global Tax Display style') }}</label>
                        <select name="global_tax_type" id="global_tax_type" 
                            class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 outline-none appearance-none cursor-pointer">
                            <option value="exclusive" {{ old('global_tax_type', $setting->global_tax_type) == 'exclusive' ? 'selected' : '' }}>{{ __('Exclusive (Price + Tax)') }}</option>
                            <option value="inclusive" {{ old('global_tax_type', $setting->global_tax_type) == 'inclusive' ? 'selected' : '' }}>{{ __('Inclusive (Tax included in Price)') }}</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label for="tax_rate" class="text-sm font-bold text-slate-300 ml-1">{{ __('Default Fallback Tax Rate (%)') }}</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="tax_rate" id="tax_rate" value="{{ old('tax_rate', $setting->tax_rate) }}"
                                class="w-full pl-5 pr-12 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all">
                            <span class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-500 font-black text-xs">%</span>
                        </div>
                    </div>
                </div>
                    <div class="space-y-2">
                        <label for="default_shipping_fee" class="text-sm font-bold text-slate-300 ml-1">{{ __('Flat-rate Shipping Threshold (JPY)') }}</label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-500 font-black text-xs">¥</span>
                            <input type="number" name="default_shipping_fee" id="default_shipping_fee" value="{{ old('default_shipping_fee', $setting->default_shipping_fee) }}"
                                class="w-full pl-10 pr-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loyalty Program Section -->
            <div class="glass p-8 rounded-[2rem] shadow-2xl border border-white/5 space-y-8 text-white" x-data="{ enabled: {{ old('points_enabled', $setting->points_enabled) ? 'true' : 'false' }} }">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-xs font-black text-purple-400 uppercase tracking-[0.2em]">{{ __('Reward Program (Points)') }}</h4>
                        <p class="text-[10px] text-slate-500 font-bold mt-1 uppercase tracking-widest">{{ __('In-store currency and customer retention') }}</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="points_enabled" class="sr-only peer" @change="enabled = $event.target.checked" {{ old('points_enabled', $setting->points_enabled) ? 'checked' : '' }}>
                        <div class="w-14 h-7 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-slate-400 peer-checked:after:bg-indigo-500 after:rounded-full after:h-5 after:w-6 after:transition-all peer-checked:bg-indigo-500/20 border border-white/5"></div>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-show="enabled" x-collapse>
                    <div class="space-y-2">
                        <label for="point_rate" class="text-sm font-bold text-slate-300 ml-1">{{ __('Point Award Grant Rate (%)') }}</label>
                        <div class="relative">
                            <input type="number" step="0.1" name="point_rate" id="point_rate" value="{{ old('point_rate', $setting->point_rate) }}"
                                class="w-full pl-5 pr-12 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all">
                            <span class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-500 font-black text-xs">%</span>
                        </div>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest pl-1">{{ __('Example: 1% awards 1pt per ¥100 spent.') }}</p>
                    </div>
                    <div class="space-y-2">
                        <label for="point_conversion_rate" class="text-sm font-bold text-slate-300 ml-1">{{ __('Point-to-Currency Conversion') }}</label>
                        <div class="flex items-center gap-3">
                            <div class="flex-grow relative">
                                <span class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-[10px] uppercase">PT</span>
                                <input type="text" disabled value="1" class="w-full pl-5 pr-12 py-4 bg-white/5 border border-white/10 rounded-2xl text-slate-500 font-black text-center">
                            </div>
                            <span class="text-xl font-black text-slate-600">=</span>
                            <div class="flex-grow relative text-indigo-400">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 font-black text-xs">¥</span>
                                <input type="number" step="0.1" name="point_conversion_rate" id="point_conversion_rate" value="{{ old('point_conversion_rate', $setting->point_conversion_rate) }}"
                                    class="w-full pl-10 pr-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-indigo-400 font-black focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all">
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Trade Law Section -->
            <div class="glass p-8 rounded-[2rem] shadow-2xl border border-white/5 space-y-6 text-white">
                <div class="flex items-center gap-3 mb-2">
                    <h4 class="text-xs font-black text-rose-400 uppercase tracking-[0.2em]">{{ __('Trade Law Compliance') }}</h4>
                    <span class="px-2 py-0.5 bg-rose-500/10 text-rose-400 text-[8px] font-black rounded uppercase tracking-widest">{{ __('Legal Requirement') }}</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="trade_law_manager" class="text-sm font-bold text-slate-300 ml-1">{{ __('Operating Manager') }}</label>
                        <input type="text" name="trade_law_manager" id="trade_law_manager" value="{{ old('trade_law_manager', $setting->trade_law_manager) }}"
                            class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all">
                    </div>

                    <div class="space-y-2">
                        <label for="trade_law_tel" class="text-sm font-bold text-slate-300 ml-1">{{ __('Official Contact Phone') }}</label>
                        <input type="text" name="trade_law_tel" id="trade_law_tel" value="{{ old('trade_law_tel', $setting->trade_law_tel) }}"
                            class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all">
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="trade_law_address" class="text-sm font-bold text-slate-300 ml-1">{{ __('Official Business Address') }}</label>
                    <input type="text" name="trade_law_address" id="trade_law_address" value="{{ old('trade_law_address', $setting->trade_law_address) }}"
                        class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all">
                </div>

                <hr class="border-white/5">

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label for="trade_law_price_info" class="text-sm font-bold text-slate-300 ml-1">{{ __('Additional Fees (Price Info)') }}</label>
                        <textarea name="trade_law_price_info" id="trade_law_price_info" rows="3"
                            class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all resize-none">{{ old('trade_law_price_info', $setting->trade_law_price_info) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <label for="trade_law_payment_methods" class="text-sm font-bold text-slate-300 ml-1">{{ __('Payment Terms & Methods') }}</label>
                        <textarea name="trade_law_payment_methods" id="trade_law_payment_methods" rows="3"
                            class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all resize-none">{{ old('trade_law_payment_methods', $setting->trade_law_payment_methods) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <label for="trade_law_delivery_info" class="text-sm font-bold text-slate-300 ml-1">{{ __('Delivery Timeframes') }}</label>
                        <textarea name="trade_law_delivery_info" id="trade_law_delivery_info" rows="3"
                            class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all resize-none">{{ old('trade_law_delivery_info', $setting->trade_law_delivery_info) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <label for="trade_law_return_policy" class="text-sm font-bold text-slate-300 ml-1">{{ __('Return & Exchange Policy') }}</label>
                        <textarea name="trade_law_return_policy" id="trade_law_return_policy" rows="3"
                            class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all resize-none">{{ old('trade_law_return_policy', $setting->trade_law_return_policy) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Sidebar Area -->
        <div class="lg:col-span-4 space-y-8">
            <div class="glass p-8 rounded-[2rem] shadow-2xl border border-white/5 space-y-6 sticky top-24">
                <h4 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em] mb-4">{{ __('Publishing') }}</h4>
                <p class="text-xs text-slate-400 mb-6 leading-relaxed">
                    {{ __('Updating these settings will change the official identity and legal documentation across the storefront.') }}
                </p>
                
                <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-2xl transition-all shadow-lg shadow-indigo-600/20 active:scale-95 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    {{ __('Save Shop Identity') }}
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
