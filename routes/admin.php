<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

// Admin Guest Routes
Route::middleware('guest:admin')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('admin.login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

// Admin Authenticated Routes
Route::middleware('auth:admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Language Switcher
    Route::get('locale/{locale}', [\App\Http\Controllers\Admin\LocaleController::class, 'update'])->name('admin.locale.update');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('admin.logout');
    
    // Catalog Management
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class)->names('admin.products');
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->names('admin.categories');
    Route::resource('tags', \App\Http\Controllers\Admin\TagController::class)->names('admin.tags');

    // Order Management
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'update'])->names('admin.orders');

    // CMS Management
    Route::get('block-center', [\App\Http\Controllers\Admin\BlockManagementController::class, 'index'])->name('admin.block-center.index');
    Route::resource('page-categories', \App\Http\Controllers\Admin\PageCategoryController::class)->names('admin.page-categories');
    Route::resource('pages', \App\Http\Controllers\Admin\PageController::class)->names('admin.pages');
    Route::post('block-instances/preview', [\App\Http\Controllers\Admin\BlockInstanceController::class, 'preview'])->name('admin.block-instances.preview');
    Route::resource('block-instances', \App\Http\Controllers\Admin\BlockInstanceController::class)->names('admin.block-instances');

    // Marketing & Extensions
    Route::resource('notices', \App\Http\Controllers\Admin\NoticeController::class)->names('admin.notices');
    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class)->names('admin.coupons');
    Route::resource('campaigns', \App\Http\Controllers\Admin\CampaignController::class)->names('admin.campaigns');

    // System Monitoring
    Route::get('/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('admin.audit-logs.index');

    // Staff Management
    Route::resource('staff', \App\Http\Controllers\Admin\StaffController::class)->names('admin.staff');

    // Customer Management
    Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class)->names('admin.customers');

    // Design & Themes
    Route::resource('themes', \App\Http\Controllers\Admin\ThemeController::class)->names('admin.themes');
    Route::post('themes/{theme}/activate', [\App\Http\Controllers\Admin\ThemeController::class, 'activate'])->name('admin.themes.activate');
    Route::resource('block-types', \App\Http\Controllers\Admin\BlockTypeController::class)->names('admin.block-types');

    // Shop & Legal Settings
    Route::prefix('settings')->group(function () {
        Route::get('/shop', [\App\Http\Controllers\Admin\ShopSettingController::class, 'edit'])->name('admin.settings.shop.edit');
        Route::put('/shop', [\App\Http\Controllers\Admin\ShopSettingController::class, 'update'])->name('admin.settings.shop.update');
    });

    // Tax Rules
    Route::resource('tax-rules', \App\Http\Controllers\Admin\TaxRuleController::class)->names('admin.tax_rules');
});
