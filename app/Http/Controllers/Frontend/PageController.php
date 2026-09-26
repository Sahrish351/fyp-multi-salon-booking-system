<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Salon;
use App\Models\User;
use App\Models\Appointment;
use App\Models\ContactMessage;
use App\Mail\AdminNewSupportTicketMail;
use App\Helpers\NotificationHelper;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function about()
    {
        $stats = [
            'salons' => Salon::where('status', 'approved')->count() ?: 100,
            'clients' => User::where('role', 'client')->count() ?: 5000,
            'appointments' => Appointment::count() ?: 12500,
            'cities' => 8,
        ];
        return view('frontend.pages.about', compact('stats'));
    }

    public function contact()
    {
        return view('frontend.pages.contact');
    }

    public function partner()
    {
        return view('frontend.pages.partner');
    }

    public function partnerSubmit(Request $request)
    {
        $request->validate([
            'salon_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'message' => 'nullable|string|max:1000',
        ]);

        return redirect()->back()->with('success', 'Thank you for your interest! We will contact you within 24 hours.');
    }

    public function pricing()
    {
        return view('frontend.pages.pricing');
    }

    public function support()
    {
        return view('frontend.pages.support');
    }

    public function supportSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'category' => 'required|string|max:100',
            'message' => 'required|string|min:10|max:1000',
        ]);

        // ✅ Support ticket ko contact_messages table mein save karo
        $ticket = ContactMessage::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'subject'    => 'Support Ticket: ' . $request->category,
            'message'    => $request->message,
            'priority'   => 'high',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // ✅ Har admin ko email + in-app notification bhejo
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new AdminNewSupportTicketMail($ticket));

            NotificationHelper::sendToUser(
                $admin->id,
                0,
                'new_support_ticket',
                [
                    'title' => 'New Support Ticket',
                    'message' => $ticket->name . ': ' . $request->category,
                    'link' => route('admin.contact-messages.show', $ticket->id),
                ]
            );
        }

        return redirect()->back()->with('success', 'Your ticket has been submitted successfully! We will get back to you within 24 hours.');
    }

    public function faq()
    {
        return view('frontend.pages.faq');
    }

    public function privacy()
    {
        return view('frontend.pages.privacy');
    }

    public function terms()
    {
        return view('frontend.pages.terms');
    }

    public function termsOfUse()
    {
        return view('frontend.pages.terms-of-use');
    }

    public function cookies()
    {
        return view('frontend.pages.cookies');
    }
}