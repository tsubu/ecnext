<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        $blockPluginsRoot = config('blocks.plugins_path');
        if (is_string($blockPluginsRoot) && is_dir($blockPluginsRoot)) {
            View::addNamespace('block-plugins', $blockPluginsRoot);
        }

        // Register Morph Map for Audit Logs & CMS
        \Illuminate\Database\Eloquent\Relations\Relation::morphMap([
            'admin' => \App\Models\Admin::class,
            'user' => \App\Models\User::class,
            'system' => \App\Models\Admin::class, // Map to Admin (will return null for ID 0/null) to prevent "Class 'system' not found"
        ]);

        // Register Active Theme View Path
        if (!$this->app->runningInConsole()) {
            try {
                $activeTheme = \App\Models\Theme::active()->first();
                if ($activeTheme) {
                    $viewPath = resource_path('views/themes/' . $activeTheme->theme_key);
                    if (is_dir($viewPath)) {
                        view()->addLocation($viewPath);
                    }
                }

                // Set Global Timezone from Shop Settings
                $shopSetting = \App\Models\ShopSetting::first();
                if ($shopSetting && $shopSetting->timezone) {
                    config(['app.timezone' => $shopSetting->timezone]);
                    date_default_timezone_set($shopSetting->timezone);
                }
            } catch (\Exception $e) {
                // Prevent crash during initial setup/migration
            }
        }
    }
}
