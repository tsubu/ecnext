<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageCategory;
use App\Models\BlockInstance;
use App\Models\PageLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Services\CmsService;

class PageController extends Controller
{
    protected CmsService $cmsService;

    public function __construct(CmsService $cmsService)
    {
        $this->cmsService = $cmsService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Page::with('category');

        if ($request->has('category_id')) {
            $query->where('page_category_id', $request->category_id);
        }

        $pages = $query->latest()->paginate(20);
        $categories = PageCategory::all();

        return view('admin.pages.index', compact('pages', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = PageCategory::all();
        return view('admin.pages.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:pages,slug',
            'page_category_id' => 'nullable|exists:page_categories,id',
            'type' => 'required|in:default,legal',
            'content' => 'nullable|string',
            'legal_data' => 'nullable|array',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'expired_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $validated['is_published'] = $request->has('is_published');
        
        Page::create($validated);

        return redirect()->route('admin.pages.index')->with('success', __('Page created successfully.'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        $categories = PageCategory::all();
        $blockInstances = BlockInstance::with('blockType')->shared()->where('is_active', true)->get();
        $blockTypes = \App\Models\BlockType::all();
        $assignedLayouts = $page->layouts()->with('blockInstance.blockType')->orderBy('sort_order')->get();

        return view('admin.pages.edit', compact('page', 'categories', 'blockInstances', 'blockTypes', 'assignedLayouts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:pages,slug,' . $page->id,
            'page_category_id' => 'nullable|exists:page_categories,id',
            'type' => 'required|in:default,legal',
            'content' => 'nullable|string',
            'legal_data' => 'nullable|array',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'expired_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $validated['is_published'] = $request->has('is_published');

        $page->update($validated);

        // Sync Layouts
        if ($request->has('layout')) {
            $this->cmsService->syncLayouts($page, $request->layout);
        }

        return redirect()->route('admin.pages.index')->with('success', __('Page updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        if ($page->is_system) {
            return redirect()->back()->with('error', __('System pages cannot be deleted.'));
        }

        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', __('Page deleted successfully.'));
    }
}
