<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    /**
     * Create an order from cart items.
     */
    public function createOrderFromCart(array $cartData, int $userId, array $addressData, string $paymentMethod): Order
    {
        return DB::transaction(function () use ($cartData, $userId, $addressData, $paymentMethod) {
            // 1. Create Order record
            $order = Order::create([
                'user_id' => $userId,
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'status' => 'pending',
                'total_price' => $cartData['total'],
                'item_total' => $cartData['subtotal'],
                'tax' => $cartData['tax_amount'],
                'shipping_fee' => $cartData['shipping_fee'],
                'payment_fee' => 0,
                'shipping_method_id' => 1, // Default
                'payment_method_id' => 1, // Default
                
                // Reward & Discount
                'coupon_id' => $cartData['coupon']?->id,
                'coupon_discount' => $cartData['discount_amount'],
                'points_used' => $cartData['points_used'],
                'points_discount' => $cartData['points_discount'],
            ]);

            // Update Coupon Usage
            if ($cartData['coupon']) {
                $cartData['coupon']->increment('usage_count');
            }

            // 2. Create Order Items & Deduct Stock
            foreach ($cartData['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['variant']->product_id,
                    'product_variant_id' => $item['variant']->id,
                    'product_name' => $item['variant']->product->name,
                    'sku_code' => $item['variant']->sku,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['price'] * $item['quantity'],
                ]);

                $this->deductStock($item['variant']->product_id, $item['variant']->id, $item['quantity']);
            }

            return $order;
        });
    }

    /**
     * Deduct stock quantity for a product or variant.
     */
    protected function deductStock(int $productId, ?int $variantId, int $quantity): void
    {
        if ($variantId) {
            ProductVariant::where('id', $variantId)->decrement('stock_quantity', $quantity);
        } else {
            Product::where('id', $productId)->decrement('stock_quantity', $quantity);
        }
    }

    /**
     * Update order payment status.
     */
    public function updatePaymentStatus(Order $order, string $status): void
    {
        $order->update(['payment_status' => $status]);
        
        if ($status === 'paid') {
            $order->update(['status' => 'processing']);
        }
    }
}
