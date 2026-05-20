<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaxRule;
use Illuminate\Http\Request;

class TaxRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $taxRules = TaxRule::latest()->paginate(20);
        return view('admin.settings.tax.index', compact('taxRules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.settings.tax.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge([
            'expires_at' => $request->filled('expires_at') ? $request->input('expires_at') : null,
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0|max:100',
            'starts_at' => 'required|date',
            'expires_at' => 'nullable|date|after:starts_at',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        TaxRule::create($validated);

        return redirect()->route('admin.tax_rules.index')
            ->with('success', __('Tax Rule created successfully.'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaxRule $taxRule)
    {
        return view('admin.settings.tax.edit', compact('taxRule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaxRule $taxRule)
    {
        $request->merge([
            'expires_at' => $request->filled('expires_at') ? $request->input('expires_at') : null,
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0|max:100',
            'starts_at' => 'required|date',
            'expires_at' => 'nullable|date|after:starts_at',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $taxRule->update($validated);

        return redirect()->route('admin.tax_rules.index')
            ->with('success', __('Tax Rule updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaxRule $taxRule)
    {
        // Check if any products are using this rule? 
        // We'll let nullOnDelete handle it, but maybe warn the user.
        $taxRule->delete();

        return redirect()->route('admin.tax_rules.index')
            ->with('success', __('Tax Rule deleted successfully.'));
    }
}
