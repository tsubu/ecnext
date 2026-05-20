<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Services\Marketing\ReviewService;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function __construct(
        protected ReviewService $reviewService
    ) {}

    public function index()
    {
        $reviews = ProductReview::with(['user', 'product', 'order'])
            ->latest()
            ->paginate(20);
            
        return view('admin.marketing.reviews.index', compact('reviews'));
    }

    public function approve(ProductReview $review)
    {
        $this->reviewService->approveReview($review);
        
        return back()->with('success', __('Review approved and rewards processed.'));
    }

    public function destroy(ProductReview $review)
    {
        $review->delete();
        
        return back()->with('success', __('Review deleted.'));
    }
}
