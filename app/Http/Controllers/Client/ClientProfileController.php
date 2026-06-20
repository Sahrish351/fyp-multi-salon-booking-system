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
        $user = Auth::user();

        //  Conditional Validation 
        $rules = [];

        if ($request->has('name') && $request->filled('name')) {
            $rules['name'] = 'required|string|max:255';
        }

        if ($request->has('email') && $request->filled('email')) {
            $rules['email'] = 'required|email|unique:users,email,' . $user->id;
        }

        if ($request->has('phone')) {
            $rules['phone'] = 'nullable|string|max:20';
        }

        if ($request->has('city')) {
            $rules['city'] = 'nullable|string|max:255';
        }

        if ($request->has('theme')) {
            $rules['theme'] = 'nullable|in:light,dark';
        }

        if ($request->hasFile('avatar')) {
            $rules['avatar'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        //  Password Validation 
        if ($request->filled('current_password') || $request->filled('password')) {
            $rules['current_password'] = 'required|string|min:8';
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        //  Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        //  Update basic fields 
        if ($request->has('name') && $request->filled('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email') && $request->filled('email')) {
            $user->email = $request->email;
        }

        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }

        if ($request->has('city')) {
            $user->city = $request->city;
        }

        if ($request->has('theme')) {
            $user->theme = $request->theme ?? 'light';
        }

        //  Handle password change
        if ($request->filled('current_password') && $request->filled('password')) {
            // Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }

            // Update password
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }
}