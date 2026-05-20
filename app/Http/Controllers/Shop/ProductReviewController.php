<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Services\Marketing\ReviewService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductReviewController extends Controller
{
    public function __construct(
        protected ReviewService $reviewService
    ) {}

    /**
     * Show form to write a review.
     */
    public function create(Product $product, Request $request)
    {
        $userId = auth()->id();
        $orderId = $request->query('order_id');

        if (!$this->reviewService->canUserReviewProduct($userId, $product->id, $orderId)) {
            return redirect()->route('products.show', $product->slug)
                ->with('error', __('You are not eligible to review this product or have already reviewed it.'));
        }

        return Inertia::render('Shop/ProductReview/Create', [
            'product' => $product,
            'orderId' => $orderId,
        ]);
    }

    /**
     * Post a review.
     */
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
            'order_id' => 'nullable|exists:orders,id',
        ]);

        try {
            $this->reviewService->submitReview([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'order_id' => $validated['order_id'],
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]);

            return redirect()->route('products.show', $product->slug)
                ->with('success', __('Review submitted. It will be visible once approved.'));

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
