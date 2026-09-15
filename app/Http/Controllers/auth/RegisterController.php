<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterClientRequest;
use App\Http\Requests\Auth\RegisterOwnerRequest;
use App\Models\User;
use App\Models\Salon;
use App\Models\OtpVerification;
use App\Mail\OtpMail;
use App\Notifications\AdminNewSalonRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

        return redirect()->route('verification.notice')->with('success', 'Welcome to Beauty Blush Salons! Please verify your email.');
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

        // Salon record — isi waqt banega, status pending
        $salon = Salon::create([
            'owner_id' => $user->id,
            'name'     => $request->salon_name,
            'slug'     => Str::slug($request->salon_name) . '-' . uniqid(),
            'phone'    => $request->phone,
            'email'    => $request->email,
            'address'  => $request->address,
            'city'     => $request->city,
            'cnic'     => $request->cnic,
            'status'   => 'pending',
        ]);

        Auth::login($user);

        $this->sendVerificationOtp($user);

        // Sab admins ko email
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new AdminNewSalonRequest($salon));
        }

        return redirect()->route('verification.notice')->with('success', 'Welcome to Beauty Blush Salons! Please verify your email to continue.');
    }

  
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