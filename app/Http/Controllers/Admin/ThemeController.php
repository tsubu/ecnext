<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ThemeController extends Controller
{
    /**
     * Display a listing of the themes.
     */
    public function index()
    {
        $themes = Theme::orderBy('is_active', 'desc')->get();
        return view('admin.themes.index', compact('themes'));
    }

    /**
     * Show the form for creating a new theme.
     */
    public function create()
    {
        return view('admin.themes.create');
    }

    /**
     * Store a newly created theme in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'theme_key' => 'required|string|max:50|unique:themes,theme_key',
            'preview_image' => 'nullable|string',
            'languages' => 'nullable|array',
            'languages.*' => 'string|in:ja,en,fr',
        ]);

        Theme::create($validated);

        return redirect()->route('admin.themes.index')->with('success', __('Theme added successfully.'));
    }

    /**
     * Show the form for editing the specified theme.
     */
    public function edit(Theme $theme)
    {
        return view('admin.themes.edit', compact('theme'));
    }

    /**
     * Update the specified theme in storage.
     */
    public function update(Request $request, Theme $theme)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'theme_key' => 'required|string|max:50|unique:themes,theme_key,' . $theme->id,
            'preview_image' => 'nullable|string',
            'languages' => 'nullable|array',
            'languages.*' => 'string|in:ja,en,fr',
        ]);

        $theme->update($validated);

        return redirect()->route('admin.themes.index')->with('success', __('Theme updated successfully.'));
    }

    /**
     * Activate the specified theme.
     */
    public function activate(Theme $theme)
    {
        DB::transaction(function () use ($theme) {
            // Deactivate all themes
            Theme::where('is_active', true)->update(['is_active' => false]);
            
            // Activate selection
            $theme->update(['is_active' => true]);
        });

        return redirect()->route('admin.themes.index')->with('success', __('Theme ":name" activated.', ['name' => $theme->name]));
    }

    /**
     * Remove the specified theme from storage.
     */
    public function destroy(Theme $theme)
    {
        if ($theme->is_active) {
            return back()->with('error', __('Cannot delete an active theme.'));
        }

        $theme->delete();

        return redirect()->route('admin.themes.index')->with('success', __('Theme removed successfully.'));
    }
}
