<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    /**
     * Display a listing of the inquiries.
     */
    public function index()
    {
        $inquiries = Inquiry::latest()->paginate(20);
        return view('admin.inquiries.index', compact('inquiries'));
    }

    /**
     * Display the specified inquiry.
     */
    public function show(Inquiry $inquiry)
    {
        return view('admin.inquiries.show', compact('inquiry'));
    }

    /**
     * Update the status of the specified inquiry.
     */
    public function update(Request $request, Inquiry $inquiry)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,replied,closed',
        ]);

        $inquiry->update([
            'status' => $validated['status'],
        ]);

        return redirect()->back()->with('success', __('Inquiry status updated successfully.'));
    }

    /**
     * Remove the specified inquiry from storage.
     */
    public function destroy(Inquiry $inquiry)
    {
        $inquiry->delete();
        return redirect()->route('admin.inquiries.index')->with('success', __('Inquiry deleted successfully.'));
    }
}
