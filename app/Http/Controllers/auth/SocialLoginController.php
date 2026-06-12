<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class SocialLoginController extends Controller
{
    // Google Login
    public function redirectToGoogle()
    {
        // SSL verification temporarily disable for localhost testing
        $socialite = Socialite::driver('google');
        $socialite->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
        return $socialite->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            // SSL verification temporarily disable for localhost testing
            $googleUser = Socialite::driver('google')
                ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
                ->user();
            
            // Check if user exists
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if (!$user) {
                // Create new user with client role
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'avatar_url' => $googleUser->getAvatar(),
                    'password' => Hash::make(Str::random(16)),
                    'email_verified_at' => now(),
                    'role' => 'client',
                ]);
            } else {
                // Agar user exist karta hai aur role null hai toh set karo
                if (is_null($user->role)) {
                    $user->role = 'client';
                    $user->save();
                }
            }
            
            Auth::login($user);
            
            // Redirect based on role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'owner') {
                return redirect()->route('owner.dashboard');
            } else {
                return redirect()->route('client.dashboard');
            }
            
        } catch (\Exception $e) {
            return redirect()->route('client.login.form')->with('error', 'Google login failed! ' . $e->getMessage());
        }
    }

    // Facebook Login
    public function redirectToFacebook()
    {
        // SSL verification temporarily disable for localhost testing
        $socialite = Socialite::driver('facebook');
        $socialite->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
        return $socialite->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            // SSL verification temporarily disable for localhost testing
            $facebookUser = Socialite::driver('facebook')
                ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
                ->user();
            
            $user = User::where('email', $facebookUser->getEmail())->first();
            
            if (!$user) {
                $user = User::create([
                    'name' => $facebookUser->getName(),
                    'email' => $facebookUser->getEmail(),
                    'avatar_url' => $facebookUser->getAvatar(),
                    'password' => Hash::make(Str::random(16)),
                    'email_verified_at' => now(),
                    'role' => 'client',
                ]);
            } else {
                if (is_null($user->role)) {
                    $user->role = 'client';
                    $user->save();
                }
            }
            
            Auth::login($user);
            
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'owner') {
                return redirect()->route('owner.dashboard');
            } else {
                return redirect()->route('client.dashboard');
            }
            
        } catch (\Exception $e) {
            return redirect()->route('client.login.form')->with('error', 'Facebook login failed! ' . $e->getMessage());
        }
    }
}