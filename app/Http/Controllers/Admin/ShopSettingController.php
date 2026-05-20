<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopSetting;
use Illuminate\Http\Request;

class ShopSettingController extends Controller
{
    /**
     * Show the form for editing the shop settings.
     */
    public function edit()
    {
        $setting = ShopSetting::first() ?? new ShopSetting();
        return view('admin.settings.shop', compact('setting'));
    }

    /**
     * Update the shop settings in storage.
     */
    public function update(Request $request)
    {
        $setting = ShopSetting::first();
        
        if (!$setting) {
            $setting = new ShopSetting();
        }

        $validated = $request->validate([
            'shop_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'postal_code' => 'nullable|string|max:10',
            'address1' => 'nullable|string|max:255',
            'address2' => 'nullable|string|max:255',
            'timezone' => 'required|string|max:50',
            'country_code' => 'required|string|size:2',
            'currency_code' => 'required|string|size:3',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'global_tax_type' => 'required|in:inclusive,exclusive',
            'points_enabled' => 'nullable|boolean',
            'point_rate' => 'nullable|numeric|min:0|max:100',
            'point_conversion_rate' => 'nullable|numeric|min:0',
            'trade_law_manager' => 'nullable|string|max:255',
            'trade_law_address' => 'nullable|string|max:255',
            'trade_law_tel' => 'nullable|string|max:20',
            'trade_law_email' => 'nullable|email|max:255',
            'trade_law_price_info' => 'nullable|string',
            'trade_law_payment_methods' => 'nullable|string',
            'trade_law_delivery_info' => 'nullable|string',
            'trade_law_return_policy' => 'nullable|string',
        ]);

        // Fix for boolean toggle
        $validated['points_enabled'] = $request->has('points_enabled');

        $setting->fill($validated);
        $setting->save();

        return redirect()->route('admin.settings.shop.edit')->with('success', __('Shop settings updated successfully.'));
    }
}
