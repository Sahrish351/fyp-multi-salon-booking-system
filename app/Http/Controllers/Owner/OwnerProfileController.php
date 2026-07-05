<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Models\Salon;
use App\Models\User;

class OwnerProfileController extends Controller
{
    /**
     * Show Salon Profile
     */
    public function index(Request $request)
    {
        try {
            $user = auth()->user();

            // Check if user is owner
            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            // Get salon data from database
            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Please create your salon first.');
            }

            return view('owner.profile', compact('salon'));

        } catch (\Exception $e) {
            \Log::error('Owner Profile Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to load profile.');
        }
    }

    /**
     * Update Salon Profile
     */
    public function update(Request $request)
    {
        try {
            $user = auth()->user();

            // Check if user is owner
            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Salon not found.');
            }

            // Validation rules
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|max:255|unique:salons,email,' . $salon->id,
                'website' => 'nullable|string|max:255',
                'address' => 'required|string|max:500',
                'description' => 'nullable|string|max:2000',
                'city' => 'nullable|string|max:100',
                'area' => 'nullable|string|max:100',
                'tagline' => 'nullable|string|max:255',
                'open_time' => 'nullable|string|max:10',
                'close_time' => 'nullable|string|max:10',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Update salon
            $salon->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'website' => $request->website,
                'address' => $request->address,
                'description' => $request->description,
                'city' => $request->city ?? $salon->city,
                'area' => $request->area ?? $salon->area,
                'tagline' => $request->tagline ?? $salon->tagline,
                'open_time' => $request->open_time ?? $salon->open_time,
                'close_time' => $request->close_time ?? $salon->close_time,
            ]);

            return redirect()->route('owner.profile')
                ->with('success', 'Salon profile updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Profile Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to update profile. Please try again.');
        }
    }

    /**
     * Upload Salon Logo / Profile Picture
     */
    public function uploadPicture(Request $request)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Salon not found.');
            }

            // Validation
            $validator = Validator::make($request->all(), [
                'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Delete old logo if exists
            if ($salon->logo && Storage::disk('public')->exists($salon->logo)) {
                Storage::disk('public')->delete($salon->logo);
            }

            // Upload new logo
            $logoPath = $request->file('logo')->store('salon-logos', 'public');

            // Update salon
            $salon->update([
                'logo' => $logoPath,
            ]);

            return redirect()->route('owner.profile')
                ->with('success', 'Salon logo updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Logo Upload Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to upload logo. Please try again.');
        }
    }
}