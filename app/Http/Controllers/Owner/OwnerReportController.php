<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Salon;
use App\Models\Report;
use App\Models\User;
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

            // REAL STATS
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

            // RECENT REPORTS
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
                    
                    // Check if file exists
                    $fileExists = $report->file_path && Storage::disk('public')->exists($report->file_path);
                    
                    return [
                        'id' => $report->id,
                        'name' => ($typeLabels[$report->type] ?? ucfirst($report->type)) . ' Report',
                        'type' => $typeLabels[$report->type] ?? ucfirst($report->type),
                        'type_key' => $report->type,
                        'format' => strtoupper($report->format ?? 'Excel'),
                        'size' => $fileExists ? $this->getFileSize($report->file_path) : 'N/A',
                        'date' => Carbon::parse($report->created_at)->format('M Y'),
                        'file' => $report->file_path,
                        'file_exists' => $fileExists,
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

            // ✅ DATA GENERATE KARO
            $data = $this->generateReportData($salon->id, $type);
            
            // ✅ FILE GENERATE KARO AUR SAVE KARO (YEH IMPORTANT HAI)
            $filePath = $this->generateAndSaveFile($data, $type, $format, $salon->id);

            // ✅ REPORT DATABASE MEIN SAVE KARO
            Report::create([
                'salon_id' => $salon->id,
                'generated_by' => auth()->id(),
                'type' => $type,
                'format' => $format,
                'file_path' => $filePath,
                'from_date' => $request->from_date ?? Carbon::now()->subMonth(),
                'to_date' => $request->to_date ?? Carbon::now(),
                'status' => 'ready',
            ]);

            // ✅ DIRECT DOWNLOAD KARO
            return Storage::disk('public')->download($filePath);

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

    // ✅ YEH NAYA METHOD HAI - FILE GENERATE AUR SAVE KARTA HAI
    private function generateAndSaveFile($data, $type, $format, $salonId)
    {
        $typeLabels = [
            'daily_sales' => 'daily-sales',
            'monthly_sales' => 'monthly-sales',
            'appointments' => 'appointments',
            'payments' => 'payments',
            'clients' => 'clients',
        ];

        $filename = $typeLabels[$type] . '-report-' . Carbon::now()->format('Y-m-d');
        $extension = $format === 'pdf' ? 'pdf' : 'csv';
        $fullFilename = $filename . '.' . $extension;
        $filePath = 'reports/' . $fullFilename;

        // FOLDER BANAO AGAR NAHI HAI
        if (!Storage::disk('public')->exists('reports')) {
            Storage::disk('public')->makeDirectory('reports');
        }

        if ($format === 'pdf') {
            // PDF GENERATE AUR SAVE
            $title = $typeLabels[$type] ?? ucfirst($type) . ' Report';
            $pdf = Pdf::loadView('owner.reports.pdf', compact('data', 'title'));
            $pdfContent = $pdf->output();
            Storage::disk('public')->put($filePath, $pdfContent);
        } else {
            // CSV GENERATE AUR SAVE
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
            Storage::disk('public')->put($filePath, $csv);
        }

        return $filePath;
    }

    public function download($file)
    {
        try {
            $file = basename($file);
            
            // POSSIBLE PATHS CHECK KARO
            $paths = [
                'reports/' . $file,
                'reports/' . $file . '.pdf',
                'reports/' . $file . '.csv',
            ];

            foreach ($paths as $path) {
                if (Storage::disk('public')->exists($path)) {
                    return Storage::disk('public')->download($path);
                }
            }

            return redirect()->back()->with('error', 'File not found. Please generate the report again.');

        } catch (\Exception $e) {
            Log::error('Report Download Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to download file.');
        }
    }

    private function getFileSize($filePath)
    {
        try {
            if (Storage::disk('public')->exists($filePath)) {
                $bytes = Storage::disk('public')->size($filePath);
                if ($bytes >= 1048576) {
                    return number_format($bytes / 1048576, 1) . ' MB';
                } elseif ($bytes >= 1024) {
                    return number_format($bytes / 1024, 1) . ' KB';
                }
                return $bytes . ' B';
            }
        } catch (\Exception $e) {
            Log::error('File size error: ' . $e->getMessage());
        }
        return 'N/A';
    }
}