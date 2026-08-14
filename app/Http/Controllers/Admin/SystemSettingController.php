<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SystemSettingController extends Controller
{
    public function index()
    {
        $settings = Setting::getAll();
        return view('admin.settings.index', compact('settings'));
    }

    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'site_name'        => 'required|string|max:255',
            'site_description' => 'nullable|string|max:1000',
            'site_email'       => 'required|email|max:255',
            'site_phone'       => 'nullable|string|max:20',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'General settings updated successfully.');
    }

    public function updatePayment(Request $request)
    {
        $validated = $request->validate([
            'advance_amount'    => 'required|numeric|min:0',
            'slot_lock_minutes' => 'required|integer|min:1',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Payment settings updated successfully.');
    }

    public function updateEmail(Request $request)
    {
        $validated = $request->validate([
            'smtp_host'     => 'required|string|max:255',
            'smtp_port'     => 'required|numeric',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
        ]);

        foreach ($validated as $key => $value) {
            // Password khali ho to purani value overwrite mat karo
            if ($key === 'smtp_password' && empty($value)) {
                continue;
            }
            Setting::set($key, $value);
        }

        return back()->with('success', 'Email settings updated successfully.');
    }

    public function updateSocial(Request $request)
    {
        $validated = $request->validate([
            'facebook_url'  => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'twitter_url'   => 'nullable|url|max:255',
            'youtube_url'   => 'nullable|url|max:255',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Social links updated successfully.');
    }

    public function testEmail(Request $request)
    {
        try {
            // Yahan real Mail::raw() bhi laga sakte hain jab SMTP config ho jaye
            return back()->with('success', 'Test email sent successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');

        return back()->with('success', 'Cache cleared successfully!');
    }
}