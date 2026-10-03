<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminSettingController extends Controller
{
   
    public function index()
    {
        return redirect()->route('admin.system-settings.index');
    }

    public function update(Request $request)
    {
        
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        $request->validate([
            'name'  => 'required|string|max:255',
            
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ]);

        $user->update($request->only('name', 'email', 'theme'));

        return back()->with('success', 'Account details updated successfully.');
    }

    public function updatePassword(Request $request)
    {
      
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password updated successfully.');
    }
}
