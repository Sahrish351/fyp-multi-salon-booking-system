<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Waitlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $client = Auth::user();
        $clientId = $client->id;

        $upcomingAppointments = Appointment::with(['salon', 'stylist', 'service', 'payment'])
            ->where('client_id', $clientId)
            ->where('appointment_date', '>=', today())
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->orderBy('appointment_date')
            ->take(5)
            ->get();

        $recentAppointments = Appointment::with(['salon', 'service'])
            ->where('client_id', $clientId)
            ->latest()
            ->take(5)
            ->get();

        $waitlists = Waitlist::with(['salon', 'service'])
            ->where('client_id', $clientId)
            ->where('status', 'waiting')
            ->latest()
            ->get();

        $pendingStatusCandidates = ['pending', 'pending_payment', 'payment_submitted', 'awaiting_payment'];

        $totalBookings   = Appointment::where('client_id', $clientId)->count();
        $completedCount  = Appointment::where('client_id', $clientId)->where('status', 'completed')->count();
        $confirmedCount  = Appointment::where('client_id', $clientId)->where('status', 'confirmed')->count();
        $cancelledCount  = Appointment::where('client_id', $clientId)->where('status', 'cancelled')->count();
        $pendingCount    = Appointment::where('client_id', $clientId)->whereIn('status', $pendingStatusCandidates)->count();

        $upcomingCount = Appointment::where('client_id', $clientId)
            ->where('appointment_date', '>=', today())
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->count();

        $waitlistCount = Waitlist::where('client_id', $clientId)->where('status', 'waiting')->count();

        $complaintsCount = 0;
        if (class_exists(\App\Models\Complaint::class)) {
            try {
                $complaintsCount = \App\Models\Complaint::where('client_id', $clientId)->count();
            } catch (\Throwable $e) {
                $complaintsCount = 0;
            }
        }

        $paymentsCount = Appointment::where('client_id', $clientId)->whereHas('payment')->count();

        // ✅ FIXED: Notification table nahi hai toh 0 set karein
        $alertsCount = 0;

        // ✅ Agar future mein notifications table aaye toh yeh use karein:
        // $alertsCount = method_exists($client, 'unreadNotifications')
        //     ? $client->unreadNotifications()->count()
        //     : 0;

        $stats = [
            'total_bookings'  => $totalBookings,
            'completed'       => $completedCount,
            'favorite_salons' => $client->favorites()->count(),

            'total'      => $totalBookings,
            'upcoming'   => $upcomingCount,
            'pending'    => $pendingCount,
            'waitlist'   => $waitlistCount,
            'complaints' => $complaintsCount,
            'payments'   => $paymentsCount,
            'alerts'     => $alertsCount,
        ];

        $breakdownTotal = max($totalBookings + $waitlistCount, 1);

        $statusBreakdown = [
            'Completed' => round($completedCount / $breakdownTotal * 100),
            'Confirmed' => round($confirmedCount / $breakdownTotal * 100),
            'Pending'   => round($pendingCount / $breakdownTotal * 100),
            'Cancelled' => round($cancelledCount / $breakdownTotal * 100),
            'Upcoming'  => round($upcomingCount / $breakdownTotal * 100),
            'Waitlist'  => round($waitlistCount / $breakdownTotal * 100),
        ];

        $completionPercent = $totalBookings > 0 ? round($completedCount / $totalBookings * 100) : 0;
        $ringCircumference = 251.2;
        $ringDashoffset = $ringCircumference - ($ringCircumference * $completionPercent / 100);

        $now = Carbon::now();

        $donutRanges = [
            'weekly'  => [$now->copy()->startOfWeek(Carbon::MONDAY), $now->copy()->endOfDay()],
            'monthly' => [$now->copy()->startOfMonth(), $now->copy()->endOfDay()],
            'yearly'  => [$now->copy()->startOfYear(), $now->copy()->endOfDay()],
        ];

        $donutData = [];
        foreach ($donutRanges as $periodKey => $range) {
            [$start, $end] = $range;
            $base = Appointment::where('client_id', $clientId)->whereBetween('created_at', [$start, $end]);

            $pComplete  = (clone $base)->where('status', 'completed')->count();
            $pConfirmed = (clone $base)->where('status', 'confirmed')->count();
            $pCancelled = (clone $base)->where('status', 'cancelled')->count();
            $pPending   = (clone $base)->whereIn('status', $pendingStatusCandidates)->count();

            $periodTotal = max($pComplete + $pConfirmed + $pCancelled + $pPending, 1);
            $periodCompletionPercent = round($pComplete / $periodTotal * 100);

            $donutData[$periodKey] = [
                'total'      => $pComplete + $pConfirmed + $pCancelled + $pPending,
                'completion' => $periodCompletionPercent,
                'breakdown'  => [
                    'Completed' => round($pComplete / $periodTotal * 100),
                    'Confirmed' => round($pConfirmed / $periodTotal * 100),
                    'Pending'   => round($pPending / $periodTotal * 100),
                    'Cancelled' => round($pCancelled / $periodTotal * 100),
                    'Upcoming'  => $statusBreakdown['Upcoming'],
                    'Waitlist'  => $statusBreakdown['Waitlist'],
                ],
                'counts' => [
                    'Completed' => $pComplete,
                    'Confirmed' => $pConfirmed,
                    'Pending'   => $pPending,
                    'Cancelled' => $pCancelled,
                    'Upcoming'  => $upcomingCount,
                    'Waitlist'  => $waitlistCount,
                ],
            ];
        }

        $totalRevenue = (float) Appointment::where('client_id', $clientId)
            ->whereNotIn('status', ['cancelled'])
            ->sum('total_amount');

        $paidAmount = 0.0;

        if (Schema::hasTable('payments') && Schema::hasColumn('payments', 'amount')) {
            $paidAmount = (float) \App\Models\Payment::whereHas(
                'appointment',
                fn ($q) => $q->where('client_id', $clientId)
            )->where('status', 'approved')->sum('amount');
        }

        if ($paidAmount <= 0) {
            $fallback = (float) Appointment::where('client_id', $clientId)
                ->whereHas('payment', fn ($q) => $q->where('status', 'approved'))
                ->sum('advance_amount');

            if ($fallback > 0) {
                $paidAmount = $fallback;
            }
        }

        $paidPercent = $totalRevenue > 0 ? round(($paidAmount / $totalRevenue) * 100) : 0;
        $pendingDuesAmount = max($totalRevenue - $paidAmount, 0);
        $pendingDuesPercent = max(100 - $paidPercent, 0);

        $recentPayments = collect();
        if (Schema::hasTable('payments')) {
            $recentPayments = \App\Models\Payment::with('appointment.salon')
                ->whereHas('appointment', fn ($q) => $q->where('client_id', $clientId))
                ->latest()
                ->take(5)
                ->get();
        }

        $startOfWeek = $now->copy()->startOfWeek(Carbon::MONDAY);
        $weeklyLabels = [];
        $weeklyValues = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i);
            $weeklyLabels[] = $day->format('D');
            $weeklyValues[] = Appointment::where('client_id', $clientId)
                ->whereDate('created_at', $day->toDateString())
                ->count();
        }

        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        $monthlyLabels = [];
        $monthlyValues = [];
        $weekCursor = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        $weekNum = 1;
        while ($weekCursor->lte($endOfMonth)) {
            $weekStart = $weekCursor->copy()->max($startOfMonth);
            $weekEnd = $weekCursor->copy()->addDays(6)->min($endOfMonth);
            $monthlyLabels[] = 'Wk ' . $weekNum;
            $monthlyValues[] = Appointment::where('client_id', $clientId)
                ->whereBetween('created_at', [$weekStart->startOfDay(), $weekEnd->endOfDay()])
                ->count();
            $weekCursor->addDays(7);
            $weekNum++;
        }

        $yearlyLabels = [];
        $yearlyValues = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthStart = Carbon::createFromDate($now->year, $m, 1)->startOfDay();
            $monthEnd = $monthStart->copy()->endOfMonth();
            $yearlyLabels[] = $monthStart->format('M');
            $yearlyValues[] = Appointment::where('client_id', $clientId)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();
        }

        $chartData = [
            'weekly'  => ['labels' => $weeklyLabels, 'values' => $weeklyValues],
            'monthly' => ['labels' => $monthlyLabels, 'values' => $monthlyValues],
            'yearly'  => ['labels' => $yearlyLabels, 'values' => $yearlyValues],
        ];

        $appointments = Appointment::where('client_id', $clientId)->get();
        $favoritesCount = $client->favorites()->count();

        return view('client.dashboard', compact(
            'upcomingAppointments',
            'recentAppointments',
            'waitlists',
            'stats',
            'appointments',
            'favoritesCount',
            'statusBreakdown',
            'completionPercent',
            'ringCircumference',
            'ringDashoffset',
            'donutData',
            'totalRevenue',
            'paidAmount',
            'paidPercent',
            'pendingDuesAmount',
            'pendingDuesPercent',
            'recentPayments',
            'chartData'
        ))->with('waitlistCount', $waitlistCount);
    }
}