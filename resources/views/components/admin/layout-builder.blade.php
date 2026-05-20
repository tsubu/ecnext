@props([
    'assignedLayouts' => [], 
    'sharedBlocks' => [], 
    'blockTypes' => [],
    'target' => null
])

@php
    $initialLayouts = $assignedLayouts->map(fn($l) => [
        'id' => $l->id,
        'block_instance_id' => $l->block_instance_id,
        'block_type_id' => $l->blockInstance?->block_type_id,
        'name' => $l->blockInstance?->resolvedName(),
        'type_name' => $l->blockInstance?->blockType?->resolvedName(),
        'section_key' => $l->section_key,
        'settings' => $l->blockInstance?->settings ?? [],
        'is_shared' => (bool) $l->blockInstance?->is_shared,
        'is_new' => false,
        'starts_at' => $l->starts_at?->format('Y-m-d\TH:i'),
        'expires_at' => $l->expires_at?->format('Y-m-d\TH:i'),
        'schedule_label' => $l->schedule_label,
    ]);
@endphp

<div x-data="layoutBuilder({
    initialLayouts: {{ \Illuminate\Support\Js::from($initialLayouts) }},
    blockTypes: {{ \Illuminate\Support\Js::from($blockTypes) }},
    sharedBlocks: {{ \Illuminate\Support\Js::from($sharedBlocks) }}
})" class="space-y-8">
    
    <!-- Header/Controls -->
    <div class="flex justify-between items-center bg-white/5 p-6 rounded-[2rem] border border-white/5">
        <div class="space-y-1">
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">{{ __('layout_builder') }}</h4>
            <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">{{ __('Add, reorder, and configure page sections') }}</p>
        </div>
        
        <div class="flex gap-3">
            <button type="button" @click="openTypeSelector = true" class="px-6 py-3 bg-indigo-600/20 text-indigo-400 text-[10px] font-black uppercase tracking-widest rounded-xl border border-indigo-500/20 hover:bg-indigo-600/30 transition-all active:scale-95">
                {{ __('+ New Custom Section') }}
            </button>
            <button type="button" @click="openSharedSelector = true" class="px-6 py-3 bg-emerald-600/20 text-emerald-400 text-[10px] font-black uppercase tracking-widest rounded-xl border border-emerald-500/20 hover:bg-emerald-600/30 transition-all active:scale-95">
                {{ __('+ Add from Library') }}
            </button>
        </div>
    </div>

    <!-- Layout List -->
    <div class="space-y-4 min-h-[100px] transition-all" :class="layouts.length === 0 ? 'border-2 border-dashed border-white/5 rounded-[3rem] p-20 flex flex-col items-center justify-center' : ''">
        <template x-if="layouts.length === 0">
            <div class="text-center opacity-30">
                <svg class="w-12 h-12 text-slate-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <p class="text-xs font-black uppercase tracking-widest">{{ __('No sections defined yet') }}</p>
            </div>
        </template>

        <template x-for="(layout, index) in layouts" :key="index">
            <div class="relative group">
                <!-- Data Inputs for Submit -->
                <input type="hidden" :name="'layout[' + index + '][block_instance_id]'" :value="layout.block_instance_id">
                <input type="hidden" :name="'layout[' + index + '][block_type_id]'" :value="layout.block_type_id">
                <input type="hidden" :name="'layout[' + index + '][section_key]'" :value="layout.section_key">
                <input type="hidden" :name="'layout[' + index + '][starts_at]'" :value="layout.starts_at">
                <input type="hidden" :name="'layout[' + index + '][expires_at]'" :value="layout.expires_at">
                <input type="hidden" :name="'layout[' + index + '][settings]'" :value="JSON.stringify(layout.settings)">
                
                <div class="glass p-5 rounded-3xl border border-white/5 flex items-center justify-between transition-all hover:bg-white/10 hover:border-white/10 shadow-lg">
                    <div class="flex items-center gap-6">
                        <!-- Move Up/Down Buttons -->
                        <div class="flex flex-col gap-1">
                            <button type="button" @click="moveUp(index)" :disabled="index === 0" class="p-1 rounded-lg text-slate-600 hover:text-indigo-400 hover:bg-indigo-400/10 transition-all disabled:opacity-20 disabled:cursor-not-allowed disabled:hover:text-slate-600 disabled:hover:bg-transparent">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
                            </button>
                            <button type="button" @click="moveDown(index)" :disabled="index === layouts.length - 1" class="p-1 rounded-lg text-slate-600 hover:text-indigo-400 hover:bg-indigo-400/10 transition-all disabled:opacity-20 disabled:cursor-not-allowed disabled:hover:text-slate-600 disabled:hover:bg-transparent">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        </div>

                        <!-- Info -->
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-black text-white" x-text="layout.name || '{{ __('Untitled Section') }}'"></span>
                                <span x-show="layout.is_shared" class="px-2 py-0.5 bg-emerald-500/10 text-emerald-500 text-[8px] font-black uppercase tracking-widest border border-emerald-500/20 rounded-md">{{ __('Shared') }}</span>
                                <span x-show="!layout.is_shared" class="px-2 py-0.5 bg-indigo-500/10 text-indigo-500 text-[8px] font-black uppercase tracking-widest border border-indigo-500/20 rounded-md">{{ __('Local') }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest" x-text="'{{ __('Template') }}: ' + (layout.type_name || 'N/A')"></p>
                                <div x-show="layout.starts_at || layout.expires_at" class="flex items-center gap-1 px-2 py-0.5 bg-amber-500/10 text-amber-500 text-[8px] font-black uppercase tracking-widest border border-amber-500/20 rounded-md">
                                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span x-text="getScheduleLabel(layout)"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- Section Picker -->
                        <select x-model="layout.section_key" class="bg-slate-900/50 border border-white/5 rounded-xl px-3 py-1.5 text-[10px] text-slate-400 font-black uppercase tracking-widest outline-none focus:border-indigo-500 transition-all">
                             <option value="main">MAIN</option>
                             <option value="hero">HERO</option>
                             <option value="footer">FOOTER</option>
                        </select>

                        <!-- Actions -->
                        <div class="flex items-center gap-2 border-l border-white/5 pl-4">
                            <button type="button" @click="editBlock(index)" class="p-2 bg-white/5 rounded-xl text-slate-400 hover:text-indigo-400 hover:bg-indigo-400/10 transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button type="button" @click="removeBlock(index)" class="p-2 bg-white/5 rounded-xl text-slate-400 hover:text-rose-500 hover:bg-rose-500/10 transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Modals -->
    
    <!-- 1. Template Selector (Local) -->
    <div x-show="openTypeSelector" class="fixed inset-0 z-[2000] flex items-center justify-center p-10 bg-void/90 backdrop-blur-md" x-cloak>
        <div @click.away="openTypeSelector = false" class="glass w-full max-w-2xl rounded-[3rem] border border-white/10 overflow-hidden shadow-2xl animate-in zoom-in duration-300">
            <div class="p-8 border-b border-white/5 flex justify-between items-center">
                <h3 class="text-sm font-black text-white uppercase tracking-[0.3em]">{{ __('Select Section Template') }}</h3>
                <button @click="openTypeSelector = false" class="text-slate-500 hover:text-white transition-colors uppercase font-black text-[10px] tracking-widest">{{ __('Close') }}</button>
            </div>
            <div class="flex h-[60vh]">
                <!-- Modal Sidebar: Categories -->
                <div class="w-1/3 border-r border-white/5 p-6 space-y-2 overflow-y-auto bg-black/20">
                    <h4 class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-4 px-4">{{ __('Categories') }}</h4>
                    <template x-for="cat in [...new Set(blockTypes.map(t => t.category || 'General'))]" :key="cat">
                        <button type="button" 
                            @click="selectedCategory = cat" 
                            class="w-full text-left px-4 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all"
                            :class="selectedCategory === cat ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-400 hover:bg-white/5 hover:text-white'"
                            x-text="cat"
                        ></button>
                    </template>
                </div>

                <!-- Modal Content: Blocks -->
                <div class="w-2/3 p-8 overflow-y-auto grid grid-cols-1 gap-4 content-start">
                    <template x-for="type in blockTypes.filter(t => (t.category || 'General') === selectedCategory)" :key="type.id">
                        <button type="button" @click="addNewSection(type)" class="group flex items-center justify-between p-6 bg-white/5 border border-white/5 rounded-2xl text-left hover:bg-indigo-600/10 hover:border-indigo-600/30 transition-all">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-slate-500 group-hover:text-indigo-400 group-hover:bg-indigo-400/10 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="block text-xs font-black text-white mb-0.5 group-hover:text-indigo-400" x-text="type.display_name || type.name"></span>
                                    <span class="block text-[8px] text-slate-500 font-bold uppercase tracking-widest" x-text="type.type_key"></span>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Shared Block Selector (Library) -->
    <div x-show="openSharedSelector" class="fixed inset-0 z-[2000] flex items-center justify-center p-10 bg-void/90 backdrop-blur-md" x-cloak>
        <div @click.away="openSharedSelector = false" class="glass w-full max-w-3xl rounded-[3rem] border border-white/10 overflow-hidden shadow-2xl">
            <div class="p-8 border-b border-white/5 flex justify-between items-center">
                <h3 class="text-sm font-black text-white uppercase tracking-[0.3em]">{{ __('Pick from Library') }}</h3>
                <button @click="openSharedSelector = false" class="text-slate-500 hover:text-white transition-colors uppercase font-black text-[10px] tracking-widest">{{ __('Close') }}</button>
            </div>
            <div class="p-8 max-h-[60vh] overflow-y-auto space-y-3">
                <template x-for="block in sharedBlocks" :key="block.id">
                    <button type="button" @click="addSharedBlock(block)" class="w-full flex items-center justify-between p-6 bg-white/5 border border-white/5 rounded-2xl text-left hover:bg-emerald-600/10 transition-all group">
                         <div>
                            <span class="block text-xs font-black text-white mb-1 group-hover:text-emerald-400" x-text="block.display_name || block.name"></span>
                            <span class="block text-[9px] text-slate-500 font-bold uppercase tracking-widest" x-text="block.block_type.display_name || block.block_type.name"></span>
                         </div>
                         <span class="text-[9px] font-black text-emerald-500 opacity-0 group-hover:opacity-100 transition-opacity">{{ __('CHOOSE') }}</span>
                    </button>
                </template>
                <template x-if="sharedBlocks.length === 0">
                    <p class="text-center py-10 text-xs text-slate-600 italic">{{ __('No shared blocks available in library.') }}</p>
                </template>
            </div>
        </div>
    </div>

    <!-- 3. Local Editor / Override Modal -->
    <div x-show="editingIndex !== null" class="fixed inset-0 z-[2000] flex items-center justify-center p-10 bg-void/95 backdrop-blur-xl" x-cloak>
        <template x-if="editingIndex !== null">
            <div class="glass w-full max-w-6xl h-[80vh] rounded-[4rem] border border-white/10 overflow-hidden shadow-2xl flex flex-col">
                <div class="p-8 border-b border-white/5 flex justify-between items-center flex-none">
                    <div class="space-y-1">
                        <h3 class="text-sm font-black text-white uppercase tracking-[0.3em]" x-text="'{{ __('Configuring') }}: ' + layouts[editingIndex].name"></h3>
                        <p class="text-[9px] text-slate-500 font-black uppercase tracking-widest" x-text="layouts[editingIndex].is_shared ? '{{ __('shared_block_notice') }}' : '{{ __('local_block_notice') }}'"></p>
                    </div>
                    <button @click="editingIndex = null" class="px-8 py-3 bg-white/5 text-white text-[10px] font-black uppercase tracking-widest rounded-xl border border-white/10 hover:bg-white/10">{{ __('Done') }}</button>
                </div>
                
                <div class="flex-grow flex overflow-hidden">
                    <!-- Editor Left -->
                    <div class="w-1/2 p-10 overflow-y-auto space-y-8 border-r border-white/5">
                        <!-- Schedule Settings -->
                        <div class="p-6 bg-amber-500/5 border border-amber-500/10 rounded-3xl space-y-4">
                             <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <h5 class="text-[10px] font-black text-amber-500 uppercase tracking-widest">{{ __('Visibility Schedule') }}</h5>
                             </div>
                             <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest ml-1">{{ __('Starts At') }}</label>
                                    <input type="datetime-local" x-model="layouts[editingIndex].starts_at" class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-3 text-white text-xs outline-none focus:border-amber-500">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest ml-1">{{ __('Expires At') }}</label>
                                    <input type="datetime-local" x-model="layouts[editingIndex].expires_at" class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-3 text-white text-xs outline-none focus:border-amber-500">
                                </div>
                             </div>
                             <p class="text-[8px] text-slate-500 font-bold italic">{{ __('Leave empty for permanent visibility.') }}</p>
                        </div>

                        <template x-for="(label, key) in getSchemaFor(layouts[editingIndex].block_type_id)" :key="key">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1" x-text="key.replace(/_/g, ' ').toUpperCase()"></label>
                                
                                <template x-if="label === 'text'">
                                    <textarea x-model="layouts[editingIndex].settings[key]" class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white text-sm outline-none focus:border-indigo-500 transition-all" rows="4"></textarea>
                                </template>

                                <template x-if="label === 'string' || label === 'image'">
                                    <div class="relative">
                                        <input type="text" x-model="layouts[editingIndex].settings[key]" class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white text-sm outline-none focus:border-indigo-500 transition-all font-mono" :placeholder="'Enter ' + label + ' URL or text...'">
                                        <template x-if="label === 'image' && layouts[editingIndex].settings[key]">
                                            <div class="mt-2 group/prev relative aspect-video w-32 rounded-xl overflow-hidden border border-white/10 bg-black">
                                                <img :src="layouts[editingIndex].settings[key]" class="w-full h-full object-cover opacity-60 group-hover/prev:opacity-100 transition-opacity">
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                <template x-if="label === 'integer' || label === 'number'">
                                    <input type="number" x-model.number="layouts[editingIndex].settings[key]" class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white text-sm outline-none focus:border-indigo-500 transition-all">
                                </template>
                            </div>
                        </template>
                    </div>

                    <!-- Preview Right -->
                    <div class="w-1/2 bg-[#fbfbfb] p-10 overflow-y-auto relative">
                        <div class="absolute top-6 left-6 flex items-center gap-2">
                             <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                             <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ __('Live Preview') }}</span>
                        </div>
                        
                        <div x-html="previewHtml" class="w-full mt-10"></div>
                        
                        <div x-show="loadingPreview" class="absolute inset-0 bg-white/50 backdrop-blur-sm flex items-center justify-center">
                            <svg class="animate-spin h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>

