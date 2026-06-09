<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Waitlist;
use Illuminate\Support\Facades\Auth;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $client = Auth::user();

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

        // ✅ FIX: Use get() instead of take() to get a collection
        $waitlists = Waitlist::with(['salon', 'service'])
            ->where('client_id', $client->id)
            ->where('status', 'waiting')
            ->latest()
            ->get();  // Changed from take(3) to get()

        $stats = [
            'total_bookings'    => Appointment::where('client_id', $client->id)->count(),
            'completed'         => Appointment::where('client_id', $client->id)->where('status', 'completed')->count(),
            'upcoming'          => Appointment::where('client_id', $client->id)->where('appointment_date', '>=', today())->whereNotIn('status', ['cancelled'])->count(),
            'favorite_salons'   => $client->favorites()->count(),
        ];

        // Extra variables for blade template
        $appointments = Appointment::where('client_id', $client->id)->get();
        $waitlistCount = Waitlist::where('client_id', $client->id)->where('status', 'waiting')->count();
        $favoritesCount = $client->favorites()->count();

        return view('client.dashboard', compact(
            'upcomingAppointments', 
            'recentAppointments', 
            'waitlists', 
            'stats',
            'appointments',
            'waitlistCount',
            'favoritesCount'
        ));
    }
}