@extends('admin.layouts.base')

@section('title', __('dashboard'))
@section('page_title', __('Dashboard Overview'))

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Sales Card -->
    <a href="{{ route('admin.orders.index') }}" class="glass p-6 rounded-3xl hover:bg-white/10 transition-all group">
        <p class="text-slate-400 text-sm font-medium mb-1 group-hover:text-indigo-400 transition-colors">{{ __('Total Sales') }}</p>
        <h3 class="text-3xl font-bold">¥{{ number_format($stats['total_sales']) }}</h3>
        <div class="mt-4 flex items-center text-xs text-emerald-400">
            <span class="bg-emerald-400/10 px-2 py-0.5 rounded-full mr-2">↑ 12%</span>
            <span>{{ __('vs previous month') }}</span>
        </div>
    </a>

    <!-- Orders Card -->
    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="glass p-6 rounded-3xl hover:bg-white/10 transition-all group">
        <p class="text-slate-400 text-sm font-medium mb-1 group-hover:text-amber-400 transition-colors">{{ __('orders') }}</p>
        <h3 class="text-3xl font-bold">{{ number_format($stats['order_count']) }}</h3>
        <div class="mt-4 flex items-center text-xs text-sky-400">
            <span class="bg-sky-400/10 px-2 py-0.5 rounded-full mr-2">{{ __('Action Required') }}</span>
            <span>{{ $stats['order_count'] }} {{ __('processing') }}</span>
        </div>
    </a>

    <!-- Products Card -->
    <a href="{{ route('admin.products.index') }}" class="glass p-6 rounded-3xl hover:bg-white/10 transition-all group">
        <p class="text-slate-400 text-sm font-medium mb-1 group-hover:text-indigo-400 transition-colors">{{ __('products') }}</p>
        <h3 class="text-3xl font-bold">{{ number_format($stats['product_count']) }}</h3>
        <div class="mt-4 flex items-center text-xs text-indigo-400">
            <span class="bg-indigo-400/10 px-2 py-0.5 rounded-full mr-2">{{ __('Inventory') }}</span>
            <span>{{ __('View catalog') }}</span>
        </div>
    </a>
</div>

<!-- Recent Activity -->
<div class="glass rounded-3xl overflow-hidden">
    <div class="px-6 py-5 border-b border-white/5 flex justify-between items-center">
        <h4 class="font-bold tracking-tight">{{ __('Recent System Activity') }}</h4>
        <a href="{{ route('admin.audit-logs.index') }}" class="text-xs text-indigo-400 font-bold hover:text-indigo-300 transition-colors uppercase tracking-widest">{{ __('View all logs') }}</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-white/5 text-xs text-slate-400 uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4 font-semibold">{{ __('User') }}</th>
                    <th class="px-6 py-4 font-semibold">{{ __('Action') }}</th>
                    <th class="px-6 py-4 font-semibold">{{ __('Entity') }}</th>
                    <th class="px-6 py-4 font-semibold">{{ __('Time') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($stats['audit_logs'] as $log)
                <tr class="hover:bg-white/5 transition-colors">
                     <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                             <div class="w-6 h-6 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center text-[10px]">
                                {{ substr($log->user->name ?? '?', 0, 1) }}
                             </div>
                             <span class="text-sm font-medium">{{ __($log->user_type) }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-700 text-slate-300">
                            {{ __($log->action) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-300">
                        {{ __(class_basename($log->auditable_type)) }} #{{ $log->auditable_id }}
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-500">
                        {{ $log->created_at->diffForHumans() }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-10 text-center text-slate-500 text-sm italic">{{ __('No activity recorded yet.') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
