<?php
 
namespace App\Http\Controllers\Owner;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
 
class OwnerAnalyticsController extends Controller
{
    
    public function index(Request $request)
    {
        $stats = [
            'total_revenue'  => 206880,
            'net_profit'     => 118180,
            'avg_monthly'    => 34480,
            'avg_per_client' => 166,
        ];
 
        $monthLabels  = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        $revenueData  = [24000, 29000, 32000, 35000, 41000, 45880];
        $profitData   = [14000, 17000, 19000, 21500, 24500, 27180];
 
        $serviceLabels  = ['Hair Styling', 'Manicure/Pedicure', 'Facial Treatment', 'Spa & Massage'];
        $serviceRevenue = [18500, 14200, 11800, 9600];
 
        $expensesLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        $expensesData   = [11200, 12400, 14100, 14800, 15900, 16700];
 
        $clientGrowthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        $clientGrowthData   = [140, 162, 180, 198, 246, 268];
 
        return view('owner.analytics.index', compact(
            'stats',
            'monthLabels', 'revenueData', 'profitData',
            'serviceLabels', 'serviceRevenue',
            'expensesLabels', 'expensesData',
            'clientGrowthLabels', 'clientGrowthData'
        ));
    }
 
  
    public function revenue(Request $request)
    {
        $breakdown = [
            ['date' => '2026-06-01', 'revenue' => 1450],
            ['date' => '2026-06-02', 'revenue' => 1680],
            ['date' => '2026-06-03', 'revenue' => 1320],
            ['date' => '2026-06-04', 'revenue' => 1890],
            ['date' => '2026-06-05', 'revenue' => 2040],
        ];
 
        if ($request->wantsJson()) {
            return response()->json(['breakdown' => $breakdown]);
        }
 
        return view('owner.analytics.index')->with('revenueBreakdown', $breakdown);
    }
}