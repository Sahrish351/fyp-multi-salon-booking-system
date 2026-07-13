<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Salon;
use App\Models\Service;
use App\Models\Stylist;
use App\Models\User;
use App\Models\Review;
use App\Models\Complaint;  // ✅ YEH ADD KIYA HAI

class OwnerDashboardController extends Controller
{
    
    public function index(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return redirect()->route('login');
            }

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Please create your salon first.');
            }

            $salonId = $salon->id;
            $cacheKey = "owner_dashboard_{$salonId}";

            $dashboardData = Cache::remember($cacheKey, 300, function () use ($salonId) {
                $stats = $this->getStats($salonId);
                
                return [
                    'stats' => $stats,
                    'complaint_stats' => $stats['complaint_stats'],  // ✅ YEH ADD KIYA HAI
                    'recent_complaints' => $stats['recent_complaints'],  // ✅ YEH ADD KIYA HAI
                    'revenueLabels' => $this->getRevenueLabels(),
                    'revenueData' => $this->getRevenueData($salonId),
                    'bookingsLabels' => $this->getBookingsLabels(),
                    'bookingsData' => $this->getBookingsData($salonId),
                    'servicesLabels' => $this->getServicesLabels($salonId),
                    'servicesData' => $this->getServicesData($salonId),
                    'clientGrowthLabels' => $this->getClientGrowthLabels(),
                    'clientGrowthData' => $this->getClientGrowthData($salonId),
                    'todaysAppointments' => $this->getTodayAppointments($salonId),
                    'recentPayments' => $this->getRecentPayments($salonId),
                ];
            });

            return view('owner.dashboard', array_merge(
                $dashboardData,
                ['salon' => $salon]
            ));

        } catch (\Exception $e) {
            \Log::error('Owner Dashboard Error: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('owner.dashboard')->with([
                'error' => 'Unable to load dashboard data. Please try again later.',
                'stats' => $this->getEmptyStats(),
                'complaint_stats' => $this->getEmptyComplaintStats(),  // ✅ YEH ADD KIYA HAI
                'recent_complaints' => [],  // ✅ YEH ADD KIYA HAI
                'revenueLabels' => $this->getRevenueLabels(),
                'revenueData' => array_fill(0, 12, 0),
                'bookingsLabels' => $this->getBookingsLabels(),
                'bookingsData' => array_fill(0, 12, 0),
                'servicesLabels' => ['No Data Available'],
                'servicesData' => [1],
                'clientGrowthLabels' => $this->getClientGrowthLabels(),
                'clientGrowthData' => array_fill(0, 12, 0),
                'todaysAppointments' => [],
                'recentPayments' => [],
                'salon' => $salon ?? null,
            ]);
        }
    }

  
    private function getStats($salonId)
    {
        $today = Carbon::today();
        $now = Carbon::now();

        $todayAppointments = Appointment::where('salon_id', $salonId)
            ->whereDate('appointment_date', $today)
            ->count();

        $pendingAppointments = Appointment::where('salon_id', $salonId)
            ->where('status', 'pending_payment')
            ->count();

        $totalRevenue = Payment::where('salon_id', $salonId)
            ->where('status', 'approved')
            ->sum('amount');

        $totalClients = User::where('role', 'client')
            ->whereHas('appointments', function ($query) use ($salonId) {
                $query->where('salon_id', $salonId);
            })
            ->count();

        $pendingPayments = Payment::where('salon_id', $salonId)
            ->where('status', 'pending')
            ->sum('amount');

        $monthlySales = Payment::where('salon_id', $salonId)
            ->where('status', 'approved')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('amount');

        $lastMonthSales = Payment::where('salon_id', $salonId)
            ->where('status', 'approved')
            ->whereMonth('created_at', $now->copy()->subMonth()->month)
            ->whereYear('created_at', $now->copy()->subMonth()->year)
            ->sum('amount');

        $yesterdayAppointments = Appointment::where('salon_id', $salonId)
            ->whereDate('appointment_date', $today->copy()->subDay())
            ->count();

        $todayTrend = $yesterdayAppointments > 0 ? round((($todayAppointments - $yesterdayAppointments) / $yesterdayAppointments) * 100) : 0;
        $revenueTrend = $totalRevenue > 0 ? round(($totalRevenue / max(Payment::where('salon_id', $salonId)->where('status', 'approved')->sum('amount') - $totalRevenue, 1)) * 100) : 0;
        $clientTrend = $totalClients > 0 ? round(($totalClients / max(User::where('role', 'client')->whereHas('appointments', function($q) use ($salonId) { $q->where('salon_id', $salonId); })->count() - $totalClients, 1)) * 100) : 0;
        $salesTrend = $monthlySales > 0 && $lastMonthSales > 0 ? round((($monthlySales - $lastMonthSales) / $lastMonthSales) * 100) : 0;

        // ✅ COMPLAINT STATS - YEH NAYA HAI
        $complaintStats = [
            'total' => Complaint::where('salon_id', $salonId)->count(),
            'pending' => Complaint::where('salon_id', $salonId)->where('status', 'pending')->count(),
            'in_progress' => Complaint::where('salon_id', $salonId)->where('status', 'in_progress')->count(),
            'resolved' => Complaint::where('salon_id', $salonId)->where('status', 'resolved')->count(),
            'escalated' => Complaint::where('salon_id', $salonId)->where('status', 'escalated')->count(),
            'closed' => Complaint::where('salon_id', $salonId)->where('status', 'closed')->count(),
            'rejected' => Complaint::where('salon_id', $salonId)->where('status', 'rejected')->count(),
        ];

        // ✅ RECENT COMPLAINTS - YEH NAYA HAI
        $recentComplaints = Complaint::where('salon_id', $salonId)
            ->with(['client'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return [
            'today_appointments' => $todayAppointments,
            'pending_appointments' => $pendingAppointments,
            'total_revenue' => $totalRevenue,
            'total_clients' => $totalClients,
            'pending_payments' => $pendingPayments,
            'monthly_sales' => $monthlySales,
            'today_trend' => $todayTrend,
            'revenue_trend' => $revenueTrend,
            'client_trend' => $clientTrend,
            'sales_trend' => $salesTrend,
            'total_appointments' => Appointment::where('salon_id', $salonId)->count(),
            'completed_appointments' => Appointment::where('salon_id', $salonId)->where('status', 'completed')->count(),
            'cancelled_appointments' => Appointment::where('salon_id', $salonId)->where('status', 'cancelled')->count(),
            'active_services' => Service::where('salon_id', $salonId)->where('is_active', true)->count(),
            'total_stylists' => Stylist::where('salon_id', $salonId)->where('is_active', true)->count(),
            'complaint_stats' => $complaintStats,  // ✅ YEH NAYA HAI
            'recent_complaints' => $recentComplaints,  // ✅ YEH NAYA HAI
        ];
    }

   
    private function getRevenueData($salonId)
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            
            $total = Payment::where('salon_id', $salonId)
                ->where('status', 'approved')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('amount');
            
            $data[] = (float) number_format($total, 0, '.', '');
        }
        return $data;
    }

    private function getBookingsData($salonId)
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            
            $total = Appointment::where('salon_id', $salonId)
                ->whereMonth('appointment_date', $month->month)
                ->whereYear('appointment_date', $month->year)
                ->count();
            
            $data[] = (int) $total;
        }
        return $data;
    }

    
    private function getServicesData($salonId)
    {
        $services = DB::table('appointments')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->where('appointments.salon_id', $salonId)
            ->select(
                'services.name',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('total')
            ->limit(6)
            ->pluck('total', 'name')
            ->toArray();

        if (empty($services)) {
            return [1];
        }

        return array_values($services);
    }

   
    private function getServicesLabels($salonId)
    {
        $labels = DB::table('appointments')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->where('appointments.salon_id', $salonId)
            ->select('services.name')
            ->groupBy('services.id', 'services.name')
            ->orderByDesc(DB::raw('COUNT(*)'))
            ->limit(6)
            ->pluck('name')
            ->toArray();

        return !empty($labels) ? $labels : ['No Data Available'];
    }

   
    private function getClientGrowthData($salonId)
    {
        $data = [];
        $cumulative = 0;
        
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            
            $newClients = User::where('role', 'client')
                ->whereHas('appointments', function ($query) use ($salonId) {
                    $query->where('salon_id', $salonId);
                })
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
            
            $cumulative += $newClients;
            $data[] = $cumulative;
        }
        
        return $data;
    }

 
    private function getTodayAppointments($salonId)
    {
        $today = Carbon::today();

        $appointments = Appointment::where('salon_id', $salonId)
            ->whereDate('appointment_date', $today)
            ->with(['client', 'service', 'stylist'])
            ->orderBy('start_time')
            ->limit(10)
            ->get();

        if ($appointments->isEmpty()) {
            return [];
        }

        $statusMap = [
            'pending_payment' => 'Pending',
            'confirmed' => 'Confirmed',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'in_progress' => 'In Progress',
        ];

        $badgeMap = [
            'pending_payment' => 'badge-pending',
            'confirmed' => 'badge-confirmed',
            'completed' => 'badge-completed',
            'cancelled' => 'badge-cancelled',
            'in_progress' => 'badge-progress',
        ];

        return $appointments->map(function ($appointment) use ($statusMap, $badgeMap) {
            $status = $statusMap[$appointment->status] ?? ucfirst($appointment->status);
            $badge = $badgeMap[$appointment->status] ?? 'badge-pending';

            return [
                'client' => $appointment->client->name ?? 'N/A',
                'service' => $appointment->service->name ?? 'N/A',
                'time' => Carbon::parse($appointment->start_time)->format('g:i A'),
                'stylist' => $appointment->stylist->name ?? 'N/A',
                'status' => $status,
                'status_badge' => $badge,
                'booking_ref' => $appointment->booking_ref ?? 'N/A',
            ];
        })->toArray();
    }

   
    private function getRecentPayments($salonId)
    {
        $payments = Payment::where('salon_id', $salonId)
            ->with(['client', 'appointment'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        if ($payments->isEmpty()) {
            return [];
        }

        $badgeMap = [
            'pending' => 'badge-pending',
            'approved' => 'badge-completed',
            'rejected' => 'badge-cancelled',
        ];

        return $payments->map(function ($payment) use ($badgeMap) {
            return [
                'client' => $payment->client->name ?? 'N/A',
                'amount' => number_format($payment->amount, 0),
                'method' => ucfirst(str_replace('_', ' ', $payment->method ?? 'N/A')),
                'date' => Carbon::parse($payment->created_at)->format('M d, Y'),
                'status' => ucfirst($payment->status),
                'status_badge' => $badgeMap[$payment->status] ?? 'badge-pending',
                'transaction_ref' => $payment->transaction_ref ?? 'N/A',
            ];
        })->toArray();
    }

   
    private function getRevenueLabels()
    {
        $labels = [];
        for ($i = 11; $i >= 0; $i--) {
            $labels[] = Carbon::now()->subMonths($i)->format('M');
        }
        return $labels;
    }

  
    private function getBookingsLabels()
    {
        return $this->getRevenueLabels();
    }

  
    private function getClientGrowthLabels()
    {
        return $this->getRevenueLabels();
    }

  
    private function getEmptyStats()
    {
        return [
            'today_appointments' => 0,
            'pending_appointments' => 0,
            'total_revenue' => 0,
            'total_clients' => 0,
            'pending_payments' => 0,
            'monthly_sales' => 0,
            'today_trend' => 0,
            'revenue_trend' => 0,
            'client_trend' => 0,
            'sales_trend' => 0,
            'total_appointments' => 0,
            'completed_appointments' => 0,
            'cancelled_appointments' => 0,
            'active_services' => 0,
            'total_stylists' => 0,
        ];
    }

    // ✅ YEH NAYA METHOD HAI - EMPTY COMPLAINT STATS KE LIYE
    private function getEmptyComplaintStats()
    {
        return [
            'total' => 0,
            'pending' => 0,
            'in_progress' => 0,
            'resolved' => 0,
            'escalated' => 0,
            'closed' => 0,
            'rejected' => 0,
        ];
    }

   
    public function getChartData(Request $request)
    {
        try {
            $period = $request->get('period', 'monthly');
            $type = $request->get('type', 'revenue');
            
            $user = auth()->user();
            
            if (!$user) {
                return response()->json(['labels' => [], 'values' => []]);
            }
            
            $salon = Salon::where('owner_id', $user->id)->first();
            
            if (!$salon) {
                return response()->json(['labels' => [], 'values' => []]);
            }
            
            $salonId = $salon->id;
            $labels = [];
            $values = [];
            
            if ($period === 'weekly') {
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::today()->subDays($i);
                    $labels[] = $date->format('D');
                    
                    if ($type === 'revenue') {
                        $values[] = (float) Payment::where('salon_id', $salonId)
                            ->where('status', 'approved')
                            ->whereDate('created_at', $date)
                            ->sum('amount');
                    } else {
                        $values[] = (int) Appointment::where('salon_id', $salonId)
                            ->whereDate('appointment_date', $date)
                            ->count();
                    }
                }
            } elseif ($period === 'monthly') {
                for ($i = 11; $i >= 0; $i--) {
                    $date = Carbon::now()->subMonths($i);
                    $labels[] = $date->format('M');
                    
                    if ($type === 'revenue') {
                        $values[] = (float) Payment::where('salon_id', $salonId)
                            ->where('status', 'approved')
                            ->whereMonth('created_at', $date->month)
                            ->whereYear('created_at', $date->year)
                            ->sum('amount');
                    } else {
                        $values[] = (int) Appointment::where('salon_id', $salonId)
                            ->whereMonth('appointment_date', $date->month)
                            ->whereYear('appointment_date', $date->year)
                            ->count();
                    }
                }
            } else {
                for ($i = 4; $i >= 0; $i--) {
                    $year = Carbon::now()->subYears($i)->year;
                    $labels[] = (string) $year;
                    
                    if ($type === 'revenue') {
                        $values[] = (float) Payment::where('salon_id', $salonId)
                            ->where('status', 'approved')
                            ->whereYear('created_at', $year)
                            ->sum('amount');
                    } else {
                        $values[] = (int) Appointment::where('salon_id', $salonId)
                            ->whereYear('appointment_date', $year)
                            ->count();
                    }
                }
            }
            
            return response()->json([
                'labels' => $labels,
                'values' => $values
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Chart Data Error: ' . $e->getMessage());
            return response()->json(['labels' => [], 'values' => []]);
        }
    }

 
    public function refresh(Request $request)
    {
        $user = auth()->user();
        $salon = Salon::where('owner_id', $user->id)->first();
        
        if ($salon) {
            Cache::forget("owner_dashboard_{$salon->id}");
            return redirect()->route('owner.dashboard')
                ->with('success', 'Dashboard data refreshed successfully.');
        }
        
        return redirect()->route('owner.dashboard')
            ->with('error', 'Unable to refresh dashboard.');
    }

   
    public function getNotifications(Request $request)
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json(['count' => 0, 'notifications' => []]);
            }

            $notifications = $user->notifications()->take(10)->get();
            $unreadCount = $user->unreadNotifications()->count();

            return response()->json([
                'count' => $unreadCount,
                'notifications' => $notifications->map(function ($notif) {
                    return [
                        'id' => $notif->id,
                        'data' => $notif->data,
                        'read_at' => $notif->read_at,
                        'created_at' => $notif->created_at->diffForHumans(),
                    ];
                })
            ]);

        } catch (\Exception $e) {
            \Log::error('Notifications Error: ' . $e->getMessage());
            return response()->json(['count' => 0, 'notifications' => []]);
        }
    }

   
    public function markNotificationRead($id)
    {
        try {
            $user = auth()->user();
            $notification = $user->notifications()->find($id);
            
            if ($notification) {
                $notification->markAsRead();
                return response()->json(['success' => true]);
            }
            
            return response()->json(['success' => false], 404);

        } catch (\Exception $e) {
            \Log::error('Mark Notification Error: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

   
    public function markAllNotificationsRead(Request $request)
    {
        try {
            $user = auth()->user();
            $user->unreadNotifications->markAsRead();
            
            return redirect()->back()->with('success', 'All notifications marked as read.');

        } catch (\Exception $e) {
            \Log::error('Mark All Notifications Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to mark notifications as read.');
        }
    }
}