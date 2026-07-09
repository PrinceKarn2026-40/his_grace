<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Force HTTPS when behind a proxy (Railway, etc.)
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Force Storage::url() to use APP_URL so images resolve correctly
        $appUrl = rtrim(config('app.url'), '/');
        config(['filesystems.disks.public.url' => $appUrl . '/storage']);
    }
}
