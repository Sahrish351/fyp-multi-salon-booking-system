<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Show contact form
     */
    public function index()
    {
        return view('frontend.contact');
    }

    /**
     * Send contact message
     */
    public function send(Request $request)
    {
        // Validate the request
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        // ✅ FIXED: Use 'status' instead of 'is_read'
        ContactMessage::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'subject'    => $request->subject,
            'message'    => $request->message,
            'status'     => 'unread',      // ← Changed from 'is_read' to 'status'
            'priority'   => 'medium',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // TODO: Send notification email to admin
        // Mail::to(config('mail.admin_email'))->send(new ContactMessageMail($request->all()));

        return back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}