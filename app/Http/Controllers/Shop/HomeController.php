<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Services\Catalog\ProductService;
use Inertia\Inertia;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {}

    /**
     * Display the storefront home page.
     */
    public function index()
    {
        $products = $this->productService->searchProducts([], 8);
        $activeTheme = \App\Models\Theme::active()->first();

        // Fetch the home page and its dynamic blocks
        $indexPage = \App\Models\Page::where('slug', 'index')
            ->with(['layouts' => fn($q) => $q->visible()->with('blockInstance.blockType')])
            ->first();

        // If a theme is active, render its Blade template
        if ($activeTheme) {
            return view('index', [
                'products' => $products->items(),
                'activeTheme' => $activeTheme,
                'indexPage' => $indexPage,
            ]);
        }

        // Fallback to default Inertia rendering if no theme is found
        return Inertia::render('Welcome', [
            'featuredProducts' => $products->items(),
            'canLogin' => \Illuminate\Support\Facades\Route::has('login'),
            'canRegister' => \Illuminate\Support\Facades\Route::has('register'),
        ]);
    }

    /**
     * Display a specific product.
     */
    public function show($slug)
    {
        $product = \App\Models\Product::where('slug', $slug)
            ->with(['variants', 'images', 'categories', 'reviews.user', 'layouts' => fn($q) => $q->visible()->with('blockInstance.blockType')])
            ->firstOrFail();
            
        $activeTheme = \App\Models\Theme::active()->first();
        $view = $activeTheme ? "themes.{$activeTheme->slug}.pages.show" : 'products.show';

        if (!view()->exists($view)) {
             abort(404, "Theme view $view not found.");
        }

        return view($view, [
            'product' => $product,
            'activeTheme' => $activeTheme,
            'reviews' => $product->reviews()->where('is_approved', true)->latest()->get(),
            'averageRating' => floatval($product->reviews()->where('is_approved', true)->avg('rating') ?: 0),
        ]);
    }
}
