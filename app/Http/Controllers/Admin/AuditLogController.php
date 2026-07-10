<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user');

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', '%' . $search . '%')
                    ->orWhere('module', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('ip_address', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($sub) use ($search) {
                        $sub->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->action && $request->action != '') {
            $query->where('action', $request->action);
        }

        if ($request->role && $request->role != '') {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->status && $request->status != '') {
            $query->where('status', $request->status);
        }

        $logs = $query->latest()->paginate(30);
        $actions = AuditLog::distinct()->pluck('action');

        return view('admin.audit-logs.index', compact('logs', 'actions'));
    }

    public function show($id)
    {
        $log = AuditLog::with('user')->findOrFail($id);
        return view('admin.audit-logs.show', compact('log'));
    }

    // ============================================================ //
    // EXPORT CSV - ALWAYS WORKS (Even with no records)
    // ============================================================ //
    public function exportCsv(Request $request)
    {
        $logs = $this->getFilteredLogs($request);

        $filename = 'audit-logs-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, [
                'Log ID', 'User', 'Role', 'Action', 'Module',
                'Description', 'IP Address', 'Status', 'Date & Time'
            ]);

            // If records exist, add them
            if ($logs->count() > 0) {
                foreach ($logs as $log) {
                    fputcsv($file, [
                        $log->id,
                        $log->user->name ?? 'System',
                        $log->user->role ?? 'N/A',
                        $log->action,
                        $log->module ?? 'N/A',
                        $log->description ?? 'N/A',
                        $log->ip_address ?? 'N/A',
                        $log->status ?? 'Success',
                        $log->created_at->format('d M Y, h:i A'),
                    ]);
                }
            } else {
                // No records - add a message row
                fputcsv($file, ['No audit logs found for the selected filters']);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ============================================================ //
    // EXPORT PDF - ALWAYS WORKS (Even with no records)
    // ============================================================ //
    public function exportPdf(Request $request)
    {
        $logs = $this->getFilteredLogs($request);

        $html = view('admin.audit-logs.export-pdf', [
            'logs' => $logs,
            'title' => 'Audit Logs Report',
            'date' => date('d M Y, h:i A'),
            'total' => $logs->count(),
        ])->render();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('audit-logs-' . date('Y-m-d') . '.pdf');
    }

    public function destroy($id)
    {
        $log = AuditLog::findOrFail($id);
        $log->delete();

        return redirect()->route('admin.audit-logs.index')
            ->with('success', 'Audit log deleted successfully.');
    }

    private function getFilteredLogs($request)
    {
        $query = AuditLog::with('user');

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', '%' . $search . '%')
                    ->orWhere('module', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($sub) use ($search) {
                        $sub->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->action && $request->action != '') {
            $query->where('action', $request->action);
        }

        if ($request->role && $request->role != '') {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->status && $request->status != '') {
            $query->where('status', $request->status);
        }

        return $query->latest()->get();
    }
}