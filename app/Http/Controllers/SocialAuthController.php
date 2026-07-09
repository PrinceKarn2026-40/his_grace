<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Google authentication failed. Please try again.']);
        }

        $user = User::updateOrCreate(
            ['google_id' => $googleUser->getId()],
            [
                'name'              => $googleUser->getName(),
                'email'             => $googleUser->getEmail(),
                'email_verified_at' => now(),
                'profile_photo_path'=> $googleUser->getAvatar(),
            ]
        );

        // If a user with this email already exists without google_id, link the account
        if (!$user->wasRecentlyCreated && !$user->google_id) {
            $user->update(['google_id' => $googleUser->getId()]);
        }

        Auth::login($user, true);

        if ($user->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('customer.dashboard');
    }
}
