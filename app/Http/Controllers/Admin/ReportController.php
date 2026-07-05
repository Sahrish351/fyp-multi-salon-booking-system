<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Complaint;
use App\Models\Salon;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * The 7 report types this page supports, and their display info.
     * Adding a new report type later just means adding an entry here
     * and a matching branch inside buildReport().
     */
    protected array $reportTypes = [
        'appointments' => ['label' => 'Booking Report',   'icon' => 'fa-calendar-check',    'color' => 'pk'],
        'payments'     => ['label' => 'Payment Report',   'icon' => 'fa-credit-card',       'color' => 'teal'],
        'revenue'      => ['label' => 'Revenue Report',   'icon' => 'fa-money-bill-wave',   'color' => 'green'],
        'complaints'   => ['label' => 'Complaint Report', 'icon' => 'fa-exclamation-circle','color' => 'amber'],
        'salons'       => ['label' => 'Salon Report',     'icon' => 'fa-store',             'color' => 'purple'],
        'clients'      => ['label' => 'Client Report',    'icon' => 'fa-users',             'color' => 'slate'],
        'owners'       => ['label' => 'Owner Report',     'icon' => 'fa-user-tie',          'color' => 'crimson'],
    ];

    /**
     * Reports index page — one filter/export card per report type.
     * No charts, no summary tiles, no edit/delete/approve/reject —
     * just View, Filter (date range), Search, Export PDF, Export Excel,
     * and Print, exactly as requested.
     */
    public function index()
    {
        return view('admin.reports.index', [
            'reportTypes' => $this->reportTypes,
        ]);
    }

    /**
     * On-screen, printable preview of a report (the "View" action).
     * Uses the exact same data as the PDF/Excel export so what you see
     * here is exactly what you'll get in the downloaded file.
     */
    public function preview(Request $request)
    {
        $request->validate([
            'type'      => 'required|in:appointments,payments,revenue,complaints,salons,clients,owners',
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
        ]);

        $report = $this->buildReport(
            $request->type,
            $request->from_date,
            $request->to_date,
            $request->search
        );

        return view('admin.reports.preview', [
            'report'    => $report,
            'type'      => $request->type,
            'typeLabel' => $this->reportTypes[$request->type]['label'] ?? ucfirst($request->type),
            'fromDate'  => $request->from_date,
            'toDate'    => $request->to_date,
        ]);
    }

    /**
     * Export a report as PDF or Excel (CSV). Both formats are built from
     * the exact same buildReport() data, so the two files always agree.
     */
    public function export(Request $request)
    {
        $request->validate([
            'type'      => 'required|in:appointments,payments,revenue,complaints,salons,clients,owners',
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
            'format'    => 'required|in:pdf,excel,csv',
        ]);

        $report = $this->buildReport(
            $request->type,
            $request->from_date,
            $request->to_date,
            $request->search
        );

        $fileBase = 'glamora-' . $request->type . '-report-' . now()->format('Y-m-d');

        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('pdf.admin-report', [
                'report'    => $report,
                'type'      => $request->type,
                'typeLabel' => $this->reportTypes[$request->type]['label'] ?? ucfirst($request->type),
                'fromDate'  => $request->from_date,
                'toDate'    => $request->to_date,
            ])->setPaper('a4', 'landscape');

            return $pdf->download($fileBase . '.pdf');
        }

        // Excel / CSV — implemented as CSV, which Excel opens natively
        // without needing the maatwebsite/excel package installed.
        return response()->streamDownload(function () use ($report) {
            $out = fopen('php://output', 'w');
            // Excel needs a UTF-8 BOM to display special characters (Rs., etc) correctly
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, array_column($report['columns'], 'label'));
            foreach ($report['rows'] as $row) {
                fputcsv($out, array_map(fn ($col) => $row[$col['key']] ?? '', $report['columns']));
            }
            fclose($out);
        }, $fileBase . '.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * Builds a uniform ['columns' => [...], 'rows' => [...], 'summary' => [...]]
     * structure for any of the 7 report types. Every row is a plain
     * associative array keyed exactly like $columns, so the PDF/preview/
     * CSV export can all loop over it identically without type-specific code.
     */
    private function buildReport(string $type, string $fromDate, string $toDate, ?string $search = null): array
    {
        $from = Carbon::parse($fromDate)->startOfDay();
        $to   = Carbon::parse($toDate)->endOfDay();

        return match ($type) {
            'appointments' => $this->buildAppointmentsReport($from, $to, $search),
            'payments'     => $this->buildPaymentsReport($from, $to, $search, false),
            'revenue'      => $this->buildPaymentsReport($from, $to, $search, true),
            'complaints'   => $this->buildComplaintsReport($from, $to, $search),
            'salons'       => $this->buildSalonsReport($from, $to, $search),
            'clients'      => $this->buildUsersReport($from, $to, $search, 'client'),
            'owners'       => $this->buildUsersReport($from, $to, $search, 'owner'),
            default        => ['columns' => [], 'rows' => [], 'summary' => []],
        };
    }

    private function buildAppointmentsReport(Carbon $from, Carbon $to, ?string $search): array
    {
        $columns = [
            ['key' => 'id',      'label' => 'ID'],
            ['key' => 'client',  'label' => 'Client'],
            ['key' => 'salon',   'label' => 'Salon'],
            ['key' => 'service', 'label' => 'Service'],
            ['key' => 'date',    'label' => 'Date'],
            ['key' => 'amount',  'label' => 'Amount (Rs.)'],
            ['key' => 'status',  'label' => 'Status'],
        ];

        $query = Appointment::with(['client', 'salon', 'service'])
            ->whereBetween('appointment_date', [$from->toDateString(), $to->toDateString()]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('client', fn ($c) => $c->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('salon', fn ($s) => $s->where('name', 'like', "%{$search}%"));
            });
        }

        $items = $query->latest('appointment_date')->get();

        $rows = $items->map(fn ($a) => [
            'id'      => $a->id,
            'client'  => $a->client->name ?? 'N/A',
            'salon'   => $a->salon->name ?? 'N/A',
            'service' => $a->service->name ?? 'N/A',
            'date'    => optional($a->appointment_date)->format('d M Y') ?? $a->appointment_date,
            'amount'  => number_format($a->total_amount ?? 0),
            'status'  => ucfirst(str_replace('_', ' ', $a->status)),
        ])->all();

        return [
            'columns' => $columns,
            'rows'    => $rows,
            'summary' => [
                'Total Bookings' => $items->count(),
                'Total Amount'   => 'Rs. ' . number_format($items->sum('total_amount')),
            ],
        ];
    }

    private function buildPaymentsReport(Carbon $from, Carbon $to, ?string $search, bool $revenueOnly): array
    {
        $columns = [
            ['key' => 'id',     'label' => 'Payment ID'],
            ['key' => 'client', 'label' => 'Client'],
            ['key' => 'salon',  'label' => 'Salon'],
            ['key' => 'method', 'label' => 'Method'],
            ['key' => 'amount', 'label' => 'Amount (Rs.)'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'date',   'label' => 'Date'],
        ];

        $query = Payment::with(['appointment.client', 'appointment.salon'])
            ->whereBetween('created_at', [$from, $to]);

        if ($revenueOnly) {
            $query->where('status', 'approved');
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('appointment.client', fn ($c) => $c->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('appointment.salon', fn ($s) => $s->where('name', 'like', "%{$search}%"));
            });
        }

        $items = $query->latest()->get();

        $rows = $items->map(fn ($p) => [
            'id'     => $p->id,
            'client' => optional($p->appointment)->client->name ?? 'N/A',
            'salon'  => optional($p->appointment)->salon->name ?? 'N/A',
            'method' => ucfirst($p->method ?? '—'),
            'amount' => number_format($p->amount ?? 0),
            'status' => ucfirst($p->status),
            'date'   => $p->created_at->format('d M Y'),
        ])->all();

        $countLabel = $revenueOnly ? 'Approved Payments' : 'Total Payments';

        return [
            'columns' => $columns,
            'rows'    => $rows,
            'summary' => [
                $countLabel     => $items->count(),
                'Total Amount'  => 'Rs. ' . number_format($items->sum('amount')),
            ],
        ];
    }

    private function buildComplaintsReport(Carbon $from, Carbon $to, ?string $search): array
    {
        $columns = [
            ['key' => 'id',      'label' => 'ID'],
            ['key' => 'client',  'label' => 'Client'],
            ['key' => 'salon',   'label' => 'Salon'],
            ['key' => 'subject', 'label' => 'Subject'],
            ['key' => 'status',  'label' => 'Status'],
            ['key' => 'date',    'label' => 'Filed On'],
        ];

        if (!class_exists(Complaint::class)) {
            return ['columns' => $columns, 'rows' => [], 'summary' => ['Total Complaints' => 0]];
        }

        $query = Complaint::with(['client', 'salon'])->whereBetween('created_at', [$from, $to]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhereHas('client', fn ($c) => $c->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('salon', fn ($s) => $s->where('name', 'like', "%{$search}%"));
            });
        }

        $items = $query->latest()->get();

        $rows = $items->map(fn ($c) => [
            'id'      => $c->id,
            'client'  => $c->client->name ?? 'N/A',
            'salon'   => $c->salon->name ?? 'N/A',
            'subject' => $c->subject,
            'status'  => ucfirst(str_replace('_', ' ', $c->status)),
            'date'    => $c->created_at->format('d M Y'),
        ])->all();

        return [
            'columns' => $columns,
            'rows'    => $rows,
            'summary' => ['Total Complaints' => $items->count()],
        ];
    }

    private function buildSalonsReport(Carbon $from, Carbon $to, ?string $search): array
    {
        $columns = [
            ['key' => 'id',     'label' => 'ID'],
            ['key' => 'name',   'label' => 'Salon Name'],
            ['key' => 'city',   'label' => 'City'],
            ['key' => 'owner',  'label' => 'Owner'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'date',   'label' => 'Registered On'],
        ];

        $query = Salon::whereBetween('created_at', [$from, $to]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $items = $query->latest()->get();

        $rows = $items->map(fn ($s) => [
            'id'     => $s->id,
            'name'   => $s->name,
            'city'   => $s->city ?? 'N/A',
            'owner'  => optional($s->owner)->name ?? 'N/A',
            'status' => ucfirst($s->status ?? 'N/A'),
            'date'   => $s->created_at->format('d M Y'),
        ])->all();

        return [
            'columns' => $columns,
            'rows'    => $rows,
            'summary' => ['Total Salons' => $items->count()],
        ];
    }

    private function buildUsersReport(Carbon $from, Carbon $to, ?string $search, string $role): array
    {
        $columns = [
            ['key' => 'id',    'label' => 'ID'],
            ['key' => 'name',  'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'phone', 'label' => 'Phone'],
            ['key' => 'date',  'label' => 'Registered On'],
        ];

        $query = User::whereBetween('created_at', [$from, $to]);

        if (Schema::hasColumn('users', 'role')) {
            $query->where('role', $role);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $items = $query->latest()->get();

        $rows = $items->map(fn ($u) => [
            'id'    => $u->id,
            'name'  => $u->name,
            'email' => $u->email,
            'phone' => $u->phone ?? 'N/A',
            'date'  => $u->created_at->format('d M Y'),
        ])->all();

        return [
            'columns' => $columns,
            'rows'    => $rows,
            'summary' => [($role === 'client' ? 'Total Clients' : 'Total Owners') => $items->count()],
        ];
    }
}