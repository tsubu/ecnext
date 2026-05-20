@extends('admin.layouts.base')

@section('title', __('Edit Page Category'))
@section('page_title', __('Refining CMS Taxonomy'))

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('admin.page-categories.update', $page_category) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="glass p-8 rounded-[2rem] border border-white/5 space-y-6">
            <div>
                <label for="name" class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-3">{{ __('Category Name') }}</label>
                <input type="text" name="name" id="name" value="{{ old('name', $page_category->name) }}" required
                       class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none transition-all placeholder:text-slate-700">
            </div>

            <div>
                <label for="slug" class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-3">{{ __('URL Identifier (Slug)') }}</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $page_category->slug) }}" required
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white font-mono text-xs focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
            </div>

            <div>
                <label for="description" class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-3">{{ __('Internal Description') }}</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">{{ old('description', $page_category->description) }}</textarea>
            </div>

            <div>
                <label for="sort_order" class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-3">{{ __('Display Order') }}</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $page_category->sort_order) }}" required
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
            </div>

            <div class="pt-6 border-t border-white/5 flex gap-4">
                <button type="submit" class="flex-grow py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-black uppercase tracking-[0.2em] rounded-2xl transition-all shadow-xl shadow-indigo-600/20 active:scale-95">
                    {{ __('Update Category') }}
                </button>
                <a href="{{ route('admin.page-categories.index') }}" class="px-8 py-4 bg-white/5 hover:bg-white/10 text-slate-400 font-bold uppercase tracking-widest rounded-2xl transition-all border border-white/5 flex items-center">
                    {{ __('Cancel') }}
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
