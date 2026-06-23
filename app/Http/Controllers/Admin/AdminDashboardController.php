<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Salon;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Complaint;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_salons'       => Salon::where('status', 'approved')->count(),
            'pending_salons'     => Salon::where('status', 'pending')->count(),
            'total_clients'      => User::where('role', 'client')->count(),
            'total_owners'       => User::where('role', 'owner')->count(),
            'total_appointments' => Appointment::count(),
            'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
            'total_payments'     => Payment::where('status', 'approved')->count(),
            'pending_payments'   => Payment::where('status', 'pending')->count(),
            'open_complaints'    => Complaint::where('status', 'open')->count(),
        ];

        $recentSalonRequests = Salon::with('owner')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentAppointments = Appointment::with(['client', 'salon', 'service'])
            ->latest()
            ->take(10)
            ->get();

        $monthlyRevenue = Payment::where('status', 'approved')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');

        return view('admin.dashboard', compact('stats', 'recentSalonRequests', 'recentAppointments', 'monthlyRevenue'));
    }
}