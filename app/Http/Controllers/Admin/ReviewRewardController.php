<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReviewRewardSetting;
use App\Models\Coupon;
use Illuminate\Http\Request;

class ReviewRewardController extends Controller
{
    public function edit()
    {
        $setting = ReviewRewardSetting::firstOrCreate([], [
            'min_rating' => 3,
            'reward_type' => 'point',
            'reward_value' => 0,
            'is_active' => false,
        ]);
        
        $coupons = Coupon::where('is_unique', false)->get();
            
        return view('admin.marketing.reviews.settings', compact('setting', 'coupons'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'min_rating' => 'required|integer|min:1|max:5',
            'reward_type' => 'required|in:coupon,point',
            'reward_value' => 'required|numeric|min:0',
            'coupon_expiry_days' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);
        
        $setting = ReviewRewardSetting::first();
        $setting->update([
            'min_rating' => $validated['min_rating'],
            'reward_type' => $validated['reward_type'],
            'reward_value' => $validated['reward_value'],
            'coupon_expiry_days' => $validated['coupon_expiry_days'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);
        
        return back()->with('success', __('Settings updated successfully.'));
    }
}
