<?php

use App\Models\ProductReview;
use App\Models\ReviewRewardSetting;
use App\Models\User;
use App\Services\Marketing\ReviewService;
use Illuminate\Support\Facades\Artisan;

// 1. Setup Setting
$setting = ReviewRewardSetting::first();
$setting->update([
    'is_active' => true,
    'reward_delay_days' => 1, // 1 day delay
    'min_rating' => 1,
    'reward_type' => 'point',
    'reward_value' => 100
]);

echo "Setting Delay: " . $setting->reward_delay_days . " days\n";

// 2. Create a review
$user = User::first();
$review = ProductReview::create([
    'user_id' => $user->id,
    'product_id' => 1,
    'rating' => 5,
    'comment' => 'Test delayed reward',
    'is_approved' => false
]);

echo "Created Review ID: " . $review->id . "\n";

// 3. Approve it
$service = app(ReviewService::class);
$service->approveReview($review);

$review->refresh();
echo "Approved: " . ($review->is_approved ? 'Yes' : 'No') . "\n";
echo "Scheduled At: " . $review->scheduled_reward_at . "\n";
echo "Issued At: " . ($review->reward_issued_at ?: 'Pending') . "\n";

// 4. Manipulate time and run command
$review->update(['scheduled_reward_at' => now()->subDay()]);
echo "Simulated: scheduled_reward_at set to 1 day ago\n";

Artisan::call('marketing:process-rewards');
echo Artisan::output();

$review->refresh();
echo "Issued At after command: " . ($review->reward_issued_at ?: 'Still Pending') . "\n";
echo "User points updated: " . $user->refresh()->points . "\n";
