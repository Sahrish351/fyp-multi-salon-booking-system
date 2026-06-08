<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ConfirmPasswordController extends Controller
{
    /**
     * Show confirm password form
     */
    public function showForm()
    {
        return view('auth.confirm-password');
    }

    /**
     * Confirm user's password
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        // Store confirmation in session
        session(['auth.password_confirmed_at' => time()]);

        // Redirect to intended page
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Check if password confirmation is required
     */
    protected function requiresConfirmation()
    {
        $confirmedAt = session('auth.password_confirmed_at');
        
        if (!$confirmedAt) {
            return true;
        }
        
        // Confirmation expires after 3 hours
        $expiresAfter = config('auth.password_timeout', 10800); // 3 hours default
        
        return (time() - $confirmedAt) > $expiresAfter;
    }

    /**
     * Show password confirmation for sensitive actions
     */
    public function showForAction(Request $request, $action)
    {
        session(['redirect_action' => $action]);
        return view('auth.confirm-password-action', compact('action'));
    }

    /**
     * Confirm for specific action
     */
    public function confirmForAction(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $action = session('redirect_action');
        
        session(['auth.password_confirmed_at' => time()]);
        session()->forget('redirect_action');

        return redirect()->route($action)->with('success', 'Password confirmed. Proceed with your action.');
    }

    /**
     * Show change password form from confirmation
     */
    public function showChangePassword()
    {
        return view('auth.confirm-change-password');
    }

    /**
     * Change password after confirmation
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password'         => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Clear confirmed session
        session()->forget('auth.password_confirmed_at');

        return redirect()->route('profile')->with('success', 'Password changed successfully!');
    }
}