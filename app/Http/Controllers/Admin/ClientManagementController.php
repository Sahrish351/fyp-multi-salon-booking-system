<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ClientManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'client')->withCount('appointments');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->status === 'active')    $query->where('is_active', true);
        if ($request->status === 'suspended') $query->where('is_active', false);
        if ($request->city)                   $query->where('city', $request->city);

        match ($request->sort) {
            'oldest'   => $query->oldest(),
            'name_asc' => $query->orderBy('name'),
            'bookings' => $query->orderByDesc('appointments_count'),
            default    => $query->latest(),
        };

        $clients = $query->paginate(20)->withQueryString();

        $cities = User::where('role', 'client')
                      ->whereNotNull('city')
                      ->distinct()
                      ->pluck('city')
                      ->sort()
                      ->values();

        return view('admin.clients.index', compact('clients', 'cities'));
    }

    public function show(User $user)
    {
        $user->load('appointments.salon', 'appointments.service', 'payments', 'reviews', 'complaints');
        $client = $user;
        return view('admin.clients.show', compact('client'));
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $msg = $user->is_active ? 'Client activated.' : 'Client suspended.';
        return back()->with('success', $msg);
    }

    // ── Export Excel (CSV) — no package needed ──
    public function export(Request $request)
    {
        $clients = $this->getExportQuery($request)->get();

        $filename = 'clients_' . now()->format('Y_m_d_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($clients) {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM — Excel ke liye zaroori
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, ['ID', 'Name', 'Email', 'Phone', 'City', 'Total Bookings', 'Status', 'Auth Provider', 'Joined Date']);
            foreach ($clients as $client) {
                fputcsv($handle, [
                    $client->id,
                    $client->name,
                    $client->email,
                    $client->phone ?? '',
                    $client->city ?? '',
                    $client->appointments_count ?? 0,
                    $client->is_active ? 'Active' : 'Suspended',
                    ucfirst($client->auth_provider ?? 'email'),
                    $client->created_at->format('d M Y'),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ── Export PDF — DomPDF use karta hai ──
    public function exportPdf(Request $request)
    {
        $clients  = $this->getExportQuery($request)->get();
        $filename = 'clients_' . now()->format('Y_m_d_His') . '.pdf';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.clients.export_pdf', compact('clients'))
                   ->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    // ── Shared query for both exports ──
    private function getExportQuery(Request $request)
    {
        $query = User::where('role', 'client')->withCount('appointments');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->status === 'active')    $query->where('is_active', true);
        if ($request->status === 'suspended') $query->where('is_active', false);
        if ($request->city)                   $query->where('city', $request->city);

        return $query->latest();
    }
}