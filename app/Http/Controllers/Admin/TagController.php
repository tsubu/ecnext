<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the tags.
     */
    public function index()
    {
        $tags = Tag::latest()->paginate(20);
        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new tag.
     */
    public function create()
    {
        return view('admin.tags.create');
    }

    /**
     * Store a newly created tag in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
            'color' => 'nullable|string|max:7', // Hex color
        ]);

        Tag::create([
            'name' => $validated['name'],
            'color' => $validated['color'] ?? '#64748b',
        ]);

        return redirect()->route('admin.tags.index')->with('success', __('Tag created successfully.'));
    }

    /**
     * Show the form for editing the specified tag.
     */
    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Update the specified tag in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $tag->id,
            'color' => 'nullable|string|max:7',
        ]);

        $tag->update([
            'name' => $validated['name'],
            'color' => $validated['color'] ?? $tag->color,
        ]);

        return redirect()->route('admin.tags.index')->with('success', __('Tag updated successfully.'));
    }

    /**
     * Remove the specified tag from storage.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('admin.tags.index')->with('success', __('Tag deleted successfully.'));
    }
}
