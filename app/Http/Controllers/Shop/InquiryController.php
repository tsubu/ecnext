<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InquiryController extends Controller
{
    /**
     * Display the inquiry form.
     */
    public function index()
    {
        return Inertia::render('Shop/Inquiry', [
            'status' => session('status'),
        ]);
    }

    /**
     * Store a newly created inquiry.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        Inquiry::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'pending',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('inquiry.index')->with('status', __('Your inquiry has been sent successfully. We will get back to you soon.'));
    }
}
