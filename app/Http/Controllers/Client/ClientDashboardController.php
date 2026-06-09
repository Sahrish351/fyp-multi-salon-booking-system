<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Waitlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;  // ← SIRF YEH LINE ADD KARNI HAI (Notifications ke liye)

class ClientDashboardController extends Controller
{
    public function index()
    {
        $client = Auth::user();

        // ========== EXISTING CODE (Bilkul same) ==========
        $upcomingAppointments = Appointment::with(['salon', 'stylist', 'service', 'payment'])
            ->where('client_id', $client->id)
            ->whereIn('status', ['confirmed', 'payment_submitted', 'payment_approved'])
            ->where('appointment_date', '>=', today())
            ->orderBy('appointment_date')
            ->take(5)
            ->get();

        $recentAppointments = Appointment::with(['salon', 'service'])
            ->where('client_id', $client->id)
            ->latest()
            ->take(5)
            ->get();

        $waitlists = Waitlist::with(['salon', 'service'])
            ->where('client_id', $client->id)
            ->where('status', 'waiting')
            ->latest()
            ->get();

        $stats = [
            'total_bookings'    => Appointment::where('client_id', $client->id)->count(),
            'completed'         => Appointment::where('client_id', $client->id)->where('status', 'completed')->count(),
            'upcoming'          => Appointment::where('client_id', $client->id)->where('appointment_date', '>=', today())->whereNotIn('status', ['cancelled'])->count(),
            'favorite_salons'   => $client->favorites()->count(),
        ];

        $appointments = Appointment::where('client_id', $client->id)->get();
        $waitlistCount = Waitlist::where('client_id', $client->id)->where('status', 'waiting')->count();
        $favoritesCount = $client->favorites()->count();
        // ========== EXISTING CODE END ==========

        // ========== NEW CODE - NOTIFICATIONS (SIRF YEH ADD KARNA HAI) ==========
        // Apni custom notifications table se data fetch karna
        $notifications = DB::table('notifications')
            ->where('recipient_type', 'client')
            ->orWhere('recipient_type', 'all')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Unread notifications count
        $unreadCount = DB::table('notifications')
            ->where(function($query) {
                $query->where('recipient_type', 'client')
                      ->orWhere('recipient_type', 'all');
            })
            ->where('sent', 1)
            ->whereNull('read_at')
            ->count();
        // ========== NEW CODE END ==========

        return view('client.dashboard', compact(
            'upcomingAppointments', 
            'recentAppointments', 
            'waitlists', 
            'stats',
            'appointments',
            'waitlistCount',
            'favoritesCount',
            'notifications',     // ← YEH ADD KARNA HAI
            'unreadCount'        // ← YEH ADD KARNA HAI
        ));
    }
}