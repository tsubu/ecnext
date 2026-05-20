@extends('admin.layouts.base')

@section('title', __('edit') . ': ' . $category->name)
@section('page_title', __('Edit Category'))

@section('content')
<div class="mb-8 flex items-center gap-4">
    <a href="{{ route('admin.categories.index') }}" class="p-3 bg-white/5 hover:bg-white/10 rounded-2xl text-slate-400 hover:text-white transition-all">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
        <h3 class="text-xl font-bold tracking-tight text-white">{{ __('Modify Classification') }}</h3>
        <p class="text-sm text-slate-400">{{ __('Update the details and positioning of this product category.') }}</p>
    </div>
</div>

<form action="{{ route('admin.categories.update', $category) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 text-white">
        <!-- Main Form -->
        <div class="lg:col-span-8 space-y-8">
            <div class="glass p-8 rounded-[2rem] shadow-2xl border border-white/5 space-y-6">
                <h4 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em]">{{ __('Identity & Detail') }}</h4>
                
                <div class="space-y-2">
                    <label for="name" class="text-sm font-bold text-slate-300 ml-1">{{ __('Category Name') }}</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" placeholder="e.g. Wireless Audio" required
                        class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all">
                    @error('name') <p class="text-xs text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2 pt-2">
                    <label for="description" class="text-sm font-bold text-slate-300 ml-1">{{ __('Description (Optional)') }}</label>
                    <textarea name="description" id="description" rows="5" placeholder="{{ __('Describe the scope of this category...') }}"
                        class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-3xl text-slate-200 font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all">{{ old('description', $category->description) }}</textarea>
                    @error('description') <p class="text-xs text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Sidebar / Positioning -->
        <div class="lg:col-span-4 space-y-8">
            <div class="glass p-8 rounded-[2rem] shadow-2xl border border-white/5 space-y-6 sticky top-24">
                <h4 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em] mb-4">{{ __('Hierarchy & Display') }}</h4>
                
                <div class="space-y-2">
                    <label for="parent_id" class="text-sm font-bold text-slate-300 ml-1">{{ __('Parent Category') }}</label>
                    <select name="parent_id" id="parent_id" 
                        class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none appearance-none cursor-pointer">
                        <option value="" class="bg-slate-900">{{ __('Top Level (None)') }}</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" class="bg-slate-900" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2 pt-2">
                    <label for="sort_order" class="text-sm font-bold text-slate-300 ml-1">{{ __('Display Rank') }}</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0"
                        class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all">
                </div>

                <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl border border-white/5 mt-6">
                    <div class="flex flex-col">
                        <span class="text-xs font-bold">{{ __('Active Status') }}</span>
                        <span class="text-[10px] text-slate-500 font-medium uppercase tracking-wider">{{ __('Visible on Shop') }}</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-500"></div>
                    </label>
                </div>

                <div class="pt-4 flex flex-col gap-3">
                    <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-2xl transition-all shadow-lg shadow-indigo-600/20 active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        {{ __('Save Update') }}
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="w-full py-4 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white text-center font-bold rounded-2xl transition-all active:scale-95 text-xs uppercase tracking-widest">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
