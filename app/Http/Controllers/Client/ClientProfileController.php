<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClientProfileController extends Controller
{
    public function index()
    {
        $client = Auth::user();
        return view('client.profile.index', compact('client'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email',
            'phone' => 'nullable',
            'city'  => 'nullable',
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            Auth::user()->update(['avatar' => $avatarPath]);
        }

        Auth::user()->update($request->only('name', 'email', 'phone', 'city', 'theme'));
        return back()->with('success', 'Profile updated!');
    }
}