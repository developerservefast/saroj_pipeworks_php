<?php

namespace App\Providers;

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
        \Illuminate\Support\Facades\RateLimiter::for('login', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perHour(5)->by(strtolower((string) $request->input('email')) . '|' . $request->ip());
        });

        try {
            foreach (\App\Models\Setting::pluck('value', 'key') as $k => $v) {
                if ($v !== null && $v !== '') config(['business.' . $k => $v]);
            }
        } catch (\Throwable $e) {
            // settings table not created yet
        }
        //
    }
}
