<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Maximum allowed failed attempts before account is blocked
    const MAX_ATTEMPTS = 5;

    // Show login form
    public function showLoginForm()
    {
        // Check which route was called
        if (url()->current() == route('client.login.form')) {
            return view('auth.client-login');
        } elseif (url()->current() == route('owner.login.form')) {
            return view('auth.owner-login');
        } elseif (url()->current() == route('admin.login.form')) {
            return view('auth.admin-login');
        }
        
        return view('auth.login-selector');
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Determine which role this login form is meant for
        $expectedRole = null;
        if ($request->routeIs('client.login.submit')) {
            $expectedRole = 'client';
        } elseif ($request->routeIs('owner.login.submit')) {
            $expectedRole = 'owner';
        } elseif ($request->routeIs('admin.login.submit')) {
            $expectedRole = 'admin';
        }

        $user = User::where('email', $request->email)->first();

        // Agar account already block ho chuka hai
        if ($user && !$user->is_active) {
            return back()->withErrors([
                'email' => 'Your account has been blocked due to multiple failed login attempts. Please contact support.',
            ])->onlyInput('email');
        }

        // Agar user exist karta hai lekin galat form se login kar raha hai (role mismatch)
        if ($user && $expectedRole && $user->role !== $expectedRole) {
            return back()->withErrors([
                'email' => 'These credentials do not match a ' . $expectedRole . ' account.',
            ])->onlyInput('email');
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $authUser = Auth::user();

            // Successful login par attempts reset kar dein
            if ($authUser->failed_login_attempts > 0) {
                $authUser->update(['failed_login_attempts' => 0]);
            }

            // Redirect based on role
            if ($authUser->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } 
            elseif ($authUser->role === 'owner') {
                return redirect()->route('owner.dashboard');
            } 
            else {
                return redirect()->route('client.dashboard');
            }
        }

        // Galat credentials — agar user exist karta hai to uski attempt count barhayein
        if ($user) {
            $user->increment('failed_login_attempts');

            if ($user->failed_login_attempts >= self::MAX_ATTEMPTS) {
                $user->update(['is_active' => false]);

                return back()->withErrors([
                    'email' => 'Too many failed login attempts. Your account has been blocked. Please contact support.',
                ])->onlyInput('email');
            }
        }

        return back()->withErrors([
            'email' => 'Invalid email or password',
        ])->onlyInput('email');
    }

    // ✅ ADD THIS METHOD (Login ke baad redirect handle karega)
    protected function authenticated(Request $request, $user)
    {
        // Check if there was an intended URL (booking page)
        $intendedUrl = session('url.intended', url()->previous());
        
        // If intended URL contains 'booking', redirect to that
        if (str_contains($intendedUrl, '/booking/')) {
            session()->forget('url.intended');
            return redirect()->to($intendedUrl);
        }
        
        // Default redirect based on role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'owner') {
            return redirect()->route('owner.dashboard');
        } else {
            return redirect()->route('client.dashboard');
        }
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('select.login');
    }
}