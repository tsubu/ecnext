<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockInstance;
use App\Models\BlockType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BlockInstanceController extends Controller
{
    /**
     * Display a listing of the block instances (concrete parts).
     */
    public function index(Request $request)
    {
        $query = BlockInstance::with('blockType');

        if ($request->has('block_type_id')) {
            $query->where('block_type_id', $request->block_type_id);
        }

        $instances = $query->latest()->paginate(20);
        $blockTypes = BlockType::all();

        return view('admin.block-instances.index', compact('instances', 'blockTypes'));
    }

    /**
     * Show the form for creating a new block instance.
     */
    public function create()
    {
        $blockTypes = BlockType::all();
        $presets = \App\Models\BlockPreset::all();

        return view('admin.block-instances.create', compact('blockTypes', 'presets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_locales' => 'nullable|array',
            'name_locales.*' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:block_instances,slug',
            'block_type_id' => 'required|exists:block_types,id',
            'settings' => 'nullable|string',
            'settings_locales' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($err = $this->validateSettingsLocalesJson($request->input('settings_locales'))) {
            return back()->withErrors(['settings_locales' => $err])->withInput();
        }

        $settings = null;
        if (! empty($validated['settings'])) {
            $settings = json_decode($validated['settings'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['settings' => 'Invalid JSON settings format.'])->withInput();
            }
        }

        $settingsLocales = $this->decodeSettingsLocales($request->input('settings_locales'));

        BlockInstance::create([
            'name' => $validated['name'],
            'name_locales' => $this->normalizeNameLocales($request->input('name_locales')),
            'slug' => $validated['slug'],
            'block_type_id' => $validated['block_type_id'],
            'settings' => $settings,
            'settings_locales' => $settingsLocales,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.block-center.index')->with('success', __('Block created successfully.'));
    }

    /**
     * Show the form for editing the specified block instance.
     */
    public function edit(BlockInstance $block_instance)
    {
        $blockTypes = BlockType::all();
        $presets = \App\Models\BlockPreset::all();

        return view('admin.block-instances.edit', compact('block_instance', 'blockTypes', 'presets'));
    }

    public function update(Request $request, BlockInstance $block_instance)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_locales' => 'nullable|array',
            'name_locales.*' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:block_instances,slug,' . $block_instance->id,
            'block_type_id' => 'required|exists:block_types,id',
            'settings' => 'nullable|string',
            'settings_locales' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($err = $this->validateSettingsLocalesJson($request->input('settings_locales'))) {
            return back()->withErrors(['settings_locales' => $err])->withInput();
        }

        $settings = null;
        if (! empty($validated['settings'])) {
            $settings = json_decode($validated['settings'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['settings' => 'Invalid JSON settings format.'])->withInput();
            }
        }

        $settingsLocales = $this->decodeSettingsLocales($request->input('settings_locales'));

        $block_instance->update([
            'name' => $validated['name'],
            'name_locales' => $this->normalizeNameLocales($request->input('name_locales')),
            'slug' => $validated['slug'],
            'block_type_id' => $validated['block_type_id'],
            'settings' => $settings,
            'settings_locales' => $settingsLocales,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.block-center.index')->with('success', __('Block updated successfully.'));
    }

    public function destroy(BlockInstance $block_instance)
    {
        if ($block_instance->layouts()->exists()) {
            return back()->with('error', __('Cannot delete block that is currently assigned to a page layout.'));
        }

        $block_instance->delete();

        return redirect()->route('admin.block-center.index')->with('success', __('Block removed successfully.'));
    }

    /**
     * Preview a block instance rendering.
     */
    public function preview(Request $request)
    {
        $validated = $request->validate([
            'block_type_id' => 'required|exists:block_types,id',
            'settings' => 'nullable|string',
            'settings_locales' => 'nullable|string',
            'preview_locale' => ['nullable', 'string', 'max:32', Rule::in(block_configured_locales())],
        ]);

        $type = BlockType::findOrFail($validated['block_type_id']);
        $base = json_decode($validated['settings'] ?? '{}', true) ?: [];
        $locales = $this->decodeSettingsLocales($validated['settings_locales'] ?? null);
        $locale = $validated['preview_locale'] ?: app()->getLocale();
        if (! in_array($locale, block_configured_locales(), true)) {
            $locale = block_configured_locales()[0] ?? app()->getLocale();
        }

        $proto = new BlockInstance([
            'settings' => $base,
            'settings_locales' => is_array($locales) && $locales !== [] ? $locales : null,
        ]);
        $settings = $proto->resolvedSettings($locale);

        try {
            $instance = new BlockInstance([
                'block_type_id' => $validated['block_type_id'],
                'name' => 'Preview',
                'settings' => $settings,
            ]);
            $instance->setRelation('blockType', $type);

            $html = view('components.shop.renderer', [
                'instance' => $instance,
                'settings' => [],
                'is_preview' => true,
            ])->render();

            return response()->json(['html' => $html]);
        } catch (\Exception $e) {
            return response()->json([
                'html' => '<div class="p-8 bg-rose-50 rounded-3xl text-rose-500 font-mono text-xs overflow-auto">' .
                          'Error Rendering: ' . e($e->getMessage()) . '</div>',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @param  array<string, mixed>|null  $input
     * @return array<string, string>|null
     */
    private function normalizeNameLocales(?array $input): ?array
    {
        if ($input === null || $input === []) {
            return null;
        }
        $allowed = array_flip(block_configured_locales());
        $out = [];
        foreach ($input as $k => $v) {
            $key = (string) $k;
            if (! isset($allowed[$key])) {
                continue;
            }
            if ($v === null || $v === '') {
                continue;
            }
            $out[$key] = (string) $v;
        }

        return $out === [] ? null : $out;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function decodeSettingsLocales(?string $json): ?array
    {
        if ($json === null || trim($json) === '') {
            return null;
        }
        $decoded = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        return is_array($decoded) ? $decoded : null;
    }

    private function validateSettingsLocalesJson(?string $json): ?string
    {
        if ($json === null || trim($json) === '') {
            return null;
        }
        json_decode($json);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return __('Invalid JSON in locale overrides.');
        }

        return null;
    }
}
