@extends('admin.layouts.base')

@section('title', __('Edit Page'))
@section('page_title', __('Refining Content'))

@section('content')
<form action="{{ route('admin.pages.update', $page) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content Area -->
        <div class="lg:col-span-2 space-y-8">
            <div class="glass p-8 rounded-[2rem] border border-white/5 space-y-6">
                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-3">{{ __('Content Type') }}</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative group cursor-pointer">
                            <input type="radio" name="type" value="default" class="peer hidden" {{ $page->type === 'default' ? 'checked' : '' }} onchange="toggleEditor(this.value)">
                            <div class="p-4 bg-white/5 border border-white/10 rounded-2xl group-hover:bg-white/10 transition-all peer-checked:border-indigo-500 peer-checked:bg-indigo-500/10">
                                <span class="block text-sm font-bold text-white mb-1">{{ __('Standard Page') }}</span>
                                <span class="block text-[10px] text-slate-500 uppercase tracking-widest font-black">{{ __('General content & features') }}</span>
                            </div>
                        </label>
                        <label class="relative group cursor-pointer">
                            <input type="radio" name="type" value="legal" class="peer hidden" {{ $page->type === 'legal' ? 'checked' : '' }} onchange="toggleEditor(this.value)">
                            <div class="p-4 bg-white/5 border border-white/10 rounded-2xl group-hover:bg-white/10 transition-all peer-checked:border-amber-500 peer-checked:bg-amber-500/10">
                                <span class="block text-sm font-bold text-white mb-1">{{ __('Legal Form') }}</span>
                                <span class="block text-[10px] text-slate-500 uppercase tracking-widest font-black">{{ __('SCTA & Privacy Policy') }}</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="title" class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-3">{{ __('Page Title') }}</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $page->title) }}" required
                           class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none transition-all placeholder:text-slate-700">
                </div>

                <!-- Standard Editor -->
                <div id="standard-editor" class="{{ $page->type === 'legal' ? 'hidden' : '' }}">
                    <label for="content" class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-3">{{ __('Page Content') }}</label>
                    <textarea name="content" id="content" rows="15"
                              class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-medium focus:ring-2 focus:ring-indigo-500 outline-none transition-all placeholder:text-slate-700">{{ old('content', $page->content) }}</textarea>
                </div>

                <!-- Legal Form -->
                <div id="legal-form" class="{{ $page->type === 'legal' ? '' : 'hidden' }} space-y-6">
                    <div class="p-6 bg-amber-500/5 border border-amber-500/10 rounded-2xl">
                        <p class="text-xs font-bold text-amber-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            {{ __('Legal Disclosure Fields') }}
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @php
                                $legalKeys = [
                                    'seller_name' => __('Seller Name'),
                                    'representative' => __('Representative'),
                                    'address' => __('Location'),
                                    'phone' => __('Phone Number'),
                                    'email' => __('Email Address'),
                                    'fees' => __('Additional Fees'),
                                    'payment_methods' => __('Payment Methods'),
                                    'payment_deadline' => __('Payment Deadline'),
                                    'delivery_time' => __('Delivery Time'),
                                    'returns' => __('Returns & Exchanges Policy')
                                ];
                            @endphp
                            @foreach($legalKeys as $key => $label)
                                <div class="space-y-2">
                                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $label }}</label>
                                    <input type="text" name="legal_data[{{ $key }}]" value="{{ old("legal_data.$key", $page->legal_data[$key] ?? '') }}"
                                           class="w-full bg-white/5 border border-white/5 rounded-xl px-4 py-3 text-white text-sm outline-none focus:border-amber-500/50 transition-all">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO Settings -->
            <div class="glass p-8 rounded-[2rem] border border-white/5 space-y-6">
                <h4 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Search Engine Optimization') }}</h4>
                <div class="space-y-4">
                    <input type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" placeholder="{{ __('Meta Title Override') }}"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm outline-none focus:ring-1 focus:ring-indigo-500">
                    <textarea name="meta_description" rows="3" placeholder="{{ __('Meta Description') }}"
                              class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm outline-none focus:ring-1 focus:ring-indigo-500">{{ old('meta_description', $page->meta_description) }}</textarea>
                </div>
            </div>

            <!-- Layout Builder -->
            <x-admin.layout-builder 
                :assignedLayouts="$assignedLayouts" 
                :sharedBlocks="$blockInstances" 
                :blockTypes="$blockTypes ?? collect()" 
            />
        </div>

        <!-- Sidebar Settings -->
        <div class="space-y-8">
            <div class="glass p-8 rounded-[2rem] border border-white/5 space-y-6">
                <div>
                    <label for="slug" class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-3">{{ __('URL Identifier (Slug)') }}</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $page->slug) }}" required
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white font-mono text-xs focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                </div>

                <div>
                    <label for="page_category_id" class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-3">{{ __('Category') }}</label>
                    <select name="page_category_id" id="page_category_id"
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        <option value="">{{ __('Uncategorized') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('page_category_id', $page->page_category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-4 border-t border-white/5 space-y-4">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', $page->is_published) ? 'checked' : '' }} class="peer hidden">
                        <div class="w-5 h-5 bg-white/5 border border-white/10 rounded-md group-hover:bg-white/10 transition-all peer-checked:bg-indigo-600 peer-checked:border-indigo-500 flex items-center justify-center">
                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="4" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="text-sm font-bold text-slate-300">{{ __('Publish Content') }}</span>
                    </label>

                    <div class="space-y-4 pt-2">
                        <div>
                            <label class="block text-[10px] font-black text-slate-600 uppercase tracking-[0.2em] mb-2">{{ __('Visible From') }}</label>
                            <input type="datetime-local" name="published_at" value="{{ old('published_at', $page->published_at?->format('Y-m-d\TH:i')) }}"
                                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-xs outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-600 uppercase tracking-[0.2em] mb-2">{{ __('Visible Until (Optional)') }}</label>
                            <input type="datetime-local" name="expired_at" value="{{ old('expired_at', $page->expired_at?->format('Y-m-d\TH:i')) }}"
                                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-xs outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-black uppercase tracking-[0.2em] rounded-2xl transition-all shadow-xl shadow-indigo-600/20 active:scale-95">
                {{ __('Update Content') }}
            </button>
            <a href="{{ route('admin.pages.index') }}" class="block text-center text-xs font-bold text-slate-500 hover:text-slate-400 transition-colors uppercase tracking-widest">
                {{ __('Discard Changes') }}
            </a>
        </div>
    </div>
</form>

<script>
    function toggleEditor(type) {
        const standard = document.getElementById('standard-editor');
        const legal = document.getElementById('legal-form');
        
        if (type === 'legal') {
            standard.classList.add('hidden');
            legal.classList.remove('hidden');
        } else {
            standard.classList.remove('hidden');
            legal.classList.add('hidden');
        }
    }
</script>
@endsection
