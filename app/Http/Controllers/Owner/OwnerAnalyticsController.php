<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Service;
use App\Models\User;
use App\Models\Salon;

class OwnerAnalyticsController extends Controller
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

        
            $period1 = $request->input('period1', 'yearly');
            $period2 = $request->input('period2', 'yearly');
            $period3 = $request->input('period3', 'yearly');
            $period4 = $request->input('period4', 'yearly');

     
            $stats = $this->getStats($salonId);

           
            $chart1Data = $this->getRevenueProfitData($salonId, $period1);

           
            $chart2Data = $this->getRevenueByServiceData($salonId, $period2);

            
            $chart3Data = $this->getExpensesData($salonId, $period3);

            $chart4Data = $this->getClientGrowthData($salonId, $period4);

            return view('owner.analytics.index', array_merge(
                compact('stats'),
                $chart1Data,
                $chart2Data,
                $chart3Data,
                $chart4Data,
                [
                    'selectedPeriod1' => $period1,
                    'selectedPeriod2' => $period2,
                    'selectedPeriod3' => $period3,
                    'selectedPeriod4' => $period4,
                ]
            ));

        } catch (\Exception $e) {
            Log::error('Analytics Error: ' . $e->getMessage());
            return $this->emptyView();
        }
    }

   
    private function getStats($salonId)
    {
        $totalRevenue = Payment::where('salon_id', $salonId)
            ->where('status', 'approved')
            ->sum('amount');

        $pendingPayments = Payment::where('salon_id', $salonId)
            ->where('status', 'pending')
            ->sum('amount');

        $completedAppointments = Appointment::where('salon_id', $salonId)
            ->where('status', 'completed')
            ->count();

        $activeServices = Service::where('salon_id', $salonId)
            ->where('is_active', true)
            ->count();

        $totalClients = User::where('role', 'client')
            ->whereHas('appointments', function ($query) use ($salonId) {
                $query->where('salon_id', $salonId);
            })
            ->count();

        $totalExpenses = $totalRevenue * 0.4;

        return [
            'total_revenue' => $totalRevenue,
            'pending_payments' => $pendingPayments,
            'completed_appointments' => $completedAppointments,
            'active_services' => $activeServices,
            'net_profit' => $totalRevenue - $totalExpenses,
            'avg_monthly' => $totalRevenue > 0 ? $totalRevenue / 12 : 0,
            'avg_per_client' => $totalClients > 0 ? round($totalRevenue / $totalClients) : 0,
            'total_clients' => $totalClients,
        ];
    }

   
    private function getRevenueProfitData($salonId, $period)
    {
        $labels = [];
        $revenue = [];
        $profit = [];

        switch ($period) {
            case 'weekly':
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::today()->subDays($i);
                    $labels[] = $date->format('D');
                    
                    $dayRevenue = Payment::where('salon_id', $salonId)
                        ->where('status', 'approved')
                        ->whereDate('created_at', $date)
                        ->sum('amount');
                    
                    $revenue[] = (float) $dayRevenue;
                    $profit[] = (float) ($dayRevenue * 0.6);
                }
                break;

            case 'monthly':
                for ($i = 11; $i >= 0; $i--) {
                    $date = Carbon::now()->subMonths($i);
                    $labels[] = $date->format('M');
                    
                    $monthRevenue = Payment::where('salon_id', $salonId)
                        ->where('status', 'approved')
                        ->whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year)
                        ->sum('amount');
                    
                    $revenue[] = (float) $monthRevenue;
                    $profit[] = (float) ($monthRevenue * 0.6);
                }
                break;

            case 'yearly':
            default:
                for ($i = 4; $i >= 0; $i--) {
                    $year = Carbon::now()->subYears($i)->year;
                    $labels[] = (string) $year;
                    
                    $yearRevenue = Payment::where('salon_id', $salonId)
                        ->where('status', 'approved')
                        ->whereYear('created_at', $year)
                        ->sum('amount');
                    
                    $revenue[] = (float) $yearRevenue;
                    $profit[] = (float) ($yearRevenue * 0.6);
                }
                break;
        }

        return [
            'monthLabels' => $labels,
            'revenueData' => $revenue,
            'profitData' => $profit,
        ];
    }

   
    private function getRevenueByServiceData($salonId, $period)
    {
        $query = DB::table('appointments')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->join('payments', 'appointments.id', '=', 'payments.appointment_id')
            ->where('appointments.salon_id', $salonId)
            ->where('payments.status', 'approved');

   
        switch ($period) {
            case 'weekly':
                $query->whereDate('payments.created_at', '>=', Carbon::today()->subDays(6));
                break;
            case 'monthly':
                $query->whereMonth('payments.created_at', Carbon::now()->month)
                      ->whereYear('payments.created_at', Carbon::now()->year);
                break;
            case 'yearly':
            default:
                // No date filter - all time
                break;
        }

        $services = $query->select('services.name', DB::raw('SUM(payments.amount) as total'))
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        return [
            'serviceLabels' => $services->pluck('name')->toArray() ?: ['No Data'],
            'serviceRevenue' => $services->pluck('total')->toArray() ?: [0],
        ];
    }

    private function getExpensesData($salonId, $period)
    {
        $labels = [];
        $data = [];

        switch ($period) {
            case 'weekly':
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::today()->subDays($i);
                    $labels[] = $date->format('D');
                    
                    $dayRevenue = Payment::where('salon_id', $salonId)
                        ->where('status', 'approved')
                        ->whereDate('created_at', $date)
                        ->sum('amount');
                    
                    $data[] = (float) ($dayRevenue * 0.4);
                }
                break;

            case 'monthly':
                for ($i = 11; $i >= 0; $i--) {
                    $date = Carbon::now()->subMonths($i);
                    $labels[] = $date->format('M');
                    
                    $monthRevenue = Payment::where('salon_id', $salonId)
                        ->where('status', 'approved')
                        ->whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year)
                        ->sum('amount');
                    
                    $data[] = (float) ($monthRevenue * 0.4);
                }
                break;

            case 'yearly':
            default:
                for ($i = 4; $i >= 0; $i--) {
                    $year = Carbon::now()->subYears($i)->year;
                    $labels[] = (string) $year;
                    
                    $yearRevenue = Payment::where('salon_id', $salonId)
                        ->where('status', 'approved')
                        ->whereYear('created_at', $year)
                        ->sum('amount');
                    
                    $data[] = (float) ($yearRevenue * 0.4);
                }
                break;
        }

        return [
            'expensesLabels' => $labels,
            'expensesData' => $data,
        ];
    }

  
    private function getClientGrowthData($salonId, $period)
    {
        $labels = [];
        $data = [];
        $cumulative = 0;

        switch ($period) {
            case 'weekly':
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::today()->subDays($i);
                    $labels[] = $date->format('D');
                    
                    $count = User::where('role', 'client')
                        ->whereHas('appointments', function ($query) use ($salonId) {
                            $query->where('salon_id', $salonId);
                        })
                        ->whereDate('created_at', $date)
                        ->count();
                    
                    $cumulative += $count;
                    $data[] = $cumulative;
                }
                break;

            case 'monthly':
                for ($i = 11; $i >= 0; $i--) {
                    $date = Carbon::now()->subMonths($i);
                    $labels[] = $date->format('M');
                    
                    $count = User::where('role', 'client')
                        ->whereHas('appointments', function ($query) use ($salonId) {
                            $query->where('salon_id', $salonId);
                        })
                        ->whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year)
                        ->count();
                    
                    $cumulative += $count;
                    $data[] = $cumulative;
                }
                break;

            case 'yearly':
            default:
                for ($i = 4; $i >= 0; $i--) {
                    $year = Carbon::now()->subYears($i)->year;
                    $labels[] = (string) $year;
                    
                    $count = User::where('role', 'client')
                        ->whereHas('appointments', function ($query) use ($salonId) {
                            $query->where('salon_id', $salonId);
                        })
                        ->whereYear('created_at', $year)
                        ->count();
                    
                    $cumulative += $count;
                    $data[] = $cumulative;
                }
                break;
        }

        return [
            'clientGrowthLabels' => $labels,
            'clientGrowthData' => $data,
        ];
    }

   
    private function emptyView()
    {
        $empty = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        return view('owner.analytics.index', [
            'stats' => [
                'total_revenue' => 0,
                'pending_payments' => 0,
                'completed_appointments' => 0,
                'active_services' => 0,
                'net_profit' => 0,
                'avg_monthly' => 0,
                'avg_per_client' => 0,
                'total_clients' => 0,
            ],
            'monthLabels' => $empty,
            'revenueData' => array_fill(0, 12, 0),
            'profitData' => array_fill(0, 12, 0),
            'serviceLabels' => ['No Data'],
            'serviceRevenue' => [0],
            'expensesLabels' => $empty,
            'expensesData' => array_fill(0, 12, 0),
            'clientGrowthLabels' => $empty,
            'clientGrowthData' => array_fill(0, 12, 0),
            'selectedPeriod1' => 'yearly',
            'selectedPeriod2' => 'yearly',
            'selectedPeriod3' => 'yearly',
            'selectedPeriod4' => 'yearly',
        ]);
    }
}