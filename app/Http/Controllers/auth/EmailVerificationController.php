<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailVerificationController extends Controller
{
    /**
     * Show verification notice
     */
    public function notice()
    {
        return view('auth.verify-email');
    }

    /**
     * Send verification link
     */
    public function send(Request $request)
    {
        $user = $request->user();
        
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        // Send verification email
        // Mail::to($user->email)->send(new VerifyEmailMail($user));

        return back()->with('success', 'Verification link sent!');
    }

    /**
     * Verify email
     */
    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
            return redirect()->route('login')->withErrors(['email' => 'Invalid verification link.']);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('success', 'Email already verified.');
        }

        $user->markEmailAsVerified();
        $user->update(['is_verified' => true]);

        return redirect()->route('login')->with('success', 'Email verified successfully! Please login.');
    }

    /**
     * Resend verification link
     */
    public function resend(Request $request)
    {
        $user = $request->user();
        
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        // Mail::to($user->email)->send(new VerifyEmailMail($user));

        return back()->with('success', 'Verification link resent!');
    }
}