<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Force Storage::url() to use APP_URL so images resolve
        // correctly when running under an XAMPP subdirectory
        $appUrl = rtrim(config('app.url'), '/');
        config(['filesystems.disks.public.url' => $appUrl . '/storage']);
    }
}
