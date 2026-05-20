<?php

use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\InquiryController;
use App\Http\Controllers\User\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [App\Http\Controllers\Shop\HomeController::class, 'index'])->name('home');
Route::get('/products/{product:slug}', [App\Http\Controllers\Shop\HomeController::class, 'show'])->name('products.show');

// Cart Routes
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/{product}', [CartController::class, 'destroy'])->name('cart.destroy');
    
    // Coupons
    Route::post('/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
    Route::delete('/coupon', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');
});

// Checkout Routes (Requires Auth)
Route::middleware('auth')->prefix('checkout')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/', [CheckoutController::class, 'store'])->name('checkout.store');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Product Reviews
    Route::get('/products/{product:slug}/review', [App\Http\Controllers\Shop\ProductReviewController::class, 'create'])->name('products.reviews.create');
    Route::post('/products/{product:slug}/review', [App\Http\Controllers\Shop\ProductReviewController::class, 'store'])->name('products.reviews.store');

    // My Page
    Route::prefix('mypage')->group(function () {
        Route::get('/orders', [App\Http\Controllers\Shop\MyPageController::class, 'orders'])->name('mypage.orders');
        Route::get('/orders/{order}', [App\Http\Controllers\Shop\MyPageController::class, 'orderDetail'])->name('mypage.orders.detail');
        Route::get('/profile', [App\Http\Controllers\Shop\MyPageController::class, 'profile'])->name('mypage.profile');
    });
});

Route::get('/legal/trade-law', [App\Http\Controllers\Shop\PageController::class, 'tradeLaw'])->name('legal.trade-law');
Route::get('/pages/{slug}', [App\Http\Controllers\Shop\PageController::class, 'show'])->name('pages.show');

// Inquiry
Route::get('/inquiry', [InquiryController::class, 'index'])->name('inquiry.index');
Route::post('/inquiry', [InquiryController::class, 'store'])->name('inquiry.store');

    // Admin Marketing Ext (Review & Reward)
    Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
        // Inquiries
        Route::resource('inquiries', App\Http\Controllers\Admin\InquiryController::class)->only(['index', 'show', 'update', 'destroy']);

        Route::get('/reviews', [App\Http\Controllers\Admin\ProductReviewController::class, 'index'])->name('reviews.index');
        Route::patch('/reviews/{review}/approve', [App\Http\Controllers\Admin\ProductReviewController::class, 'approve'])->name('reviews.approve');
        Route::delete('/reviews/{review}', [App\Http\Controllers\Admin\ProductReviewController::class, 'destroy'])->name('reviews.destroy');
        
        Route::get('/review-rewards', [App\Http\Controllers\Admin\ReviewRewardController::class, 'edit'])->name('review-rewards.edit');
        Route::put('/review-rewards', [App\Http\Controllers\Admin\ReviewRewardController::class, 'update'])->name('review-rewards.update');

        // Payment Gateways
        Route::get('/payments', [App\Http\Controllers\Admin\PaymentProviderController::class, 'index'])->name('payments.index');
        Route::get('/payments/{provider}/edit', [App\Http\Controllers\Admin\PaymentProviderController::class, 'edit'])->name('payments.edit');
        Route::put('/payments/{provider}', [App\Http\Controllers\Admin\PaymentProviderController::class, 'update'])->name('payments.update');
    });

require __DIR__.'/auth.php';
