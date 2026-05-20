@inject('langService', 'App\Services\Localization\LanguageService')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('Dashboard')) | EC-NEXT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top left, #1e293b, #0f172a);
            color: #f8fafc;
            min-height: 100vh;
        }
        .glass {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .sidebar {
            width: 280px;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="bg-void text-slate-200 selection:bg-indigo-500/30">

    <div x-data="{ sidebarOpen: false }" class="flex min-h-screen overflow-hidden">
        
        <!-- Mobile Sidebar Backdrop -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-void/80 backdrop-blur-md z-[60] lg:hidden" 
             x-cloak></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed lg:sticky lg:top-0 inset-y-0 left-0 w-[280px] bg-void/80 backdrop-blur-3xl border-r border-white/5 z-[70] flex flex-col p-6 transition-transform duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] sidebar">
            
            <div class="flex items-center justify-between mb-10 px-2">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center shadow-lg shadow-indigo-500/50">
                        <span class="text-white font-bold">N</span>
                    </div>
                    <h1 class="text-xl font-bold tracking-tight text-white">EC-NEXT <span class="text-indigo-400 text-xs font-medium uppercase ml-1">{{ __('Admin') }}</span></h1>
                </div>
                <!-- Close Button (Mobile Only) -->
                <button @click="sidebarOpen = false" class="lg:hidden p-2 text-slate-500 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <nav class="flex-1 space-y-4 overflow-y-auto pr-2 custom-scrollbar">
                <!-- 1. Main Console -->
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] px-4 mb-4 mt-2">{{ __('main_console') }}</p>
                    <div class="space-y-1">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/20 shadow-lg shadow-indigo-600/5' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            <span class="text-xs font-bold">{{ __('dashboard') }}</span>
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.products.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            <span class="text-xs font-bold">{{ __('products') }}</span>
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <span class="text-xs font-bold">{{ __('orders') }}</span>
                        </a>
                    </div>
                </div>

                <!-- 2. Shop Operations (Collapsible) -->
                @php
                    $isOpsActive = request()->routeIs(['admin.customers.*', 'admin.notices.*', 'admin.campaigns.*', 'admin.reviews.*', 'admin.inquiries.*', 'admin.coupons.*', 'admin.pages.*']);
                @endphp
                <div x-data="{ open: @json($isOpsActive) }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] hover:text-white transition-colors">
                        <span>{{ __('shop_operations') }}</span>
                        <svg class="w-3 h-3 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="3" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-2 space-y-1 pl-2">
                        <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.customers.*') ? 'bg-indigo-600/20 text-indigo-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span class="text-xs font-bold">{{ __('customer_management') }}</span>
                        </a>
                        <a href="{{ route('admin.notices.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.notices.*') ? 'bg-indigo-600/20 text-indigo-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <span class="text-xs font-bold">{{ __('notices') }}</span>
                        </a>
                        <a href="{{ route('admin.campaigns.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.campaigns.*') ? 'bg-indigo-600/20 text-indigo-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5.882V19.297A2.497 2.497 0 0013.5 21.5h.5a2.5 2.5 0 002.5-2.5V5.882L11 5.882zM11 5.882c0-1.04-.84-1.882-1.882-1.882H7.5A2.5 2.5 0 005 6.5v12.797a2.497 2.497 0 002.5 2.203h.5a2.5 2.5 0 002.5-2.5V5.882z"/></svg>
                            <span class="text-xs font-bold">{{ __('Sales Campaigns') }}</span>
                        </a>
                        <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.reviews.*') ? 'bg-indigo-600/20 text-indigo-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            <span class="text-xs font-bold">{{ __('Product Reviews') }}</span>
                        </a>
                        <a href="{{ route('admin.inquiries.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.inquiries.*') ? 'bg-indigo-600/20 text-indigo-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            <span class="text-xs font-bold">{{ __('Inquiries') }}</span>
                        </a>
                        <a href="{{ route('admin.coupons.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.coupons.*') ? 'bg-indigo-600/20 text-indigo-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span class="text-xs font-bold">{{ __('Coupons') }}</span>
                        </a>
                        <a href="{{ route('admin.pages.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.pages.*') || request()->routeIs('admin.page-categories.*') ? 'bg-indigo-600/20 text-indigo-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="text-xs font-bold">{{ __('Static Pages') }}</span>
                        </a>
                    </div>
                </div>

                <!-- 3. Catalog Management (Collapsible) -->
                @php
                    $isCatalogActive = request()->routeIs(['admin.categories.*', 'admin.tags.*']);
                @endphp
                <div x-data="{ open: @json($isCatalogActive) }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] hover:text-white transition-colors">
                        <span>{{ __('catalog_management') }}</span>
                        <svg class="w-3 h-3 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="3" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-2 space-y-1 pl-2">
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-600/20 text-indigo-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            <span class="text-xs font-bold">{{ __('categories') }}</span>
                        </a>
                        <a href="{{ route('admin.tags.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.tags.*') ? 'bg-indigo-600/20 text-indigo-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            <span class="text-xs font-bold">{{ __('tags') }}</span>
                        </a>
                    </div>
                </div>

                <!-- 4. Theme and Design (Collapsible) -->
                @php
                    $isDesignActive = request()->routeIs(['admin.themes.*', 'admin.block-instances.*', 'admin.block-types.*', 'admin.block-center.*']);
                @endphp
                <div x-data="{ open: @json($isDesignActive) }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] hover:text-white transition-colors">
                        <span>{{ __('theme_and_design') }}</span>
                        <svg class="w-3 h-3 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="3" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-2 space-y-1 pl-2">
                        <a href="{{ route('admin.themes.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.themes.*') ? 'bg-indigo-600/20 text-indigo-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                            <span class="text-xs font-bold">{{ __('storefront_themes') }}</span>
                        </a>
                        <a href="{{ route('admin.block-center.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs(['admin.block-center.*', 'admin.block-instances.*', 'admin.block-types.*']) ? 'bg-indigo-600/20 text-indigo-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <span class="text-xs font-bold">{{ __('block_management_unified') }}</span>
                        </a>
                    </div>
                </div>

                <!-- 5. System Settings (Collapsible) -->
                @php
                    $isSystemActive = request()->routeIs(['admin.staff.*', 'admin.settings.shop.*', 'admin.review-rewards.*', 'admin.payments.*', 'admin.audit-logs.*']);
                @endphp
                <div x-data="{ open: @json($isSystemActive) }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] hover:text-white transition-colors">
                        <span>{{ __('system_settings') }}</span>
                        <svg class="w-3 h-3 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="3" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-2 space-y-1 pl-2">
                        <a href="{{ route('admin.staff.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.staff.*') ? 'bg-indigo-600/20 text-indigo-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span class="text-xs font-bold">{{ __('staff_management') }}</span>
                        </a>
                        <a href="{{ route('admin.settings.shop.edit') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.settings.shop.*') ? 'bg-indigo-600/20 text-indigo-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span class="text-xs font-bold">{{ __('shop_settings') }}</span>
                        </a>
                        <a href="{{ route('admin.review-rewards.edit') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.review-rewards.*') ? 'bg-indigo-600/20 text-indigo-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-xs font-bold">{{ __('Review Rewards') }}</span>
                        </a>
                        <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.payments.*') ? 'bg-indigo-600/20 text-indigo-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            <span class="text-xs font-bold">{{ __('Payment Gateways') }}</span>
                        </a>
                        <a href="{{ route('admin.audit-logs.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.audit-logs.*') ? 'bg-indigo-600/20 text-indigo-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="text-xs font-bold">{{ __('audit_logs') }}</span>
                        </a>
                        <a href="{{ route('admin.tax_rules.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.tax_rules.*') ? 'bg-indigo-600/20 text-indigo-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            <span class="text-xs font-bold">{{ __('Taxation Governance') }}</span>
                        </a>
                    </div>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <header class="sticky top-0 bg-void/60 backdrop-blur-xl border-b border-white/5 z-50 p-4 lg:p-8">
                <div class="flex justify-between items-center max-w-[1600px] mx-auto">
                    <div class="flex items-center gap-4">
                        <!-- Hamburger (Mobile Only) -->
                        <button @click="sidebarOpen = true" class="lg:hidden p-2.5 bg-white/5 hover:bg-white/10 rounded-xl transition-all border border-white/10 text-slate-400 hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                        </button>
                        <h2 class="text-lg md:text-2xl font-bold tracking-tight text-white">@yield('page_title')</h2>
                    </div>
                    
                    <div class="flex items-center gap-3 md:gap-6">
                        <!-- Date (Optional/Subtle) -->
                        <div class="hidden xl:block text-xs font-bold text-slate-500 uppercase tracking-widest">
                            {{ now()->translatedFormat(__('date_format')) }}
                        </div>

                        <!-- Language Switcher -->
                        <div class="relative group" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 md:px-4 md:py-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-full text-[10px] md:text-xs font-bold transition-all">
                                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="hidden sm:inline">{{ $langService->getAvailableLanguages()[app()->getLocale()] ?? strtoupper(app()->getLocale()) }}</span>
                                <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" @click.away="open = false" 
                                 class="absolute top-full right-0 mt-2 w-48 bg-slate-900 border border-white/10 rounded-2xl shadow-2xl z-50 overflow-hidden py-1" x-cloak>
                                @foreach($langService->getAvailableLanguages() as $code => $name)
                                    <a href="{{ route('admin.locale.update', $code) }}" class="flex items-center justify-between px-4 py-2.5 text-xs font-bold hover:bg-indigo-600 transition-colors {{ app()->getLocale() === $code ? 'text-indigo-400 bg-indigo-500/5' : 'text-slate-400' }}">
                                        {{ $name }}
                                        @if(app()->getLocale() === $code)
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="3" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- User Account -->
                        <div class="relative group" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-3 pl-3 pr-2 py-1.5 bg-white/5 hover:bg-white/10 border border-white/10 rounded-full transition-all">
                                <span class="hidden sm:inline text-xs font-bold text-white ml-1">{{ auth('admin')->user()->name }}</span>
                                <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-xs font-black text-white shadow-lg shadow-indigo-600/20 uppercase">
                                    {{ substr(auth('admin')->user()->name, 0, 2) }}
                                </div>
                            </button>
                            <div x-show="open" @click.away="open = false"
                                 class="absolute top-full right-0 mt-2 w-64 bg-slate-900 border border-white/10 rounded-3xl shadow-2xl z-50 overflow-hidden p-3" x-cloak>
                                <div class="mb-3 px-3 py-2">
                                    <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest mb-1">{{ __('Account') }}</p>
                                    <p class="text-xs font-bold text-white truncate">{{ auth('admin')->user()->email }}</p>
                                    <p class="text-[9px] text-indigo-400 font-black uppercase tracking-tighter mt-1">{{ auth('admin')->user()->role->display_name ?? 'Administrator' }}</p>
                                </div>
                                <div class="space-y-1 border-t border-white/5 pt-2">
                                    <form action="{{ route('admin.logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-rose-500/10 text-slate-400 hover:text-rose-500 text-xs font-bold rounded-2xl transition-all group/btn">
                                            <svg class="w-4 h-4 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            {{ __('sign_out') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <section class="p-4 md:p-8 max-w-[1600px] mx-auto">
                @yield('content')
            </section>
        </main>
    </div>

</body>
</html>
