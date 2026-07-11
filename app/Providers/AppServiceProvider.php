<?php

namespace App\Providers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Auto-verify email on registration (no SMTP needed)
        Fortify::createUsersUsing(function (array $input) {
            return \App\Models\User::create([
                'name'               => $input['name'],
                'email'              => $input['email'],
                'password'           => \Illuminate\Support\Facades\Hash::make($input['password']),
                'email_verified_at'  => now(),
            ]);
        });

        // Force HTTPS when behind a proxy (Railway, etc.)
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Force Storage::url() to use APP_URL so images resolve correctly
        $appUrl = rtrim(config('app.url'), '/');
        config(['filesystems.disks.public.url' => $appUrl . '/storage']);
    }
}
