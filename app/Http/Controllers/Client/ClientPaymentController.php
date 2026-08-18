<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Appointment;
use App\Models\Salon;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ClientPaymentController extends Controller
{
    // ── GET /client/payments ─────────────────────────────────────
    public function index(Request $request)
    {
        $baseQuery = Payment::whereHas('appointment', function ($q) {
            $q->where('client_id', Auth::id());
        });

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

    // ── GET /client/payments/{payment} ─────────────────────────────
    public function show(Payment $payment)
    {
        if ($payment->appointment->client_id !== Auth::id()) {
            abort(403);
        }

        $payment->load(['appointment.salon', 'appointment.service']);

        // ✅ Note: Show view par owner ko notification bhejna remove kar diya hai
        return view('client.payments.show', compact('payment'));
    }

    // ── POST /client/payments ─────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'amount'         => 'required|numeric|min:0',
            'method'         => 'required|in:easypaisa,jazzcash,bank,cash,online',
            'screenshot'     => 'nullable|image|max:2048',
            'sender_number'  => 'nullable|string',
        ]);

        $appointment = Appointment::where('client_id', Auth::id())->findOrFail($validated['appointment_id']);

        $payment = Payment::create([
            'appointment_id'  => $appointment->id,
            'client_id'       => Auth::id(),
            'salon_id'        => $appointment->salon_id,
            'amount'          => $validated['amount'],
            'method'          => $validated['method'],
            'status'          => 'pending',
            'transaction_ref' => strtoupper($validated['method']) . '-' . strtoupper(uniqid()),
            'sender_number'   => $validated['sender_number'] ?? null,
        ]);

        if ($request->hasFile('screenshot')) {
            $path = $request->file('screenshot')->store('payments', 'public');
            $payment->update(['screenshot' => $path]);
        }

        // ✅ NOTIFICATION: Owner ko alert jaye ga jab nayi payment submit ho
        try {
            $client = Auth::user();
            
            NotificationHelper::send(
                $appointment->salon_id,
                'payment',
                [
                    'title'   => '💰 New Payment Received',
                    'message' => "{$client->name} made a payment of PKR " . number_format($validated['amount']) . " via " . ucfirst($validated['method']),
                    'link'    => route('owner.payments.show', $payment->id),
                ]
            );
        } catch (\Exception $e) {
            \Log::error('Payment notification error: ' . $e->getMessage());
        }

        return redirect()->route('client.payments.index')
            ->with('success', 'Payment submitted successfully! Waiting for approval.');
    }

    // ── GET /client/payments/{payment}/receipt ────────────────────
    public function downloadReceipt(Payment $payment)
    {
        if ($payment->appointment->client_id !== Auth::id()) {
            abort(403);
        }

        $payment->load(['appointment.salon', 'appointment.service']);

        // ✅ Receipt download par notification spam avoid karne ke liye logic simplify kar diya hai
        $pdf = Pdf::loadView('client.payments.receipt', compact('payment'));
        return $pdf->download('receipt-' . $payment->id . '.pdf');
    }
}