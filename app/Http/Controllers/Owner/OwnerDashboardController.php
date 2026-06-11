<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerDashboardController extends Controller
{
    public function index()
    {
        // FORCE DUMMY DATA FOR TESTING
        $totalAppointments = 128;
        $pendingAppointments = 15;
        $completedAppointments = 98;
        $approvedAppointments = 15;
        $totalRevenue = 125000;
        $totalClients = 342;
        $totalServices = 24;
        $totalStylists = 8;
        
        // Dummy Data for Recent Appointments
        $recentAppointments = collect([]);
        
        // Dummy Data for Recent Payments
        $recentPayments = collect([]);
        
        // ========== CHART DATA (FORCE DUMMY) ==========
        $appointmentsLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $appointmentsData = [12, 19, 15, 17, 24, 35, 28];
        
        $revenueLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $revenueData = [12500, 18900, 15200, 17800, 24500, 35800, 28900];
        
        $monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        $monthlyData = [125000, 142000, 168000, 195000, 223000, 258000];

        return view('owner.dashboard', compact(
            'totalAppointments', 'pendingAppointments', 'completedAppointments', 'approvedAppointments',
            'totalRevenue', 'totalClients', 'totalServices', 'totalStylists',
            'recentAppointments', 'recentPayments',
            'appointmentsLabels', 'appointmentsData',
            'revenueLabels', 'revenueData',
            'monthlyLabels', 'monthlyData'
        ));
    }
}