<script>
function layoutBuilder({ initialLayouts, blockTypes, sharedBlocks }) {
    return {
        layouts: initialLayouts,
        blockTypes: blockTypes,
        sharedBlocks: sharedBlocks,
        openTypeSelector: false,
        openSharedSelector: false,
        editingIndex: null,
        selectedCategory: 'Marketing',
        previewHtml: '',
        loadingPreview: false,
        debounceTimer: null,

        init() {
            this.$watch('editingIndex', (val) => {
                if (val !== null) this.updatePreview();
            });
            
            this.$watch('layouts', (val) => {
                if (this.editingIndex !== null) this.debouncedPreview();
            }, { deep: true });
        },

        addNewSection(type) {
            const schema = type.schema || {};
            let settings = {};
            Object.keys(schema).forEach(k => settings[k] = '');
            const typeLabel = type.display_name || type.name;

            this.layouts.push({
                block_instance_id: null,
                block_type_id: type.id,
                name: 'New ' + typeLabel,
                type_name: typeLabel,
                section_key: 'main',
                settings: settings,
                is_shared: false,
                is_new: true,
                starts_at: null,
                expires_at: null,
            });
            this.openTypeSelector = false;
        },

        addSharedBlock(block) {
            this.layouts.push({
                block_instance_id: block.id,
                block_type_id: block.block_type_id,
                name: block.display_name || block.name,
                type_name: block.block_type.display_name || block.block_type.name,
                section_key: 'main',
                settings: block.settings,
                is_shared: true,
                is_new: false,
                starts_at: null,
                expires_at: null,
            });
            this.openSharedSelector = false;
        },

        moveUp(index) {
            if (index <= 0) return;
            const item = this.layouts.splice(index, 1)[0];
            this.layouts.splice(index - 1, 0, item);
        },

        moveDown(index) {
            if (index >= this.layouts.length - 1) return;
            const item = this.layouts.splice(index, 1)[0];
            this.layouts.splice(index + 1, 0, item);
        },

        removeBlock(index) {
            if (confirm('Remove this section from the page?')) {
                this.layouts.splice(index, 1);
            }
        },

        editBlock(index) {
            this.editingIndex = index;
        },

        getSchemaFor(typeId) {
            const type = this.blockTypes.find(t => t.id == typeId);
            return type ? type.schema : {};
        },

        getScheduleLabel(layout) {
            if (!layout.starts_at && !layout.expires_at) return '{{ __('Permanent') }}';
            
            const formatDate = (str) => {
                if (!str) return '';
                const d = new Date(str);
                return d.toLocaleDateString() + ' ' + d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0');
            };

            if (layout.starts_at && layout.expires_at) {
                return formatDate(layout.starts_at) + ' ~ ' + formatDate(layout.expires_at);
            }
            if (layout.starts_at) return formatDate(layout.starts_at) + ' ~';
            return '~ ' + formatDate(layout.expires_at);
        },

        debouncedPreview() {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => this.updatePreview(), 600);
        },

        async updatePreview() {
            if (this.editingIndex === null) return;
            const layout = this.layouts[this.editingIndex];
            
            this.loadingPreview = true;
            try {
                const response = await fetch('{{ route('admin.block-instances.preview') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({
                        block_type_id: layout.block_type_id,
                        settings: JSON.stringify(layout.settings)
                    })
                });
                const data = await response.json();
                this.previewHtml = data.html;
            } catch (e) {
                console.error('Preview failed', e);
            } finally {
                this.loadingPreview = false;
            }
        }
    }
}
</script>
