<?php

namespace App\Services\Marketing;

use App\Models\ProductReview;
use App\Models\Order;
use App\Models\ReviewRewardSetting;
use App\Models\Coupon;
use App\Services\User\PointService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReviewService
{
    public function __construct(
        protected PointService $pointService
    ) {}

    /**
     * Check if a user can review a specific product.
     */
    public function canUserReviewProduct($userId, $productId, $orderId = null)
    {
        $query = Order::where('user_id', $userId)
            ->where('status', 'completed')
            ->whereHas('items', function ($q) use ($productId) {
                // Handle both product variants and main products
                $q->whereHas('variant', function ($qv) use ($productId) {
                    $qv->where('product_id', $productId);
                });
            });

        if ($orderId) {
            $query->where('id', $orderId);
        }

        $order = $query->first();

        if (!$order) {
            return false;
        }

        // Check if already reviewed for this order/product
        $existing = ProductReview::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('order_id', $order->id)
            ->exists();

        return !$existing;
    }

    /**
     * Submit a new review.
     */
    public function submitReview(array $data)
    {
        if (!$this->canUserReviewProduct($data['user_id'], $data['product_id'], $data['order_id'] ?? null)) {
            throw new \Exception('You are not eligible to review this product.');
        }

        return ProductReview::create([
            'user_id' => $data['user_id'],
            'product_id' => $data['product_id'],
            'order_id' => $data['order_id'],
            'rating' => $data['rating'],
            'comment' => $data['comment'],
            'is_approved' => false, // Always pending by default
        ]);
    }

    public function approveReview(ProductReview $review)
    {
        return DB::transaction(function () use ($review) {
            if ($review->is_approved) {
                return $review;
            }

            $review->update(['is_approved' => true]);

            $setting = ReviewRewardSetting::where('is_active', true)->first();
            if ($setting && $review->rating >= $setting->min_rating) {
                $delay = (int) ($setting->reward_delay_days ?? 7);
                
                // Base date is now order shipping date
                $baseDate = $review->order?->shipments()->latest('shipped_at')->first()?->shipped_at 
                           ?? $review->order?->created_at 
                           ?? now();
                
                $scheduledAt = $baseDate->addDays($delay);

                if ($scheduledAt <= now()) {
                    $this->processReward($review, $setting);
                } else {
                    $review->update([
                        'scheduled_reward_at' => $scheduledAt
                    ]);
                }
            }

            return $review;
        });
    }

    /**
     * Process rewards that are scheduled and due for issuance.
     */
    public function processScheduledRewards()
    {
        $dueReviews = ProductReview::where('is_approved', true)
            ->whereNotNull('scheduled_reward_at')
            ->whereNull('reward_issued_at')
            ->where('scheduled_reward_at', '<=', now())
            ->get();

        $count = 0;
        $setting = ReviewRewardSetting::where('is_active', true)->first();
        
        if (!$setting) {
            return 0;
        }

        foreach ($dueReviews as $review) {
            try {
                $this->processReward($review, $setting);
                $count++;
            } catch (\Exception $e) {
                \Log::error("Failed to issue scheduled reward for review {$review->id}: " . $e->getMessage());
            }
        }

        return $count;
    }

    /**
     * Process reward logic based on setting.
     */
    protected function processReward(ProductReview $review, ?ReviewRewardSetting $setting = null)
    {
        if (!$setting) {
            $setting = ReviewRewardSetting::where('is_active', true)->first();
        }

        if (!$setting || $review->rating < $setting->min_rating) {
            return;
        }

        if ($setting->reward_type === 'point') {
            $this->pointService->addPoints(
                $review->user,
                $setting->reward_value,
                'Product review reward: ' . $review->product->name,
                $review
            );
        } elseif ($setting->reward_type === 'coupon') {
            $template = Coupon::find($setting->reward_value);
            if ($template) {
                $this->issueUniqueCoupon($review->user, $template, $setting->coupon_expiry_days);
            }
        }

        $review->update(['reward_issued_at' => now()]);
    }

    /**
     * Issue a unique one-time coupon to a user.
     */
    protected function issueUniqueCoupon($user, Coupon $template, $expiryDays)
    {
        return Coupon::create([
            'user_id' => $user->id,
            'code' => strtoupper(Str::random(10)),
            'name' => $template->name . ' (Review Reward)',
            'discount_type' => $template->discount_type,
            'discount_value' => $template->discount_value,
            'min_order_amount' => $template->min_order_amount,
            'starts_at' => now(),
            'expires_at' => $expiryDays ? now()->addDays($expiryDays) : $template->expires_at,
            'usage_limit' => 1,
            'usage_count' => 0,
            'is_active' => true,
            'is_unique' => true,
        ]);
    }
}
