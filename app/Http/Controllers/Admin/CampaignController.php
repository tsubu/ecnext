<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $campaigns = Campaign::latest()->paginate(20);
        return view('admin.campaigns.index', compact('campaigns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('is_active', true)->get();
        return view('admin.campaigns.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'boolean',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');
        
        $campaign = Campaign::create($validated);

        if ($request->has('products')) {
            $campaign->products()->sync($request->products);
        }

        return redirect()->route('admin.campaigns.index')->with('success', __('Campaign created successfully.'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campaign $campaign)
    {
        $products = Product::where('is_active', true)->get();
        $selectedProducts = $campaign->products()->pluck('products.id')->toArray();
        return view('admin.campaigns.edit', compact('campaign', 'products', 'selectedProducts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'boolean',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $campaign->update($validated);

        if ($request->has('products')) {
            $campaign->products()->sync($request->products);
        } else {
            $campaign->products()->detach();
        }

        return redirect()->route('admin.campaigns.index')->with('success', __('Campaign updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign)
    {
        $campaign->delete();
        return redirect()->route('admin.campaigns.index')->with('success', __('Campaign removed successfully.'));
    }
}
