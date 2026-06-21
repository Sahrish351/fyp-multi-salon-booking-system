<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OwnerDashboardController extends Controller
{
    /**
     * Route: GET /owner/dashboard  -->  name: owner.dashboard
     *
     * Owner ka main Dashboard page.
     *
     * BAAD ME: Database se real queries lagayen, jaise:
     *   $salonId = auth()->user()->salon_id;
     *   $stats['today_appointments'] = Appointment::where('salon_id', $salonId)
     *       ->whereDate('date', today())->count();
     *   $stats['total_revenue'] = Payment::where('salon_id', $salonId)
     *       ->where('status', 'completed')->sum('amount');
     *   ... waghera
     */
    public function index(Request $request)
    {
        // ===================== STAT CARDS DATA =====================
        $stats = [
            'today_appointments'   => 24,
            'pending_appointments' => 8,
            'total_revenue'        => 45280,
            'total_clients'        => 1245,
            'pending_payments'     => 3420,
            'monthly_sales'        => 128450,
        ];

        // ===================== REVENUE TREND CHART (Line/Area) =====================
        $revenueLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        $revenueData   = [24000, 29000, 32000, 35000, 41000, 45280];

        // ===================== MONTHLY BOOKINGS CHART (Bar) =====================
        $bookingsLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        $bookingsData   = [140, 160, 185, 205, 245, 265];

        // ===================== POPULAR SERVICES CHART (Pie) =====================
        $servicesLabels = ['Hair Styling', 'Manicure', 'Facial', 'Massage', 'Makeup'];
        $servicesData   = [35, 25, 20, 15, 5];

        // ===================== CLIENT GROWTH CHART (Line) =====================
        $clientGrowthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        $clientGrowthData   = [780, 850, 920, 1040, 1150, 1245];

        // ===================== TODAY'S APPOINTMENTS TABLE =====================
        $todaysAppointments = [
            ['client' => 'Sarah Johnson', 'service' => 'Hair Styling',      'time' => '10:00 AM', 'stylist' => 'Emma Wilson',     'status' => 'Confirmed'],
            ['client' => 'Michael Chen',  'service' => 'Haircut',          'time' => '11:30 AM', 'stylist' => 'James Brown',     'status' => 'Confirmed'],
            ['client' => 'Emily Davis',   'service' => 'Manicure',         'time' => '02:00 PM', 'stylist' => 'Sophia Lee',      'status' => 'Pending'],
            ['client' => 'David Miller',  'service' => 'Facial Treatment', 'time' => '03:30 PM', 'stylist' => 'Olivia Martinez', 'status' => 'Confirmed'],
            ['client' => 'Lisa Anderson', 'service' => 'Full Body Massage','time' => '04:00 PM', 'stylist' => 'Isabella Garcia', 'status' => 'In Progress'],
        ];

        // ===================== RECENT PAYMENTS TABLE =====================
        $recentPayments = [
            ['client' => 'Sarah Johnson', 'amount' => 120, 'method' => 'Credit Card', 'date' => 'Jun 8, 2026', 'status' => 'Completed'],
            ['client' => 'Michael Chen',  'amount' => 85,  'method' => 'Cash',        'date' => 'Jun 8, 2026', 'status' => 'Completed'],
            ['client' => 'Emily Davis',   'amount' => 95,  'method' => 'Credit Card', 'date' => 'Jun 8, 2026', 'status' => 'Pending'],
            ['client' => 'David Miller',  'amount' => 150, 'method' => 'Debit Card',  'date' => 'Jun 7, 2026', 'status' => 'Completed'],
        ];

        return view('owner.dashboard', compact(
            'stats',
            'revenueLabels', 'revenueData',
            'bookingsLabels', 'bookingsData',
            'servicesLabels', 'servicesData',
            'clientGrowthLabels', 'clientGrowthData',
            'todaysAppointments',
            'recentPayments'
        ));
    }
}
