@extends('themes.classic.pages.layout')

@section('content')
<article class="prose prose-slate max-w-none mb-20">
    <div class="mb-12 border-b border-black/5 pb-12">
        <span class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-600 mb-4 block">
            {{ $page->category?->name ?? __('Page') }}
        </span>
        <h1 class="page-title text-4xl md:text-6xl text-slate-900">{{ $page->title }}</h1>
    </div>

    <div class="text-lg leading-relaxed text-slate-700 space-y-8">
        {!! nl2br(e($page->content)) !!}
    </div>
</article>

{{-- Dynamic Blocks Rendering --}}
@if($page->layouts->count() > 0)
<div class="space-y-12">
    @foreach($page->layouts as $layout)
        @if($layout->blockInstance && $layout->blockInstance->is_active)
            <x-shop.renderer :instance="$layout->blockInstance" :settings="$layout->settings_override ?? []" />
        @endif
    @endforeach
</div>
@endif
@endsection
