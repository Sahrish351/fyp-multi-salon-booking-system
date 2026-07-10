<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OwnerSettingController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user();
            $salon = Salon::where('owner_id', $user->id)->first();

            // Get preferences from session
            $preferences = session('user_preferences_' . $user->id, [
                'email_appointments' => true,
                'email_payments' => true,
                'email_reviews' => true,
                'sms_appointments' => false,
                'sms_payments' => false,
                'weekly_reports' => true,
            ]);

            return view('owner.settings.index', compact('user', 'salon', 'preferences'));

        } catch (\Exception $e) {
            Log::error('Settings Index Error: ' . $e->getMessage());
            return view('owner.settings.index', [
                'user' => auth()->user(),
                'salon' => null,
                'preferences' => $this->getDefaultPreferences(),
            ])->with('error', 'Unable to load settings.');
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = auth()->user();

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:20',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            return redirect()->route('owner.settings.index')
                ->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            Log::error('Profile Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to update profile.')
                ->withInput();
        }
    }

    public function updateNotifications(Request $request)
    {
        try {
            $user = auth()->user();

            $preferences = [
                'email_appointments' => $request->has('email_appointments'),
                'email_payments' => $request->has('email_payments'),
                'email_reviews' => $request->has('email_reviews'),
                'sms_appointments' => $request->has('sms_appointments'),
                'sms_payments' => $request->has('sms_payments'),
                'weekly_reports' => $request->has('weekly_reports'),
            ];

            session(['user_preferences_' . $user->id => $preferences]);

            return redirect()->route('owner.settings.index')
                ->with('success', 'Notification preferences updated successfully!');

        } catch (\Exception $e) {
            Log::error('Notifications Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to update notification preferences.');
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $user = auth()->user();

            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()
                    ->with('error', 'Current password is incorrect.')
                    ->withInput();
            }

            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            return redirect()->route('owner.settings.index')
                ->with('success', 'Password updated successfully!');

        } catch (\Exception $e) {
            Log::error('Password Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to update password.')
                ->withInput();
        }
    }

    private function getDefaultPreferences()
    {
        return [
            'email_appointments' => true,
            'email_payments' => true,
            'email_reviews' => true,
            'sms_appointments' => false,
            'sms_payments' => false,
            'weekly_reports' => true,
        ];
    }
}