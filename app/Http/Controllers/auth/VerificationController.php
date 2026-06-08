<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OtpVerification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /**
     * Show email verification notice
     */
    public function showNotice()
    {
        return view('auth.verify-notice');
    }

    /**
     * Send email verification OTP
     */
    public function sendVerificationEmail(Request $request)
    {
        $user = Auth::user();
        
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard')->with('success', 'Email already verified.');
        }

        // Generate OTP
        $otp = rand(100000, 999999);
        
        // Store OTP
        OtpVerification::updateOrCreate(
            ['email' => $user->email, 'type' => 'email_verify'],
            [
                'otp'        => $otp,
                'is_used'    => false,
                'expires_at' => Carbon::now()->addMinutes(10),
            ]
        );

        return back()->with('success', 'Verification OTP sent to your email.');
    }

    /**
     * Verify email with OTP
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = Auth::user();
        
        $otpRecord = OtpVerification::where('email', $user->email)
            ->where('otp', $request->otp)
            ->where('type', 'email_verify')
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        // Mark OTP as used
        $otpRecord->update(['is_used' => true]);

        // Mark email as verified
        $user->update([
            'email_verified_at' => Carbon::now(),
            'is_verified' => true,
        ]);

        // Redirect based on role
        return match($user->role) {
            'admin'  => redirect()->route('admin.dashboard')->with('success', 'Email verified successfully!'),
            'owner'  => redirect()->route('owner.dashboard')->with('success', 'Email verified successfully!'),
            'client' => redirect()->route('client.dashboard')->with('success', 'Email verified successfully!'),
            default  => redirect('/')->with('success', 'Email verified successfully!'),
        };
    }

    /**
     * Show phone verification form
     */
    public function showPhoneForm()
    {
        return view('auth.verify-phone');
    }

    /**
     * Send phone verification OTP
     */
    public function sendPhoneOtp(Request $request)
    {
        $request->validate(['phone' => 'required|string']);
        
        $user = Auth::user();
        
        if ($user->phone && $user->phone === $request->phone) {
            // Generate OTP
            $otp = rand(100000, 999999);
            
            // Store OTP
            OtpVerification::updateOrCreate(
                ['phone' => $request->phone, 'type' => 'phone_verify'],
                [
                    'otp'        => $otp,
                    'is_used'    => false,
                    'expires_at' => Carbon::now()->addMinutes(10),
                ]
            );


            return back()->with('success', 'OTP sent to your phone.');
        }
        
        return back()->withErrors(['phone' => 'Phone number does not match our records.']);
    }

    /**
     * Verify phone with OTP
     */
    public function verifyPhone(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp'   => 'required|digits:6',
        ]);

        $user = Auth::user();
        
        $otpRecord = OtpVerification::where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->where('type', 'phone_verify')
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        // Mark OTP as used
        $otpRecord->update(['is_used' => true]);

        // Update user phone verification
        $user->update(['is_verified' => true]);

        return redirect()->route('dashboard')->with('success', 'Phone verified successfully!');
    }

   
    public function resend(Request $request)
    {
        $user = Auth::user();
        
        // Generate new OTP
        $otp = rand(100000, 999999);
        
        // Update OTP
        OtpVerification::updateOrCreate(
            ['email' => $user->email, 'type' => 'email_verify'],
            [
                'otp'        => $otp,
                'is_used'    => false,
                'expires_at' => Carbon::now()->addMinutes(10),
            ]
        );

        return back()->with('success', 'New verification OTP sent.');
    }
}