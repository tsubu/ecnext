@extends('admin.layouts.base')

@section('title', __('audit_logs'))
@section('page_title', __('System Audit Logs'))

@section('content')
<div class="glass rounded-[2.5rem] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-white/5 text-xs text-slate-400 uppercase tracking-wider font-bold">
                <tr>
                    <th class="px-6 py-5">{{ __('Timestamp') }}</th>
                    <th class="px-6 py-5">{{ __('Administrator') }}</th>
                    <th class="px-6 py-5">{{ __('Event') }}</th>
                    <th class="px-6 py-5">{{ __('Auditable Target') }}</th>
                    <th class="px-6 py-5">{{ __('IP Address') }}</th>
                    <th class="px-6 py-5">{{ __('User Agent') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5 font-medium">
                @forelse($logs as $log)
                <tr class="hover:bg-white/5 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-[10px] text-slate-500 font-mono">{{ $log->created_at->format('Y-m-d H:i:s') }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-indigo-500/20 rounded-full flex items-center justify-center">
                                <span class="text-[8px] font-black text-indigo-400">{{ substr($log->user->name ?? '?', 0, 1) }}</span>
                            </div>
                            <span class="text-xs text-slate-300">{{ $log->user->name ?? __('System') }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $eventColors = [
                                'created' => 'text-emerald-400 bg-emerald-500/10',
                                'updated' => 'text-blue-400 bg-blue-500/10',
                                'deleted' => 'text-rose-400 bg-rose-500/10',
                            ];
                            $colorClass = $eventColors[$log->action] ?? 'text-slate-400 bg-slate-500/10';
                        @endphp
                        <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-widest {{ $colorClass }}">
                            {{ __($log->action) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-xs">
                            <span class="text-white font-bold">{{ __(class_basename($log->auditable_type)) }}</span>
                            <span class="text-slate-600 font-mono ml-1">#{{ $log->auditable_id }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-[10px] font-mono text-slate-500">{{ $log->ip_address }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="w-48 truncate text-[10px] text-slate-600 hover:text-slate-400 transition-colors cursor-default" title="{{ $log->user_agent }}">
                            {{ $log->user_agent }}
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-500 text-sm italic">
                        {{ __('No audit records found.') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="px-6 py-5 bg-white/5 border-t border-white/5">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection
