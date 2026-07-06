<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Complaint;
use App\Models\Salon;
use App\Models\Service;
use App\Models\Review;
use App\Models\Waitlist;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Exactly the 7 reports requested — no more, no less.
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

    public function index()
    {
        return view('admin.reports.index', [
            'reportTypes' => $this->reportTypes,
        ]);
    }

    public function preview(Request $request)
    {
        $request->validate([
            'type'      => 'required|in:appointments,payments,revenue,complaints,salons,clients,owners',
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
        ]);

        $report = $this->buildReport($request->type, $request->from_date, $request->to_date, $request->search);

        return view('admin.reports.preview', [
            'report'    => $report,
            'type'      => $request->type,
            'typeLabel' => $this->reportTypes[$request->type]['label'] ?? ucfirst($request->type),
            'fromDate'  => $request->from_date,
            'toDate'    => $request->to_date,
        ]);
    }

    public function export(Request $request)
    {
        $request->validate([
            'type'      => 'required|in:appointments,payments,revenue,complaints,salons,clients,owners',
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
            'format'    => 'required|in:pdf,excel,csv',
        ]);

        $report = $this->buildReport($request->type, $request->from_date, $request->to_date, $request->search);
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

        // Excel / CSV — plain CSV, which Excel opens natively (no extra package needed).
        return response()->streamDownload(function () use ($report) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM so Excel shows special characters correctly
            fputcsv($out, array_column($report['columns'], 'label'));
            foreach ($report['rows'] as $row) {
                fputcsv($out, array_map(fn ($col) => $row[$col['key']] ?? '', $report['columns']));
            }
            fclose($out);
        }, $fileBase . '.csv', ['Content-Type' => 'text/csv']);
    }

    private function buildReport(string $type, string $fromDate, string $toDate, ?string $search = null): array
    {
        $from = Carbon::parse($fromDate)->startOfDay();
        $to   = Carbon::parse($toDate)->endOfDay();

        return match ($type) {
            'appointments' => $this->buildBookingReport($from, $to, $search),
            'payments'     => $this->buildPaymentReport($from, $to, $search),
            'revenue'      => $this->buildRevenueReport($from, $to),
            'complaints'   => $this->buildComplaintReport($from, $to, $search),
            'salons'       => $this->buildSalonReport($from, $to, $search),
            'clients'      => $this->buildClientReport($from, $to, $search),
            'owners'       => $this->buildOwnerReport($from, $to, $search),
            default        => ['columns' => [], 'rows' => [], 'summary' => []],
        };
    }

    // =====================================================================
    // 1. BOOKING REPORT
    // Summary: Total / Pending / Confirmed / Completed / Cancelled / Waitlist
    // Columns: Booking Date, Salon Name, Client Name, Service Name (+ ID, Amount, Status)
    // =====================================================================
    private function buildBookingReport(Carbon $from, Carbon $to, ?string $search): array
    {
        $columns = [
            ['key' => 'id',      'label' => 'ID'],
            ['key' => 'date',    'label' => 'Booking Date'],
            ['key' => 'salon',   'label' => 'Salon Name'],
            ['key' => 'client',  'label' => 'Client Name'],
            ['key' => 'service', 'label' => 'Service Name'],
            ['key' => 'amount',  'label' => 'Amount (Rs.)'],
            ['key' => 'status',  'label' => 'Status'],
        ];

        $base = Appointment::whereBetween('appointment_date', [$from->toDateString(), $to->toDateString()]);

        if ($search) {
            $base->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('client', fn ($c) => $c->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('salon', fn ($s) => $s->where('name', 'like', "%{$search}%"));
            });
        }

        $items = (clone $base)->with(['client', 'salon', 'service'])->latest('appointment_date')->get();

        $rows = $items->map(fn ($a) => [
            'id'      => $a->id,
            'date'    => optional($a->appointment_date)->format('d M Y') ?? $a->appointment_date,
            'salon'   => $a->salon->name ?? 'N/A',
            'client'  => $a->client->name ?? 'N/A',
            'service' => $a->service->name ?? 'N/A',
            'amount'  => number_format($a->total_amount ?? 0),
            'status'  => ucfirst(str_replace('_', ' ', $a->status)),
        ])->all();

        $pendingStatuses = ['pending', 'pending_payment', 'payment_submitted'];

        $waitlistCount = class_exists(Waitlist::class)
            ? Waitlist::whereBetween('created_at', [$from, $to])->count()
            : 0;

        return [
            'columns' => $columns,
            'rows'    => $rows,
            'summary' => [
                'Total Bookings'     => (clone $base)->count(),
                'Pending Bookings'   => (clone $base)->whereIn('status', $pendingStatuses)->count(),
                'Confirmed Bookings' => (clone $base)->where('status', 'confirmed')->count(),
                'Completed Bookings' => (clone $base)->where('status', 'completed')->count(),
                'Cancelled Bookings' => (clone $base)->where('status', 'cancelled')->count(),
                'Waitlist Bookings'  => $waitlistCount,
            ],
        ];
    }

    // =====================================================================
    // 2. PAYMENT REPORT
    // Summary: Total / Successful / Pending / Rejected
    // Columns: Payment Method, Amount, Payment Date, Client Name, Salon Name (+ ID)
    // =====================================================================
    private function buildPaymentReport(Carbon $from, Carbon $to, ?string $search): array
    {
        $columns = [
            ['key' => 'id',     'label' => 'Payment ID'],
            ['key' => 'client', 'label' => 'Client Name'],
            ['key' => 'salon',  'label' => 'Salon Name'],
            ['key' => 'method', 'label' => 'Payment Method'],
            ['key' => 'amount', 'label' => 'Amount (Rs.)'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'date',   'label' => 'Payment Date'],
        ];

        $base = Payment::whereBetween('created_at', [$from, $to]);

        if ($search) {
            $base->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('appointment.client', fn ($c) => $c->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('appointment.salon', fn ($s) => $s->where('name', 'like', "%{$search}%"));
            });
        }

        $items = (clone $base)->with(['appointment.client', 'appointment.salon'])->latest()->get();

        $rows = $items->map(fn ($p) => [
            'id'     => $p->id,
            'client' => optional($p->appointment)->client->name ?? 'N/A',
            'salon'  => optional($p->appointment)->salon->name ?? 'N/A',
            'method' => ucfirst($p->method ?? '—'),
            'amount' => number_format($p->amount ?? 0),
            'status' => ucfirst($p->status),
            'date'   => $p->created_at->format('d M Y'),
        ])->all();

        return [
            'columns' => $columns,
            'rows'    => $rows,
            'summary' => [
                'Total Payments'      => (clone $base)->count(),
                'Successful Payments' => (clone $base)->where('status', 'approved')->count(),
                'Pending Payments'    => (clone $base)->where('status', 'pending')->count(),
                'Rejected Payments'   => (clone $base)->where('status', 'rejected')->count(),
            ],
        ];
    }

    // =====================================================================
    // 3. REVENUE REPORT
    // Summary: Total Revenue / Monthly Revenue / Daily Revenue
    // Table: Revenue by Salon (grouped)
    // =====================================================================
    private function buildRevenueReport(Carbon $from, Carbon $to): array
    {
        $columns = [
            ['key' => 'salon',   'label' => 'Salon Name'],
            ['key' => 'revenue', 'label' => 'Total Revenue (Rs.)'],
        ];

        $approvedInRange = Payment::where('status', 'approved')->whereBetween('created_at', [$from, $to]);

        $totalRevenue   = (clone $approvedInRange)->sum('amount');
        $monthlyRevenue = Payment::where('status', 'approved')
            ->whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)
            ->sum('amount');
        $dailyRevenue = Payment::where('status', 'approved')->whereDate('created_at', now()->toDateString())->sum('amount');

        // Revenue by Salon — group the approved payments in range by the
        // salon their appointment belongs to.
        $bySalon = (clone $approvedInRange)->with('appointment.salon')->get()
            ->groupBy(fn ($p) => optional($p->appointment)->salon->name ?? 'N/A')
            ->map(fn ($group) => $group->sum('amount'))
            ->sortDesc();

        $rows = $bySalon->map(fn ($amount, $salonName) => [
            'salon'   => $salonName,
            'revenue' => 'Rs. ' . number_format($amount),
        ])->values()->all();

        return [
            'columns' => $columns,
            'rows'    => $rows,
            'summary' => [
                'Total Revenue (Range)' => 'Rs. ' . number_format($totalRevenue),
                'Monthly Revenue'       => 'Rs. ' . number_format($monthlyRevenue),
                'Daily Revenue'         => 'Rs. ' . number_format($dailyRevenue),
            ],
        ];
    }

    // =====================================================================
    // 4. COMPLAINT REPORT
    // Summary: Total / Pending / Under Review / Resolved / Rejected
    // Columns: Client Name, Salon Name, Complaint Category, Complaint Date (+ ID, Subject)
    // =====================================================================
    private function buildComplaintReport(Carbon $from, Carbon $to, ?string $search): array
    {
        $columns = [
            ['key' => 'id',       'label' => 'ID'],
            ['key' => 'client',   'label' => 'Client Name'],
            ['key' => 'salon',    'label' => 'Salon Name'],
            ['key' => 'subject',  'label' => 'Subject'],
            ['key' => 'category', 'label' => 'Complaint Category'],
            ['key' => 'status',   'label' => 'Status'],
            ['key' => 'date',     'label' => 'Complaint Date'],
        ];

        if (!class_exists(Complaint::class)) {
            return ['columns' => $columns, 'rows' => [], 'summary' => [
                'Total Complaints' => 0, 'Pending Complaints' => 0, 'Under Review' => 0, 'Resolved Complaints' => 0, 'Rejected Complaints' => 0,
            ]];
        }

        $base = Complaint::whereBetween('created_at', [$from, $to]);

        if ($search) {
            $base->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhereHas('client', fn ($c) => $c->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('salon', fn ($s) => $s->where('name', 'like', "%{$search}%"));
            });
        }

        $items = (clone $base)->with(['client', 'salon'])->latest()->get();

        $rows = $items->map(fn ($c) => [
            'id'       => $c->id,
            'client'   => $c->client->name ?? 'N/A',
            'salon'    => $c->salon->name ?? 'N/A',
            'subject'  => $c->subject,
            'category' => ucfirst(str_replace('_', ' ', $c->type ?? 'general')),
            'status'   => ucfirst(str_replace('_', ' ', $c->status)),
            'date'     => $c->created_at->format('d M Y'),
        ])->all();

        return [
            'columns' => $columns,
            'rows'    => $rows,
            'summary' => [
                'Total Complaints'    => (clone $base)->count(),
                'Pending Complaints'  => (clone $base)->where('status', 'open')->count(),
                'Under Review'        => (clone $base)->where('status', 'in_review')->count(),
                'Resolved Complaints' => (clone $base)->where('status', 'resolved')->count(),
                'Rejected Complaints' => (clone $base)->where('status', 'rejected')->count(),
            ],
        ];
    }

    // =====================================================================
    // 5. SALON REPORT
    // Summary: Total / Active / Pending / Blocked Salons
    // Columns: Salon Name, Owner Name, Total Services, Total Bookings, Total Revenue, Rating
    // =====================================================================
    private function buildSalonReport(Carbon $from, Carbon $to, ?string $search): array
    {
        $columns = [
            ['key' => 'name',     'label' => 'Salon Name'],
            ['key' => 'owner',    'label' => 'Owner Name'],
            ['key' => 'services', 'label' => 'Total Services'],
            ['key' => 'bookings', 'label' => 'Total Bookings'],
            ['key' => 'revenue',  'label' => 'Total Revenue (Rs.)'],
            ['key' => 'rating',   'label' => 'Rating'],
            ['key' => 'reviews',  'label' => 'Total Reviews'],
        ];

        $base = Salon::query();

        if ($search) {
            $base->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('city', 'like', "%{$search}%");
            });
        }

        $salons = (clone $base)->with('owner')->get();

        $rows = $salons->map(fn ($s) => [
            'name'     => $s->name,
            'owner'    => optional($s->owner)->name ?? 'N/A',
            'services' => class_exists(Service::class) ? Service::where('salon_id', $s->id)->count() : 0,
            'bookings' => Appointment::where('salon_id', $s->id)->count(),
            'revenue'  => number_format(
                Payment::where('status', 'approved')->whereHas('appointment', fn ($q) => $q->where('salon_id', $s->id))->sum('amount')
            ),
            'rating'   => class_exists(Review::class)
                ? number_format(Review::where('salon_id', $s->id)->avg('rating') ?? 0, 1)
                : 'N/A',
            'reviews'  => class_exists(Review::class) ? Review::where('salon_id', $s->id)->count() : 0,
        ])->all();

        $statusCounts = $this->salonStatusCounts();

        return [
            'columns' => $columns,
            'rows'    => $rows,
            'summary' => [
                'Total Salons'   => $statusCounts['total'],
                'Active Salons'  => $statusCounts['active'],
                'Pending Salons' => $statusCounts['pending'],
                'Blocked Salons' => $statusCounts['blocked'],
            ],
        ];
    }

    // =====================================================================
    // 6. CLIENT REPORT
    // Summary: Total / Active / Blocked Clients
    // Columns: Client Name, Total Bookings, Completed, Cancelled, Total Spent, Reviews, Complaints
    // =====================================================================
    private function buildClientReport(Carbon $from, Carbon $to, ?string $search): array
    {
        $columns = [
            ['key' => 'id',             'label' => 'Client ID'],
            ['key' => 'name',           'label' => 'Client Name'],
            ['key' => 'salons',         'label' => 'Salon(s) Booked'],
            ['key' => 'bookings',       'label' => 'Total Bookings'],
            ['key' => 'completed',      'label' => 'Completed Bookings'],
            ['key' => 'cancelled',      'label' => 'Cancelled Bookings'],
            ['key' => 'paid',           'label' => 'Amount Paid (Rs.)'],
            ['key' => 'pending_amount', 'label' => 'Amount Pending (Rs.)'],
            ['key' => 'payment_status', 'label' => 'Payment Status'],
            ['key' => 'reviews',        'label' => 'Total Reviews'],
            ['key' => 'complaints',     'label' => 'Total Complaints'],
        ];

        // NOTE: no date-range filter here on purpose. The summary counts
        // below (Total/Active/Blocked Clients) are all-time totals, so the
        // table needs to list ALL matching clients too — otherwise the
        // numbers at the top and the rows in the table disagree (e.g.
        // "5 Active Clients" but an empty table because none of them
        // happened to register within the selected date range).
        $base = User::query();
        $roleColumn = $this->detectRoleColumn();
        if ($roleColumn) {
            $base->where($roleColumn, 'client');
        }
        if ($search) {
            $base->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $clients = (clone $base)->get();

        $rows = $clients->map(function ($u) {
            $salonNames = Appointment::where('client_id', $u->id)
                ->with('salon')
                ->get()
                ->pluck('salon.name')
                ->filter()
                ->unique()
                ->implode(', ');

            $paid = Payment::where('status', 'approved')
                ->whereHas('appointment', fn ($q) => $q->where('client_id', $u->id))
                ->sum('amount');

            $pendingAmount = Payment::where('status', 'pending')
                ->whereHas('appointment', fn ($q) => $q->where('client_id', $u->id))
                ->sum('amount');

            $hasPending = Payment::where('status', 'pending')
                ->whereHas('appointment', fn ($q) => $q->where('client_id', $u->id))->exists();
            $hasRejected = Payment::where('status', 'rejected')
                ->whereHas('appointment', fn ($q) => $q->where('client_id', $u->id))->exists();
            $paymentStatus = $hasPending ? 'Pending' : ($hasRejected ? 'Has Rejected' : 'All Approved');

            return [
                'id'             => $u->id,
                'name'           => $u->name,
                'salons'         => $salonNames ?: '—',
                'bookings'       => Appointment::where('client_id', $u->id)->count(),
                'completed'      => Appointment::where('client_id', $u->id)->where('status', 'completed')->count(),
                'cancelled'      => Appointment::where('client_id', $u->id)->where('status', 'cancelled')->count(),
                'paid'           => number_format($paid),
                'pending_amount' => number_format($pendingAmount),
                'payment_status' => $paymentStatus,
                'reviews'        => class_exists(Review::class) ? Review::where('client_id', $u->id)->count() : 0,
                'complaints'     => class_exists(Complaint::class) ? Complaint::where('client_id', $u->id)->count() : 0,
            ];
        })->all();

        $statusCounts = $this->userStatusCounts('client');

        return [
            'columns' => $columns,
            'rows'    => $rows,
            'summary' => [
                'Total Clients'   => $statusCounts['total'],
                'Active Clients'  => $statusCounts['active'],
                'Blocked Clients' => $statusCounts['blocked'],
            ],
        ];
    }

    // =====================================================================
    // 7. OWNER REPORT
    // Summary: Total / Active / Pending / Blocked Owners
    // Columns (per salon owned): Owner Name, Salon Name, Total Services, Total Bookings, Total Revenue, Total Reviews
    // =====================================================================
    private function buildOwnerReport(Carbon $from, Carbon $to, ?string $search): array
    {
        $columns = [
            ['key' => 'owner',    'label' => 'Owner Name'],
            ['key' => 'salon',    'label' => 'Salon Name'],
            ['key' => 'services', 'label' => 'Total Services'],
            ['key' => 'bookings', 'label' => 'Total Bookings'],
            ['key' => 'revenue',  'label' => 'Total Revenue (Rs.)'],
            ['key' => 'reviews',  'label' => 'Total Reviews'],
        ];

        // One row per salon (each salon has one owner) — this is what lets
        // "Salon Name" appear as its own column per the spec.
        $base = Salon::with('owner');

        if ($search) {
            $base->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('owner', fn ($o) => $o->where('name', 'like', "%{$search}%"));
            });
        }

        $salons = $base->get();

        $rows = $salons->map(fn ($s) => [
            'owner'    => optional($s->owner)->name ?? 'N/A',
            'salon'    => $s->name,
            'services' => class_exists(Service::class) ? Service::where('salon_id', $s->id)->count() : 0,
            'bookings' => Appointment::where('salon_id', $s->id)->count(),
            'revenue'  => number_format(
                Payment::where('status', 'approved')->whereHas('appointment', fn ($q) => $q->where('salon_id', $s->id))->sum('amount')
            ),
            'reviews'  => class_exists(Review::class) ? Review::where('salon_id', $s->id)->count() : 0,
        ])->all();

        $statusCounts = $this->userStatusCounts('owner');

        return [
            'columns' => $columns,
            'rows'    => $rows,
            'summary' => [
                'Total Owners'   => $statusCounts['total'],
                'Active Owners'  => $statusCounts['active'],
                'Pending Owners' => $statusCounts['pending'],
                'Blocked Owners' => $statusCounts['blocked'],
            ],
        ];
    }

    /**
     * Auto-detect which column on `users` distinguishes client/owner/admin,
     * since different projects name this differently. Tries the common
     * conventions in order and uses whichever actually exists — this is
     * safer than assuming 'role' and silently counting everyone as both
     * a client AND an owner if that guess is wrong.
     */
    private function detectRoleColumn(): ?string
    {
        foreach (['role', 'user_type', 'account_type', 'type'] as $candidate) {
            if (Schema::hasColumn('users', $candidate)) {
                return $candidate;
            }
        }
        return null;
    }

    // =====================================================================
    // Helpers — flexible status counting so this works whichever exact
    // status values your `salons` / `users` tables actually use, instead
    // of assuming one spelling and silently showing 0 for everything.
    // =====================================================================
    private function salonStatusCounts(): array
    {
        $total   = Salon::count();
        $active  = Salon::whereIn('status', ['approved', 'active'])->count();
        $pending = Salon::where('status', 'pending')->count();
        $blocked = Salon::whereIn('status', ['suspended', 'blocked', 'rejected'])->count();

        return compact('total', 'active', 'pending', 'blocked');
    }

    private function userStatusCounts(string $role): array
    {
        $query = User::query();
        $roleColumn = $this->detectRoleColumn();
        if ($roleColumn) {
            $query->where($roleColumn, $role);
        }
        $total = (clone $query)->count();

        if (Schema::hasColumn('users', 'status')) {
            $active  = (clone $query)->whereIn('status', ['active', 'approved'])->count();
            $pending = (clone $query)->where('status', 'pending')->count();
            $blocked = (clone $query)->whereIn('status', ['blocked', 'suspended', 'banned'])->count();
        } elseif (Schema::hasColumn('users', 'is_active')) {
            $active  = (clone $query)->where('is_active', true)->count();
            $blocked = (clone $query)->where('is_active', false)->count();
            $pending = 0;
        } else {
            // No status-tracking column found on `users` — rather than guess
            // a wrong column name, count everyone as active so the numbers
            // still add up sensibly.
            $active  = $total;
            $pending = 0;
            $blocked = 0;
        }

        return compact('total', 'active', 'pending', 'blocked');
    }
}