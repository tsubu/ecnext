<?php

namespace App\Services\Tax;

use App\Models\Product;
use App\Models\TaxRule;
use App\Models\ShopSetting;
use Illuminate\Support\Carbon;

class TaxService
{
    /**
     * Get the active tax rate and type for a specific product.
     * 
     * @param Product $product
     * @param Carbon|null $date
     * @return array [rate, type]
     */
    public function getTaxForProduct(Product $product, ?Carbon $date = null): array
    {
        $date = $date ?? Carbon::now();
        $setting = ShopSetting::first();

        // 1. Determine the rate
        $rate = $this->resolveRate($product, $date, $setting);

        // 2. Determine the tax type (inclusive/exclusive)
        $type = $this->resolveType($product, $setting);

        return [
            'rate' => (float) $rate,
            'type' => $type, // 'inclusive' or 'exclusive'
        ];
    }

    /**
     * Resolve the numerical tax rate based on priority:
     * Product Override -> Active Period Rule -> Global Default
     */
    private function resolveRate(Product $product, Carbon $date, ?ShopSetting $setting): float
    {
        // A. Individual product rate override (Explicitly set on product)
        if ($product->individual_tax_rate !== null) {
            return (float) $product->individual_tax_rate;
        }

        // B. Product-assigned tax rule (Active for the given period)
        if ($product->tax_rule_id) {
            $assignedRule = TaxRule::where('id', $product->tax_rule_id)->active($date)->first();
            if ($assignedRule) {
                return (float) $assignedRule->rate;
            }
        }

        // C. Global Active Period Rule (First active rule without a product assignment logic usually is global fallback)
        // But in our case, we'll check for any active "Global" rules (those not specifically assigned but active)
        $globalRule = TaxRule::active($date)->first();
        if ($globalRule) {
            return (float) $globalRule->rate;
        }

        // D. Final Fallback: Shop setting tax_rate
        return $setting ? (float) $setting->tax_rate : 10.0;
    }

    /**
     * Resolve the tax type based on priority:
     * Product Style -> Global Style
     */
    private function resolveType(Product $product, ?ShopSetting $setting): string
    {
        // Product specific type override
        if ($product->tax_type !== 'inherit') {
            return $product->tax_type;
        }

        // Global default
        return $setting ? $setting->global_tax_type : 'exclusive';
    }

    /**
     * Calculate tax amount for a given price, rate, and type.
     */
    public function calculateTax(float $price, float $rate, string $type): float
    {
        if ($type === 'inclusive') {
            // Price is 110 (if 10% tax). Total * (rate / (100 + rate))
            return $price * ($rate / (100 + rate));
        }

        // Exclusive: Price is 100. Price * (rate / 100)
        return $price * ($rate / 100);
    }
}
