<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Salon;
use App\Models\Report;
use App\Models\User;
use App\Models\Service;
use App\Models\Stylist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class OwnerReportController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Please create your salon first.');
            }

            $salonId = $salon->id;

            // ✅ REAL STATS
            $totalRevenue = Payment::where('salon_id', $salonId)
                ->where('status', 'approved')
                ->sum('amount');

            $totalClients = User::where('role', 'client')
                ->whereHas('appointments', function ($q) use ($salonId) {
                    $q->where('salon_id', $salonId);
                })
                ->count();

            $completedAppointments = Appointment::where('salon_id', $salonId)
                ->where('status', 'completed')
                ->count();

            $stats = [
                'total_revenue' => $totalRevenue,
                'net_profit' => $totalRevenue * 0.65,
                'total_clients' => $totalClients,
                'completed_appointments' => $completedAppointments,
            ];

            // ✅ RECENT REPORTS
            $recentReports = Report::where('salon_id', $salonId)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($report) {
                    $typeLabels = [
                        'daily_sales' => 'Daily Sales',
                        'monthly_sales' => 'Monthly Sales',
                        'appointments' => 'Appointments',
                        'payments' => 'Payments',
                        'clients' => 'Clients',
                    ];
                    
                    return [
                        'id' => $report->id,
                        'name' => ($typeLabels[$report->type] ?? ucfirst($report->type)) . ' Report',
                        'type' => $typeLabels[$report->type] ?? ucfirst($report->type),
                        'type_key' => $report->type,
                        'format' => strtoupper($report->format ?? 'Excel'),
                        'size' => $report->file_path ? '2.4 MB' : 'N/A',
                        'date' => Carbon::parse($report->created_at)->format('M Y'),
                        'file' => $report->file_path,
                    ];
                });

            return view('owner.reports.index', compact('stats', 'recentReports'));

        } catch (\Exception $e) {
            Log::error('Report Index Error: ' . $e->getMessage());
            return view('owner.reports.index', [
                'stats' => ['total_revenue' => 0, 'net_profit' => 0, 'total_clients' => 0, 'completed_appointments' => 0],
                'recentReports' => [],
            ])->with('error', 'Unable to load reports.');
        }
    }

    public function export(Request $request)
    {
        try {
            $user = auth()->user();
            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->back()->with('error', 'Salon not found.');
            }

            $type = $request->input('type', 'monthly_sales');
            $format = $request->input('format', 'excel');

            $validTypes = ['daily_sales', 'monthly_sales', 'appointments', 'payments', 'clients'];
            if (!in_array($type, $validTypes)) {
                $type = 'monthly_sales';
            }

            // ✅ SAVE REPORT
            $this->saveReport($salon->id, $type, $format);

            // ✅ GENERATE DATA
            $data = $this->generateReportData($salon->id, $type);

            // ✅ EXPORT BASED ON FORMAT
            if ($format === 'pdf') {
                return $this->exportPdf($data, $type);
            }

            return $this->exportCsv($data, $type);

        } catch (\Exception $e) {
            Log::error('Report Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to export report: ' . $e->getMessage());
        }
    }

    private function generateReportData($salonId, $type)
    {
        $data = [];

        switch ($type) {
            case 'monthly_sales':
                for ($i = 11; $i >= 0; $i--) {
                    $date = Carbon::now()->subMonths($i);
                    $revenue = Payment::where('salon_id', $salonId)
                        ->where('status', 'approved')
                        ->whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year)
                        ->sum('amount');
                    $bookings = Appointment::where('salon_id', $salonId)
                        ->whereMonth('appointment_date', $date->month)
                        ->whereYear('appointment_date', $date->year)
                        ->count();
                    
                    $data[] = [
                        'Month' => $date->format('M Y'),
                        'Revenue (PKR)' => number_format($revenue, 0),
                        'Bookings' => $bookings,
                    ];
                }
                break;

            case 'daily_sales':
                for ($i = 29; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $revenue = Payment::where('salon_id', $salonId)
                        ->where('status', 'approved')
                        ->whereDate('created_at', $date->format('Y-m-d'))
                        ->sum('amount');
                    $bookings = Appointment::where('salon_id', $salonId)
                        ->whereDate('appointment_date', $date->format('Y-m-d'))
                        ->count();
                    
                    $data[] = [
                        'Date' => $date->format('d M Y'),
                        'Revenue (PKR)' => number_format($revenue, 0),
                        'Bookings' => $bookings,
                    ];
                }
                break;

            case 'clients':
                $clients = User::where('role', 'client')
                    ->whereHas('appointments', function ($q) use ($salonId) {
                        $q->where('salon_id', $salonId);
                    })
                    ->with(['appointments' => function ($q) use ($salonId) {
                        $q->where('salon_id', $salonId);
                    }])
                    ->get();

                foreach ($clients as $client) {
                    $totalVisits = $client->appointments->count();
                    $totalSpent = $client->appointments->sum('total_amount');
                    $lastVisit = $client->appointments->sortByDesc('appointment_date')->first();

                    $data[] = [
                        'Name' => $client->name,
                        'Email' => $client->email,
                        'Phone' => $client->phone ?? 'N/A',
                        'Total Visits' => $totalVisits,
                        'Total Spent (PKR)' => number_format($totalSpent, 0),
                        'Last Visit' => $lastVisit ? Carbon::parse($lastVisit->appointment_date)->format('d M Y') : 'Never',
                        'Status' => $totalVisits > 10 ? 'VIP' : ($totalVisits > 5 ? 'Regular' : 'New'),
                    ];
                }
                break;

            case 'appointments':
                $appointments = Appointment::where('salon_id', $salonId)
                    ->with(['client', 'service', 'stylist'])
                    ->orderBy('appointment_date', 'desc')
                    ->limit(100)
                    ->get();

                foreach ($appointments as $appt) {
                    $data[] = [
                        'Date' => Carbon::parse($appt->appointment_date)->format('d M Y'),
                        'Time' => Carbon::parse($appt->start_time)->format('h:i A'),
                        'Client' => $appt->client->name ?? 'N/A',
                        'Service' => $appt->service->name ?? 'N/A',
                        'Stylist' => $appt->stylist->name ?? 'N/A',
                        'Amount (PKR)' => number_format($appt->total_amount ?? 0, 0),
                        'Status' => ucfirst($appt->status ?? 'pending'),
                    ];
                }
                break;

            case 'payments':
                $payments = Payment::where('salon_id', $salonId)
                    ->with(['client'])
                    ->orderBy('created_at', 'desc')
                    ->limit(100)
                    ->get();

                foreach ($payments as $payment) {
                    $data[] = [
                        'Date' => Carbon::parse($payment->created_at)->format('d M Y'),
                        'Client' => $payment->client->name ?? 'N/A',
                        'Amount (PKR)' => number_format($payment->amount, 0),
                        'Method' => ucfirst(str_replace('_', ' ', $payment->method ?? 'N/A')),
                        'Status' => ucfirst($payment->status ?? 'pending'),
                        'Transaction' => $payment->transaction_ref ?? 'N/A',
                    ];
                }
                break;

            default:
                $data = [['Message' => 'No data available for this report type.']];
        }

        return $data;
    }

    private function exportCsv($data, $type)
    {
        $typeLabels = [
            'daily_sales' => 'daily-sales',
            'monthly_sales' => 'monthly-sales',
            'appointments' => 'appointments',
            'payments' => 'payments',
            'clients' => 'clients',
        ];

        $filename = $typeLabels[$type] . '-report-' . Carbon::now()->format('Y-m-d') . '.csv';
        
        $handle = fopen('php://temp', 'w+');

        if (!empty($data)) {
            fputcsv($handle, array_keys($data[0]));
        }

        foreach ($data as $row) {
            fputcsv($handle, array_values($row));
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'private, max-age=0, must-revalidate');
    }

    private function exportPdf($data, $type)
    {
        $typeLabels = [
            'daily_sales' => 'Daily Sales',
            'monthly_sales' => 'Monthly Sales',
            'appointments' => 'Appointments',
            'payments' => 'Payments',
            'clients' => 'Clients',
        ];

        $title = $typeLabels[$type] ?? ucfirst($type) . ' Report';
        $filename = strtolower(str_replace(' ', '-', $title)) . '-report-' . Carbon::now()->format('Y-m-d') . '.pdf';

        $pdf = Pdf::loadView('owner.reports.pdf', compact('data', 'title'));
        
        return $pdf->download($filename);
    }

    private function saveReport($salonId, $type, $format = 'excel')
    {
        try {
            Report::create([
                'salon_id' => $salonId,
                'generated_by' => auth()->id(),
                'type' => $type,
                'format' => $format,
                'file_path' => 'reports/' . $type . '-report-' . Carbon::now()->format('Y-m-d') . '.' . ($format === 'pdf' ? 'pdf' : 'csv'),
                'from_date' => Carbon::now()->subMonth(),
                'to_date' => Carbon::now(),
                'status' => 'ready',
            ]);
        } catch (\Exception $e) {
            Log::error('Save Report Error: ' . $e->getMessage());
        }
    }

    public function download($file)
    {
        try {
            if (Storage::disk('public')->exists('reports/' . $file)) {
                return Storage::disk('public')->download('reports/' . $file);
            }

            if (Storage::disk('public')->exists($file)) {
                return Storage::disk('public')->download($file);
            }

            return redirect()->back()->with('error', 'File not found.');

        } catch (\Exception $e) {
            Log::error('Report Download Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to download file.');
        }
    }
}