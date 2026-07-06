<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // Imported for PDF download

class ClientPaymentController extends Controller
{
    /**
     * GET /client/payments
     * List all payments belonging to the logged-in client's appointments.
     */
    public function index(Request $request)
    {
        $baseQuery = Payment::whereHas('appointment', function ($q) {
            $q->where('client_id', Auth::id());
        });

        // Top card counts (independent of the current filter)
        $counts = [
            'total'     => (clone $baseQuery)->count(),
            'paid'      => (clone $baseQuery)->where('status', 'approved')->count(),
            'pending'   => (clone $baseQuery)->where('status', 'pending')->count(),
            'cancelled' => (clone $baseQuery)->where('status', 'rejected')->count(),
        ];

        $query = (clone $baseQuery)
            ->with(['appointment.salon', 'appointment.service'])
            ->latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $payments = $query->paginate(15)->withQueryString();

        return view('client.payments.index', compact('payments', 'counts'));
    }

    /**
     * GET /client/payments/{payment}
     * Show a single payment's details.
     */
    public function show(Payment $payment)
    {
        // Check if this payment belongs to the logged-in client
        if ($payment->appointment->client_id !== Auth::id()) {
            abort(403);
        }

        // Load related data for the detail page
        $payment->load(['appointment.salon', 'appointment.service']);

        // Return the payment detail view (FIXED: missing brace and return added)
        return view('client.payments.show', compact('payment'));
    }

    /**
     * GET /client/payments/{payment}/receipt
     * Download the payment receipt as a PDF.
     */
    public function downloadReceipt(Payment $payment)
    {
        // Check if this payment belongs to the logged-in client
        if ($payment->appointment->client_id !== Auth::id()) {
            abort(403);
        }

        // Load relationships
        $payment->load(['appointment.salon', 'appointment.service']);

        // Generate and download PDF
        $pdf = Pdf::loadView('client.payments.receipt', compact('payment'));

        return $pdf->download('receipt-' . $payment->id . '.pdf');
    }
}