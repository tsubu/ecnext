<?php

namespace App\Services\Checkout;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderAddress;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    /**
     * Create a new order from cart data and shipping information.
     *
     * @param int $userId
     * @param array $cartData
     * @param array $shippingInfo
     * @return Order
     * @throws \Exception
     */
    public function createOrderFromCart(int $userId, array $cartData, array $shippingInfo): Order
    {
        return DB::transaction(function () use ($userId, $cartData, $shippingInfo) {
            // 1. Create Order Master
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'user_id' => $userId,
                'total_price' => $cartData['total'],
                'item_total' => $cartData['subtotal'],
                'shipping_fee' => 0, // Simplified for now
                'payment_fee' => 0,
                'tax' => 0,
                'status' => 'new',
                'notes' => $shippingInfo['notes'] ?? null,
            ]);

            // 2. Create Order Items (Snapshots) and Update Stock
            foreach ($cartData['items'] as $item) {
                $variant = $item['variant'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $variant->id,
                    'product_name' => $variant->product->name,
                    'variant_sku' => $variant->sku,
                    'quantity' => $data['quantity'] ?? $item['quantity'],
                    'price' => $variant->price,
                    'tax' => 0,
                ]);

                // Stock decrement logic
                $variant->decrement('stock_quantity', $item['quantity']);
            }

            // 3. Create Shipping Address (Snapshot)
            OrderAddress::create([
                'order_id' => $order->id,
                'type' => 'shipping',
                'first_name' => $shippingInfo['first_name'],
                'last_name' => $shippingInfo['last_name'],
                'email' => $shippingInfo['email'] ?? null,
                'phone' => $shippingInfo['phone'] ?? null,
                'user_zip' => $shippingInfo['zip'],
                'user_address1' => $shippingInfo['address1'],
                'user_address2' => $shippingInfo['address2'] ?? null,
                'user_pref' => $shippingInfo['pref'] ?? null,
            ]);

            return $order;
        });
    }

    /**
     * Update the status of an existing order.
     *
     * @param int $orderId
     * @param string $status
     * @return bool
     */
    public function updateStatus(int $orderId, string $status): bool
    {
        $order = Order::findOrFail($orderId);
        return $order->update(['status' => $status]);
    }
}
