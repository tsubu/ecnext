<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    /**
     * Change the application locale.
     *
     * @param string $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(string $locale)
    {
        // Validation could be added here using LanguageService
        // But for now, we'll trust the session setting
        Session::put('locale', $locale);
        
        return redirect()->back(fallback: route('admin.dashboard'))->with('success', __('Language changed successfully.'));
    }
}
