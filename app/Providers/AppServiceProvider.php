<?php

namespace App\Providers;

use App\Models\Setting;
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
        // Admin login: limit per IP (stops one IP trying many emails)
        // and per email+IP (stops hammering one account).
        RateLimiter::for('login', fn (Request $r) => [
            Limit::perMinute(10)->by('ip:' . $r->ip()),
            Limit::perHour(10)->by(strtolower((string) $r->input('email')) . '|' . $r->ip()),
        ]);

        // Public contact form.
        RateLimiter::for('contact', fn (Request $r) => [
            Limit::perMinute(3)->by($r->ip()),
            Limit::perHour(10)->by($r->ip()),
        ]);

        try {
            foreach (Setting::pluck('value', 'key') as $k => $v) {
                if ($v !== null && $v !== '') {
                    config(['business.' . $k => $v]);
                }
            }
        } catch (\Throwable $e) {
            // settings table not created yet
        }
    }
}