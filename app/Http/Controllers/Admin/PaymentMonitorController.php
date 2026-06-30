<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Appointment;
use Illuminate\Http\Request;

class PaymentMonitorController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['client', 'salon', 'appointment']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'LIKE', "%{$search}%")
                  ->orWhereHas('client', function ($cq) use ($search) {
                      $cq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        $payments = $query->latest()->paginate(20);

        $stats = [
            'total' => Payment::count(),
            'approved' => Payment::where('status', 'approved')->count(),
            'pending' => Payment::where('status', 'pending')->count(),
            'rejected' => Payment::where('status', 'rejected')->count(),
            'total_amount' => Payment::where('status', 'approved')->sum('amount'),
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    public function show($id)
    {
        $payment = Payment::with(['client', 'appointment.salon', 'appointment.service'])
            ->findOrFail($id);

        return view('admin.payments.show', compact('payment'));
    }

    public function approve($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->update(['status' => 'approved']);

        if ($payment->appointment) {
            $payment->appointment->update(['payment_status' => 'paid']);
        }

        return back()->with('success', 'Payment approved successfully.');
    }

    public function reject($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->update(['status' => 'rejected']);

        return back()->with('success', 'Payment rejected.');
    }

    public function export()
    {
        // Always stream a CSV file, even if there are zero payments —
        // in that case the file will simply contain the header row only.
        $payments = Payment::with(['client', 'salon', 'appointment'])->get();

        $filename = "payments_" . date('Y-m-d') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($payments) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Transaction ID', 'Client', 'Salon', 'Amount', 'Method', 'Status', 'Date']);

            foreach ($payments as $payment) {
                fputcsv($handle, [
                    $payment->transaction_id ?? 'N/A',
                    $payment->client->name ?? 'N/A',
                    $payment->salon->name ?? $payment->appointment->salon->name ?? 'N/A',
                    number_format($payment->amount, 0),
                    $payment->method ?? 'N/A',
                    ucfirst($payment->status),
                    $payment->created_at->format('d M Y'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}