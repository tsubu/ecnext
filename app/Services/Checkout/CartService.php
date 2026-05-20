<?php

namespace App\Services\Checkout;

use App\Models\ProductVariant;
use App\Models\Coupon;
use App\Models\ShopSetting;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const SESSION_KEY = 'ec_cart';
    private const COUPON_KEY = 'ec_coupon_id';
    private const POINTS_KEY = 'ec_points_used';

    /**
     * Add an item to the cart.
     */
    public function addItem(int $variantId, int $quantity = 1): void
    {
        $variant = ProductVariant::findOrFail($variantId);
        
        // Inventory check
        $cart = Session::get(self::SESSION_KEY, []);
        $currentQuantityInCart = isset($cart[$variantId]) ? $cart[$variantId]['quantity'] : 0;
        
        if ($variant->stock_quantity < ($currentQuantityInCart + $quantity)) {
            throw new \Exception(__('Insufficient stock available.'));
        }

        if (isset($cart[$variantId])) {
            $cart[$variantId]['quantity'] += $quantity;
        } else {
            $cart[$variantId] = [
                'id' => $variantId,
                'quantity' => $quantity,
            ];
        }

        Session::put(self::SESSION_KEY, $cart);
    }

    /**
     * Update the quantity of an item in the cart.
     */
    public function updateQuantity(int $variantId, int $quantity): void
    {
        $cart = Session::get(self::SESSION_KEY, []);

        if ($quantity <= 0) {
            unset($cart[$variantId]);
        } elseif (isset($cart[$variantId])) {
            $cart[$variantId]['quantity'] = $quantity;
        }

        Session::put(self::SESSION_KEY, $cart);
    }

    /**
     * Remove an item from the cart.
     */
    public function removeItem(int $variantId): void
    {
        $cart = Session::get(self::SESSION_KEY, []);
        unset($cart[$variantId]);
        Session::put(self::SESSION_KEY, $cart);
    }

    /**
     * Get the cart data with pricing calculations.
     */
    public function getCart(): array
    {
        $sessionData = Session::get(self::SESSION_KEY, []);
        $items = [];
        $subtotal = 0;
        $totalTaxAmount = 0;
        
        $taxService = app(\App\Services\Tax\TaxService::class);
        $setting = ShopSetting::first();

        foreach ($sessionData as $id => $data) {
            $variant = ProductVariant::with(['product', 'product.images'])->find($id);
            
            if ($variant) {
                // 1. Base Price (Active price from campaigns/sales)
                $price = (float) $variant->active_price;
                $lineQuantityTotal = $price * $data['quantity'];
                
                // 2. Resolve Tax for this Item
                $taxInfo = $taxService->getTaxForProduct($variant->product);
                $taxRate = $taxInfo['rate'];
                $taxType = $taxInfo['type'];
                
                // 3. Calculate Item Tax
                $itemTax = $taxService->calculateTax($price, $taxRate, $taxType);
                $lineTaxTotal = $itemTax * $data['quantity'];
                
                // 4. Update Totals
                // If exclusive, the subtotal is just the base price sum.
                // If inclusive, the subtotal already includes tax, but for consistent reporting 
                // we often treat "Subtotal" as prices before additional tax.
                // Here, we'll keep subtotal as the sum of "Price as entered" for simplicity.
                $subtotal += $lineQuantityTotal;
                $totalTaxAmount += $lineTaxTotal;

                $items[] = [
                    'variant' => $variant,
                    'quantity' => $data['quantity'],
                    'price' => $price,
                    'tax_rate' => $taxRate,
                    'tax_type' => $taxType,
                    'line_total' => $lineQuantityTotal,
                    'line_tax' => $lineTaxTotal,
                ];
            }
        }

        $coupon = $this->getAppliedCoupon();
        $discountLabel = '';
        $discountAmount = 0;

        if ($coupon) {
            if ($coupon->isValid() && ($coupon->min_order_amount === null || $subtotal >= $coupon->min_order_amount)) {
                if ($coupon->discount_type === 'percentage') {
                    $discountAmount = $subtotal * ($coupon->discount_value / 100);
                    $discountLabel = $coupon->code . " (" . $coupon->discount_value . "%)";
                } else {
                    $discountAmount = $coupon->discount_value;
                    $discountLabel = $coupon->code . " (" . $this->formatCurrency($coupon->discount_value) . ")";
                }
                
                // Cap discount at subtotal
                $discountAmount = min($discountAmount, $subtotal);
            } else {
                $this->removeCoupon();
                $coupon = null;
            }
        }

        // Apply discount proportionally to tax? (Standard practice varies)
        // For now, we'll keep it simple: discount reduces the total before tax if exclusive.
        // But since we calculate tax per line now, we should ideally apply discount to lines.
        // Simplified: reduce tax amount by the effective total discount percentage.
        $taxReductionFactor = $subtotal > 0 ? (1 - ($discountAmount / $subtotal)) : 1;
        $totalTaxAmount *= $taxReductionFactor;

        $shippingFee = $setting ? (float) $setting->default_shipping_fee : 0.0;
        
        // Final Total Calculation:
        // Core Logic: 
        // If tax is inclusive, tax is already in $subtotal, so we don't add $totalTaxAmount.
        // If tax is exclusive, we add it.
        // Wait, the TaxService handles this per line.
        // So we need to sum "Add on" tax only for exclusive lines.
        
        $addSubtractTax = 0;
        foreach($items as $item) {
            if ($item['tax_type'] === 'exclusive') {
                $addSubtractTax += ($item['line_tax'] * $taxReductionFactor);
            }
        }
        
        $totalWithTaxAndShipping = ($subtotal - $discountAmount) + $addSubtractTax + $shippingFee;

        // 5. Points Redemption
        $pointsUsed = Session::get(self::POINTS_KEY, 0);
        $pointsDiscount = 0;
        if ($pointsUsed > 0) {
            // Use Point Conversion Rate (Currency units per Point)
            $conversionRate = $setting ? (float) $setting->point_conversion_rate : 1.0;
            $pointsDiscount = $pointsUsed * $conversionRate;
            
            // Cap points at the remaining total
            $pointsDiscount = min($pointsDiscount, $totalWithTaxAndShipping);
            $totalWithTaxAndShipping -= $pointsDiscount;
        }

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'discount_label' => $discountLabel,
            'coupon' => $coupon,
            'tax_amount' => $totalTaxAmount,
            'shipping_fee' => $shippingFee,
            'points_used' => $pointsUsed,
            'points_discount' => $pointsDiscount,
            'total' => max(0, $totalWithTaxAndShipping),
            'count' => count($items),
        ];

    }

    /**
     * Format currency based on shop settings.
     */
    private function formatCurrency(float $amount): string
    {
        $setting = ShopSetting::first();
        $code = $setting->currency_code ?? 'JPY';
        
        if ($code === 'JPY') {
            return '¥' . number_format($amount);
        }
        
        return $code . ' ' . number_format($amount, 2);
    }

    /**
     * Apply a coupon by code.
     */
    public function applyCoupon(string $code): bool
    {
        $coupon = Coupon::where('code', $code)->where('is_active', true)->first();
        
        if (!$coupon || !$coupon->isValid()) {
            return false;
        }

        Session::put(self::COUPON_KEY, $coupon->id);
        return true;
    }

    /**
     * Remove the applied coupon.
     */
    public function removeCoupon(): void
    {
        Session::forget(self::COUPON_KEY);
    }

    /**
     * Apply points for discount.
     */
    public function applyPoints(float $amount): bool
    {
        $user = auth()->user();
        if (!$user || $user->points < $amount || $amount < 0) {
            return false;
        }

        Session::put(self::POINTS_KEY, $amount);
        return true;
    }

    /**
     * Remove the applied points.
     */
    public function removePoints(): void
    {
        Session::forget(self::POINTS_KEY);
    }

    /**
     * Get the currently applied coupon model.
     */
    public function getAppliedCoupon(): ?Coupon
    {
        $id = Session::get(self::COUPON_KEY);
        return $id ? Coupon::find($id) : null;
    }

    /**
     * Clear the cart.
     */
    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
        Session::forget(self::COUPON_KEY);
        Session::forget(self::POINTS_KEY);
    }
}
