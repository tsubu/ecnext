<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Services\Checkout\CartService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    /**
     * Display the cart contents.
     */
    public function index()
    {
        return Inertia::render('Shop/Cart', [
            'cart' => $this->cartService->getCart(),
        ]);
    }

    /**
     * Add an item to the cart.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $this->cartService->addItem(
            $validated['product_id'],
            $validated['product_variant_id'] ?? null,
            $validated['quantity']
        );

        return redirect()->back()->with('success', '商品をカートに追加しました。');
    }

    /**
     * Update item quantity in the cart.
     */
    public function update(Request $request, $productId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'product_variant_id' => 'nullable|integer',
        ]);

        $this->cartService->addItem($productId, $validated['product_variant_id'] ?? null, $validated['quantity']);

        return redirect()->back();
    }

    /**
     * Remove an item from the cart.
     */
    public function destroy($productId, Request $request)
    {
        $variantId = $request->input('product_variant_id');
        $this->cartService->removeItem($productId, $variantId);

        return redirect()->back();
    }

    /**
     * Apply a coupon code.
     */
    public function applyCoupon(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
        ]);

        if ($this->cartService->applyCoupon($validated['code'])) {
            return redirect()->back()->with('success', __('Coupon applied successfully!'));
        }

        return redirect()->back()->with('error', __('Invalid or expired coupon code.'));
    }

    /**
     * Remove the active coupon.
     */
    public function removeCoupon()
    {
        $this->cartService->removeCoupon();
        return redirect()->back()->with('success', __('Coupon removed.'));
    }

    /**
     * Apply points for discount.
     */
    public function applyPoints(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        if ($this->cartService->applyPoints($validated['amount'])) {
            return redirect()->back()->with('success', __('Points applied successfully!'));
        }

        return redirect()->back()->with('error', __('Insufficient point balance or invalid amount.'));
    }

    /**
     * Remove applied points.
     */
    public function removePoints()
    {
        $this->cartService->removePoints();
        return redirect()->back()->with('success', __('Points removed from order.'));
    }
}
