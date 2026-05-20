<?php

use App\Models\ProductVariant;
use App\Models\ShopSetting;
use App\Services\Checkout\CartService;
use Illuminate\Support\Facades\Session;

// 1. Setup Shop Settings
$setting = ShopSetting::first() ?? new ShopSetting();
$setting->update([
    'shop_name' => 'Antigravity Store',
    'tax_rate' => 10.0,
    'default_shipping_fee' => 500.0,
    'trade_law_manager' => 'Toshifumi Tsuburaya',
    'trade_law_tel' => '03-1234-5678',
    'trade_law_email' => 'support@example.com'
]);

echo "Shop Setting Updated: Tax={$setting->tax_rate}%, Shipping=¥{$setting->default_shipping_fee}\n";

// 2. Prepare Product with stock
$variant = ProductVariant::first();
$variant->update(['stock_quantity' => 5, 'price' => 1000]);
echo "Product Variant ID {$variant->id}: Price=¥{$variant->price}, Stock=5\n";

// 3. Test Cart Calculation
$cartService = app(CartService::class);
$cartService->clear();

try {
    echo "Adding 3 items to cart...\n";
    $cartService->addItem($variant->id, 3);
    
    $cart = $cartService->getCart();
    echo "Subtotal: ¥" . $cart['subtotal'] . "\n";
    echo "Tax (10%): ¥" . $cart['tax_amount'] . "\n";
    echo "Shipping: ¥" . $cart['shipping_fee'] . "\n";
    echo "Total: ¥" . $cart['total'] . "\n";
    
    echo "Adding 3 more items (Should fail: 3+3 = 6 > 5)...\n";
    $cartService->addItem($variant->id, 3);
} catch (\Exception $e) {
    echo "Expected Failure: " . $e->getMessage() . "\n";
}
