<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminSettingController extends Controller
{
    /**
     * Purana /admin/settings route ab yahan nahi rukta —
     * seedha System Settings page par bhej deta hai taake
     * koi bhi purana link/bookmark toota na rahe.
     */
    public function index()
    {
        return redirect()->route('admin.system-settings.index');
    }

    public function update(Request $request)
    {
        // ✅ FIX: pehle seedha Auth::user()->update(...) call ho raha tha.
        // Agar session expire ho chuki ho ya guard mismatch ho, Auth::user()
        // null hota hai aur ->update() call karte hi "Call to a member
        // function update() on null" fatal error deta hai. Ye guard wahi
        // pattern hai jo NotificationController mein already use ho raha hai.
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        $request->validate([
            'name'  => 'required|string|max:255',
            // ✅ FIX: pehle email ki uniqueness check nahi thi. Agar admin
            // apna email kisi already-existing user ke email se change
            // karta to database ka unique constraint fail hota aur
            // "SQLSTATE[23000]: Integrity constraint violation" wala 500
            // error aata. Ab Laravel khud validation error dikhayega
            // (current user ka apna record ignore karke).
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
        // ✅ FIX: same null-user guard as update() above.
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