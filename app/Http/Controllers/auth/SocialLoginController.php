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
    // ==================== GOOGLE LOGIN ====================
    
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
            
            // Check if user exists by email
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if (!$user) {
                // Create new user with client role
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar_url' => $googleUser->getAvatar(),
                    'password' => Hash::make(Str::random(16)),
                    'email_verified_at' => now(),
                    'role' => 'client',
                ]);
            } else {
                // Update google_id if missing
                if (is_null($user->google_id)) {
                    $user->google_id = $googleUser->getId();
                    $user->save();
                }
                // Update role if null
                if (is_null($user->role)) {
                    $user->role = 'client';
                    $user->save();
                }
            }
            
            Auth::login($user);
            
            // ✅ DIRECT CLIENT DASHBOARD PE REDIRECT
            return redirect('/client/dashboard');
            
        } catch (\Exception $e) {
            return redirect()->route('client.login.form')->with('error', 'Google login failed! ' . $e->getMessage());
        }
    }

    // ==================== FACEBOOK LOGIN ====================
    
    public function redirectToFacebook()
    {
        // SSL verification temporarily disable for localhost testing
        $socialite = Socialite::driver('facebook');
        $socialite->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
        return $socialite->scopes(['email'])->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            // SSL verification temporarily disable for localhost testing
            $facebookUser = Socialite::driver('facebook')
                ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
                ->user();
            
            // Check if user exists by email OR facebook_id
            $user = User::where('email', $facebookUser->getEmail())
                ->orWhere('facebook_id', $facebookUser->getId())
                ->first();
            
            if (!$user) {
                // Create new user
                $user = User::create([
                    'name' => $facebookUser->getName(),
                    'email' => $facebookUser->getEmail(),
                    'facebook_id' => $facebookUser->getId(),
                    'avatar_url' => $facebookUser->getAvatar(),
                    'password' => Hash::make(Str::random(16)),
                    'email_verified_at' => now(),
                    'role' => 'client',
                ]);
            } else {
                // Update facebook_id if missing
                if (is_null($user->facebook_id)) {
                    $user->facebook_id = $facebookUser->getId();
                    $user->save();
                }
                // Update role if null
                if (is_null($user->role)) {
                    $user->role = 'client';
                    $user->save();
                }
            }
            
            Auth::login($user);
            
            //  DIRECT CLIENT DASHBOARD  REDIRECT
            return redirect('/client/dashboard');
            
        } catch (\Exception $e) {
            return redirect()->route('client.login.form')->with('error', 'Facebook login failed! ' . $e->getMessage());
        }
    }
}