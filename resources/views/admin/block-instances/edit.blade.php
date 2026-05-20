@extends('admin.layouts.base')

@php
    $settingsLocalesDefault = json_encode($block_instance->settings_locales ?? new \stdClass(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $settingsLocalesInitial = old('settings_locales') !== null ? old('settings_locales') : $settingsLocalesDefault;
    $blockLocalesList = block_configured_locales();
    $defaultBlockPreviewLocale = in_array(app()->getLocale(), $blockLocalesList, true)
        ? app()->getLocale()
        : ($blockLocalesList[0] ?? 'en');
@endphp

@section('title', __('Edit Block'))

@section('content')
<div x-data="blockEditor()" class="max-w-[1600px] mx-auto">
    <div class="mb-8 flex justify-between items-center">
        <a href="{{ route('admin.block-center.index') }}" class="text-xs font-bold text-slate-500 uppercase tracking-widest hover:text-white transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M15 19l-7-7 7-7"/></svg>
            {{ __('Back to Library') }}
        </a>
        <div class="flex gap-4">
            <button type="button" @click="setEditorTab('visual')" :class="mode === 'visual' ? 'bg-indigo-600 text-white' : 'bg-white/5 text-slate-400'" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">{{ __('Visual') }}</button>
            <button type="button" @click="setEditorTab('code')" :class="mode === 'code' ? 'bg-indigo-600 text-white' : 'bg-white/5 text-slate-400'" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">{{ __('Code (JSON)') }}</button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        {{-- Left: metadata + schema fields. Right: Live preview (Visual) or JSON (Code). --}}
        <div class="lg:col-span-5 space-y-8">
            <form action="{{ route('admin.block-instances.update', $block_instance) }}" method="POST" id="block-form" class="space-y-8">
                @csrf
                @method('PUT')
                <div class="glass p-8 rounded-[2.5rem] border border-white/5 space-y-8 shadow-2xl">
                    <!-- Template Selection -->
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">{{ __('Template') }}</label>
                        <select name="block_type_id" x-model="blockTypeId" @change="onTypeChange()" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all outline-none">
                            <option value="">{{ __('Select a template...') }}</option>
                            @foreach($blockTypes as $type)
                                <option value="{{ $type->id }}" data-schema='@json($type->schema)'>{{ $type->resolvedName() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Presets (Usage Examples) -->
                    <template x-if="filteredPresets.length > 0">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">{{ __('Usage Examples (Presets)') }}</label>
                            <div class="grid grid-cols-1 gap-2">
                                <template x-for="preset in filteredPresets" :key="preset.id">
                                    <button type="button" @click="applyPreset(preset)" class="flex items-center justify-between p-4 bg-white/5 hover:bg-white/10 border border-white/5 rounded-2xl transition-all group text-left">
                                        <span class="text-xs font-bold text-slate-300 group-hover:text-white" x-text="preset.name"></span>
                                        <span class="text-[10px] font-black text-indigo-400 opacity-0 group-hover:opacity-100 transition-opacity">{{ __('APPLY') }}</span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Name and Slug -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">{{ __('Name') }} <span class="text-slate-600 normal-case">({{ __('fallback if locale label empty') }})</span></label>
                            <input type="text" name="name" value="{{ old('name', $block_instance->name) }}" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-white text-sm outline-none focus:border-indigo-500">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">{{ __('Slug') }}</label>
                            <input type="text" name="slug" value="{{ old('slug', $block_instance->slug) }}" class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-indigo-300 font-mono text-xs outline-none focus:border-indigo-500">
                        </div>
                    </div>

                    <div class="p-6 rounded-2xl border border-white/10 bg-white/[0.02] space-y-4">
                        <h5 class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">{{ __('Block translations') }}</h5>
                        <p class="text-[9px] text-slate-500 leading-relaxed">{{ __('Optional titles per language. Placeholders are examples only. If empty, the fallback name above is used.') }}</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                            @foreach(block_admin_locale_rows() as $row)
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 font-mono text-indigo-300/90">{{ strtoupper($row['code']) }}</label>
                                    <input type="text" name="name_locales[{{ $row['code'] }}]" value="{{ old('name_locales.'.$row['code'], $block_instance->name_locales[$row['code']] ?? '') }}" class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-white text-sm outline-none focus:border-indigo-500" placeholder="{{ $row['placeholder'] }}">
                                </div>
                            @endforeach
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">{{ __('Locale overrides (JSON)') }}</label>
                            <p class="text-[9px] text-slate-600">{{ __('Per-locale partial settings merged over the JSON config below. Example:') }} <code class="text-indigo-400/80">{"en":{"title":"Hello"},"ja":{"title":"こんにちは"}}</code></p>
                            <textarea name="settings_locales" x-model="settingsLocalesJson" rows="8" class="w-full bg-slate-950/50 border border-white/10 rounded-2xl px-4 py-3 text-indigo-200 font-mono text-xs outline-none focus:border-indigo-500"></textarea>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <template x-for="(label, key) in schemaFields" :key="key">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1" x-text="key.replace('_', ' ').toUpperCase()"></label>
                                <template x-if="typeof settings[key] === 'string' && settings[key].length > 100">
                                    <textarea x-model="settings[key]" class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-white text-sm outline-none focus:border-indigo-500" rows="4"></textarea>
                                </template>
                                <template x-if="!(typeof settings[key] === 'string' && settings[key].length > 100)">
                                    <input type="text" x-model="settings[key]" class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-white text-sm outline-none focus:border-indigo-500">
                                </template>
                            </div>
                        </template>
                        <div x-show="!blockTypeId" class="py-10 text-center">
                            <p class="text-xs text-slate-600 italic">{{ __('Select a template to show fields') }}</p>
                        </div>
                    </div>

                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="is_active" value="1" {{ $block_instance->is_active ? 'checked' : '' }} class="hidden peer">
                        <div class="w-4 h-4 rounded border border-white/20 peer-checked:bg-indigo-600 peer-checked:border-indigo-600 flex items-center justify-center transition-all">
                            <svg class="w-3 h-3 text-white scale-0 peer-checked:scale-100 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 group-hover:text-white transition-colors uppercase tracking-widest">{{ __('Enable Block') }}</span>
                    </label>
                </div>

                <div class="flex justify-between items-center">
                    <button type="button" @click="confirmDelete()" class="text-[10px] font-black text-rose-500/50 hover:text-rose-500 uppercase tracking-widest transition-colors">{{ __('Delete Block') }}</button>
                    <button type="submit" class="px-12 py-4 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-black rounded-2xl transition-all shadow-xl shadow-indigo-600/20 active:scale-95">
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </form>
            <form id="delete-form" action="{{ route('admin.block-instances.destroy', $block_instance) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>

        <div class="lg:col-span-7">
            <div class="sticky top-8 h-[calc(100vh-8rem)] min-h-[20rem]" x-bind:class="{ 'hidden': mode !== 'visual' }">
                <div class="h-full glass rounded-[3rem] border border-white/5 overflow-hidden flex flex-col shadow-2xl">
                    <div class="p-6 bg-white/5 border-b border-white/5 flex flex-wrap justify-between items-center gap-4">
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                            {{ __('Live Preview') }}
                        </span>
                        <div class="flex items-center gap-2">
                            <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest">{{ __('Preview locale') }}</label>
                            <select x-model="previewLocale" @change="updatePreview()" class="bg-white/5 border border-white/10 rounded-xl px-3 py-2 text-white text-xs outline-none focus:border-indigo-500">
                                @foreach(block_admin_locale_rows() as $row)
                                    <option value="{{ $row['code'] }}">{{ $row['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex-grow bg-[#fbfbfb] relative overflow-auto min-h-0">
                        <div id="preview-container" x-html="previewHtml" class="w-full min-h-full p-8 transition-opacity duration-300" :class="loading ? 'opacity-30' : 'opacity-100'"></div>
                        <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-white/50 backdrop-blur-sm">
                            <svg class="animate-spin h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sticky top-8 h-[calc(100vh-8rem)] min-h-[20rem]" x-bind:class="{ 'hidden': mode !== 'code' }">
                <div class="h-full glass rounded-[3rem] border border-white/5 shadow-2xl p-8 flex flex-col min-h-0 gap-3">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1 shrink-0">{{ __('JSON Config') }}</label>
                    <textarea name="settings" id="settings-json" form="block-form" x-model="settingsJson" rows="20" class="w-full flex-1 min-h-[16rem] bg-white/5 border border-white/10 rounded-3xl px-6 py-6 text-indigo-300 font-mono text-xs focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all outline-none resize-y"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function blockEditor() {
    return {
        mode: 'visual',
        blockTypeId: '{{ old('block_type_id', $block_instance->block_type_id) }}',
        settings: @json($block_instance->settings ?: []),
        settingsJson: @json(json_encode($block_instance->settings ?: [], JSON_PRETTY_PRINT)),
        settingsLocalesJson: {!! json_encode($settingsLocalesInitial) !!},
        previewLocale: '{{ old('preview_locale', $defaultBlockPreviewLocale) }}',
        previewHtml: '',
        loading: false,
        presets: @json($presets),
        schemaFields: {},
        debounceTimer: null,

        init() {
            if (this.blockTypeId) {
                this.onTypeChange();
                this.updatePreview();
            }
            
            // While preview tab is active, keep JSON string in sync (for code tab + submit)
            this.$watch('settings', (value) => {
                if (this.mode === 'visual') {
                    this.settingsJson = JSON.stringify(value, null, 4);
                }
                this.debouncedPreview();
            }, { deep: true });

            this.$watch('settingsJson', (value) => {
                if (this.mode === 'code') {
                    try {
                        this.settings = JSON.parse(value);
                    } catch (e) {}
                }
                this.debouncedPreview();
            });

            this.$watch('settingsLocalesJson', () => this.debouncedPreview());
        },

        setEditorTab(tab) {
            if (tab === 'code') {
                this.settingsJson = JSON.stringify(this.settings, null, 4);
                this.mode = 'code';
            } else {
                try {
                    const parsed = JSON.parse(this.settingsJson);
                    if (parsed !== null && typeof parsed === 'object' && ! Array.isArray(parsed)) {
                        this.settings = parsed;
                    }
                } catch (e) { /* keep current settings */ }
                this.mode = 'visual';
            }
        },

        onTypeChange() {
            const select = document.querySelector('select[name="block_type_id"]');
            const selectedOption = select.options[select.selectedIndex];
            if (selectedOption && selectedOption.dataset.schema) {
                const schema = JSON.parse(selectedOption.dataset.schema);
                this.schemaFields = schema;
                
                // Merge current settings with schema defaults if needed
                let newSettings = { ...this.settings };
                Object.keys(schema).forEach(key => {
                    if (newSettings[key] === undefined) newSettings[key] = '';
                });
                this.settings = newSettings;
            } else {
                this.schemaFields = {};
            }
        },

        get filteredPresets() {
            return this.presets.filter(p => p.block_type_id == this.blockTypeId);
        },

        applyPreset(preset) {
            if (confirm('{{ __('Apply this preset? Current settings will be overwritten.') }}')) {
                this.settings = preset.settings;
                this.settingsJson = JSON.stringify(preset.settings, null, 4);
            }
        },

        debouncedPreview() {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                this.updatePreview();
            }, 600);
        },

        async updatePreview() {
            if (!this.blockTypeId) return;
            
            this.loading = true;
            try {
                const response = await fetch('{{ route('admin.block-instances.preview') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        block_type_id: this.blockTypeId,
                        settings: JSON.stringify(this.settings),
                        settings_locales: this.settingsLocalesJson,
                        preview_locale: this.previewLocale
                    })
                });
                const data = await response.json();
                this.previewHtml = data.html;
            } catch (error) {
                console.error('Preview error:', error);
            } finally {
                this.loading = false;
            }
        },

        confirmDelete() {
            if (confirm('{{ __('Are you sure you want to delete this block?') }}')) {
                document.getElementById('delete-form').submit();
            }
        }
    }
}
</script>
@endsection
