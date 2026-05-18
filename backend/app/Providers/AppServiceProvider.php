<?php

/**
 * ============================================================================
 * AppServiceProvider — Global Application Service Provider
 * ============================================================================
 *
 * PURPOSE:
 *   The central location to bootstrap and configure global application services,
 *   macros, configurations, and validation rules in the PMRS Laravel backend.
 *
 * KEY RESPONSIBILITIES & FUNCTIONS:
 *
 *   1. HTTPS Enforcement:
 *      In production environments (`APP_ENV=production`), automatically forces
 *      all generated URLs and routes to use secure HTTPS protocol instead of HTTP.
 *
 *   2. Global Password Validation Policy:
 *      Defines the global `Password::defaults()` rule set used across the system:
 *      - Minimum length of 8 characters.
 *      - Requires at least one letter (uppercase & lowercase).
 *      - Requires at least one number.
 *      - Requires at least one special character symbol.
 *      This secures registration, password resets, and user creation.
 *
 * RELATED FILES:
 *   - Controllers: App\Http\Controllers\Auth\... (uses Password::defaults())
 *   - Config:      config/app.php
 * ============================================================================
 */
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rules\Password;

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
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        // Globally enforce secure password defaults across registration, resets, and profiles
        Password::defaults(function () {
            return Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols();
        });
    }
}
