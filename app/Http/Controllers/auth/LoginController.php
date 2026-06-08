<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
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
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Redirect based on role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } 
            elseif ($user->role === 'owner') {
                return redirect()->route('owner.dashboard');
            } 
            else {
                return redirect()->route('client.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
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