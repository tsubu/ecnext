<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockType;
use Illuminate\Http\Request;

class BlockTypeController extends Controller
{
    /**
     * Display a listing of the block types (components).
     */
    public function index()
    {
        $types = BlockType::orderBy('is_system', 'desc')->orderBy('name')->get();
        return view('admin.block-types.index', compact('types'));
    }

    /**
     * Show the form for creating a new block type.
     */
    public function create()
    {
        return view('admin.block-types.create');
    }

    /**
     * Store a newly created block type in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type_key' => 'required|string|max:50|unique:block_types,type_key',
            'schema' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);

        $schema = null;
        if (!empty($validated['schema'])) {
            $schema = json_decode($validated['schema'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['schema' => 'Invalid JSON schema format.'])->withInput();
            }
        }

        BlockType::create([
            'name' => $validated['name'],
            'type_key' => $validated['type_key'],
            'schema' => $schema,
            'icon' => $validated['icon'] ?? 'cube',
            'is_system' => false,
        ]);

        return redirect()->route('admin.block-center.index')->with('success', __('Template created successfully.'));
    }

    /**
     * Show the form for editing the specified block type.
     */
    public function edit(BlockType $block_type)
    {
        return view('admin.block-types.edit', compact('block_type'));
    }

    /**
     * Update the specified block type in storage.
     */
    public function update(Request $request, BlockType $block_type)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'schema' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);

        $schema = null;
        if (!empty($validated['schema'])) {
            $schema = json_decode($validated['schema'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['schema' => 'Invalid JSON schema format.'])->withInput();
            }
        }

        $block_type->update([
            'name' => $validated['name'],
            'schema' => $schema,
            'icon' => $validated['icon'] ?? $block_type->icon,
        ]);

        return redirect()->route('admin.block-center.index')->with('success', __('Template updated successfully.'));
    }

    /**
     * Remove the specified block type from storage.
     */
    public function destroy(BlockType $block_type)
    {
        if ($block_type->is_system) {
            return back()->with('error', __('System components cannot be deleted.'));
        }

        if ($block_type->instances()->exists()) {
            return back()->with('error', __('Cannot delete component type that has active instances.'));
        }

        $block_type->delete();

        return redirect()->route('admin.block-center.index')->with('success', __('Template removed successfully.'));
    }
}
