<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\ShopSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    /**
     * Display the specified static page.
     */
    public function show($slug)
    {
        $page = Page::where('slug', $slug)
            ->where('is_published', true)
            ->with(['category', 'layouts' => fn($q) => $q->visible()->with('blockInstance.blockType')])
            ->firstOrFail();

        // If an active theme has a Blade template, use it
        $activeTheme = \App\Models\Theme::active()->first();
        if ($activeTheme) {
            $themeView = "themes.{$activeTheme->theme_key}.pages.show";
            if (view()->exists($themeView)) {
                return view($themeView, compact('page', 'activeTheme'));
            }
        }

        // Fallback to Inertia
        return Inertia::render('Shop/Page/Show', [
            'page' => $page,
            'html_content' => $page->content,
        ]);
    }

    /**
     * Display the Specified Commercial Transactions Act (Trade Law) page.
     */
    public function tradeLaw()
    {
        $page = Page::where('slug', 'trade-law')->first();
        
        if (!$page) {
            $setting = ShopSetting::firstOrFail();
            // Fallback for when DB record doesn't exist yet but settings do
            return Inertia::render('Shop/Page/Show', [
                'page' => [
                    'title' => __('Trade Law Compliance'),
                    'type' => 'legal',
                    'legal_data' => $setting->toArray()
                ]
            ]);
        }

        return $this->show('trade-law');
    }
}
