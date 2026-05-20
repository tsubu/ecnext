<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    /**
     * Display a listing of the notices.
     */
    public function index()
    {
        $notices = Notice::latest()->paginate(20);
        return view('admin.notices.index', compact('notices'));
    }

    /**
     * Show the form for creating a new notice.
     */
    public function create()
    {
        return view('admin.notices.create');
    }

    /**
     * Store a newly created notice in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'published_at' => 'nullable|date',
            'expired_at' => 'nullable|date|after_or_equal:published_at',
            'is_active' => 'boolean',
        ]);

        Notice::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'published_at' => $validated['published_at'] ?? now(),
            'expired_at' => $validated['expired_at'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.notices.index')->with('success', __('Notice created successfully.'));
    }

    /**
     * Show the form for editing the specified notice.
     */
    public function edit(Notice $notice)
    {
        return view('admin.notices.edit', compact('notice'));
    }

    /**
     * Update the specified notice in storage.
     */
    public function update(Request $request, Notice $notice)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'published_at' => 'nullable|date',
            'expired_at' => 'nullable|date|after_or_equal:published_at',
            'is_active' => 'boolean',
        ]);

        $notice->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'published_at' => $validated['published_at'] ?? $notice->published_at,
            'expired_at' => $validated['expired_at'] ?? $notice->expired_at,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.notices.index')->with('success', __('Notice updated successfully.'));
    }

    /**
     * Remove the specified notice from storage.
     */
    public function destroy(Notice $notice)
    {
        $notice->delete();
        return redirect()->route('admin.notices.index')->with('success', __('Notice deleted successfully.'));
    }
}
