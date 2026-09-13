<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterClientRequest;
use App\Http\Requests\Auth\RegisterOwnerRequest;
use App\Models\User;
use App\Models\OtpVerification;
use App\Mail\OtpMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function showClientForm()
    {
        return view('auth.register-client');
    }

    public function showOwnerForm()
    {
        return view('auth.register-owner');
    }

    public function registerClient(RegisterClientRequest $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => 'client',
            'city'     => $request->city,
        ]);

        Auth::login($user);

        $this->sendVerificationOtp($user);

        return redirect()->route('verification.notice')->with('success', 'Welcome to Glamora! Please verify your email.');
    }

    public function registerOwner(RegisterOwnerRequest $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => 'owner',
            'city'     => $request->city,
        ]);

        Auth::login($user);

        $this->sendVerificationOtp($user);

        return redirect()->route('verification.notice')->with('success', 'Welcome! Please verify your email to continue.');
    }

    /**
     * Generate and send an email verification OTP to a newly registered user.
     */
    protected function sendVerificationOtp(User $user)
    {
        $otp = rand(100000, 999999);

        OtpVerification::updateOrCreate(
            ['email' => $user->email, 'type' => 'email_verify'],
            [
                'otp'        => $otp,
                'is_used'    => false,
                'expires_at' => Carbon::now()->addMinutes(10),
            ]
        );

        Mail::to($user->email)->send(new OtpMail($otp));
    }
}