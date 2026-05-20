<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageCategory;
use Illuminate\Http\Request;

class PageCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = PageCategory::orderBy('sort_order')->get();
        return view('admin.page-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.page-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:page_categories,slug',
            'description' => 'nullable|string',
            'sort_order' => 'integer',
        ]);

        PageCategory::create($validated);

        return redirect()->route('admin.page-categories.index')->with('success', __('Category created successfully.'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PageCategory $page_category)
    {
        return view('admin.page-categories.edit', compact('page_category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PageCategory $page_category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:page_categories,slug,' . $page_category->id,
            'description' => 'nullable|string',
            'sort_order' => 'integer',
        ]);

        $page_category->update($validated);

        return redirect()->route('admin.page-categories.index')->with('success', __('Category updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PageCategory $page_category)
    {
        if ($page_category->pages()->count() > 0) {
            return redirect()->back()->with('error', __('Category cannot be deleted as it contains pages.'));
        }

        $page_category->delete();
        return redirect()->route('admin.page-categories.index')->with('success', __('Category deleted successfully.'));
    }
}
