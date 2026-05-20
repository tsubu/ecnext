@extends('admin.layouts.base')

@section('title', __('Product Reviews'))
@section('page_title', __('Review Moderation'))

@section('content')
<div class="space-y-6">
    <div class="glass p-8 rounded-[2.5rem] border border-white/5 shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-white/5">
                        <th class="px-6 py-4">{{ __('Product') }}</th>
                        <th class="px-6 py-4">{{ __('User') }}</th>
                        <th class="px-6 py-4">{{ __('Rating') }}</th>
                        <th class="px-6 py-4">{{ __('Comment') }}</th>
                        <th class="px-6 py-4">{{ __('Status') }}</th>
                        <th class="px-6 py-4 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($reviews as $review)
                    <tr class="group hover:bg-white/[0.02] transition-colors">
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-white">{{ $review->product->name }}</p>
                            <p class="text-[10px] text-slate-500 font-mono">{{ $review->order?->order_number }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs font-bold text-slate-300">{{ $review->user->name }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-3 h-3 {{ $i <= $review->rating ? 'text-amber-400 fill-amber-400' : 'text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                @endfor
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs text-slate-400 max-w-xs truncate">{{ $review->comment }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($review->is_approved)
                                <span class="px-2 py-1 bg-emerald-500/10 text-emerald-400 text-[9px] font-black uppercase tracking-widest rounded-lg border border-emerald-500/20">
                                    {{ __('Approved') }}
                                </span>
                            @else
                                <span class="px-2 py-1 bg-amber-500/10 text-amber-400 text-[9px] font-black uppercase tracking-widest rounded-lg border border-amber-500/20">
                                    {{ __('Pending') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if(!$review->is_approved)
                                <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="p-2 text-emerald-400 hover:bg-emerald-500/10 rounded-xl transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-400 hover:bg-rose-500/10 rounded-xl transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-8">
            {{ $reviews->links() }}
        </div>
    </div>
</div>
@endsection
