<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterClientRequest;
use App\Http\Requests\Auth\RegisterOwnerRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
        return redirect()->route('client.dashboard')->with('success', 'Welcome to Glamora!');
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
        return redirect()->route('owner.dashboard')->with('success', 'Welcome! Please register your salon.');
    }
}