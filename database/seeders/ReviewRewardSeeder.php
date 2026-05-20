<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductReview;
use App\Models\ReviewRewardSetting;

class ReviewRewardSeeder extends Seeder
{
    public function run()
    {
        // 1. Ensure a user exists
        $user = User::first();
        if (!$user) {
             $user = User::create([
                 'name' => 'Test User',
                 'email' => 'test@example.com',
                 'password' => \Illuminate\Support\Facades\Hash::make('password'),
                 'points' => 0,
             ]);
        }
        $user->update(['points' => 5000]);

        // 2. Create some approved reviews
        $products = Product::take(3)->get();
        foreach ($products as $product) {
            ProductReview::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'rating' => rand(4, 5),
                'comment' => 'This is an excellent gaming peripheral! Neural sync is flawless.',
                'is_approved' => true,
            ]);
        }

        // 3. Create a pending review
        ProductReview::create([
            'user_id' => $user->id,
            'product_id' => $products->first()->id,
            'rating' => 5,
            'comment' => 'Just received it. Amazing build quality!',
            'is_approved' => false,
        ]);

        // 4. Create a completed order for the user so they are eligible to review more
        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'TEST-ORD-001',
            'status' => 'completed',
            'total_price' => 12000,
            'item_total' => 12000,
            'tax' => 0,
            'payment_method_id' => 1,
            'shipping_method_id' => 1,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $products->last()->id,
            'product_variant_id' => $products->last()->variants->first()?->id,
            'product_name' => $products->last()->name,
            'sku_code' => $products->last()->sku,
            'quantity' => 1,
            'unit_price' => 12000,
            'total_price' => 12000,
        ]);

        // 5. Initialize Reward Settings
        ReviewRewardSetting::updateOrCreate(['id' => 1], [
            'min_rating' => 3,
            'reward_type' => 'point',
            'reward_value' => 100,
            'is_active' => true,
        ]);
    }
}
