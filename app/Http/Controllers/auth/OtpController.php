<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class OtpController extends Controller
{
    public function showVerifyForm()
    {
        return view('auth.otp-verify');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|array|size:6',
        ]);

        $enteredOtp = implode('', $request->otp);
        $storedOtp = Session::get('otp');

        if ($enteredOtp == $storedOtp) {
            Session::forget('otp');
            return redirect()->route('client.dashboard')->with('success', 'Phone verified successfully!');
        }

        return back()->with('error', 'Invalid OTP. Please try again.');
    }

    public function resend(Request $request)
    {
        // Generate new OTP
        $newOtp = rand(100000, 999999);
        Session::put('otp', $newOtp);
        
        // Here you can add SMS sending logic
        
        return back()->with('success', 'New OTP sent successfully!');
    }
}