<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // API GeoJSON — 60 request per menit per IP
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });

        // Login admin — 5 percobaan per menit per IP (anti-brute force)
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        // Submit review publik — 5 per menit per IP (anti-spam review)
        RateLimiter::for('review', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        // Submit kontak/inquiry — 3 per menit per IP (anti-spam form kontak)
        RateLimiter::for('contact', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });
    }
}
