<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if locale is stored in session (User choice)
        if (Session::has('locale')) {
            $locale = Session::get('locale');
            App::setLocale($locale);
        } else {
            // 2. Detect from browser
            $languageService = new \App\Services\Localization\LanguageService();
            $availableLocales = array_keys($languageService->getAvailableLanguages());
            
            // Get browser languages (ordered by priority)
            $browserLocales = $request->getLanguages();
            
            $match = null;
            foreach ($browserLocales as $browserLocale) {
                // Handle complex tags like 'ja-JP' -> 'ja'
                $code = explode('-', $browserLocale)[0];
                
                if (in_array($code, $availableLocales)) {
                    $match = $code;
                    break;
                }
            }
            
            // 3. Fallback to English if no match found
            $finalLocale = $match ?? 'en';
            App::setLocale($finalLocale);
            
            // Optionally store it in session so we don't recalculate every time
            // Session::put('locale', $finalLocale);
        }

        return $next($request);
    }
}
