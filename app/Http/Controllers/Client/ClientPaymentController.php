<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Appointment;
use App\Models\Salon;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OwnerNotificationEmail;
use Barryvdh\DomPDF\Facade\Pdf;

class ClientPaymentController extends Controller
{
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

    public function show(Payment $payment)
    {
        if ($payment->appointment->client_id !== Auth::id()) {
            abort(403);
        }

        $payment->load(['appointment.salon', 'appointment.service']);

        return view('client.payments.show', compact('payment'));
    }

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

        try {
            $client = Auth::user();
            
            // Dashboard Notification
            NotificationHelper::send(
                $appointment->salon_id,
                'payment',
                [
                    'title'   => '💰 New Payment Received',
                    'message' => "{$client->name} made a payment of PKR " . number_format($validated['amount']) . " via " . ucfirst($validated['method']),
                    'link'    => route('owner.payments.show', $payment->id),
                ]
            );

            // Fetch Salon with Owner Relationship explicitly
            $salon = Salon::with('owner')->find($appointment->salon_id);
            
            // Fallback sequence: Owner Email -> Salon Direct Email -> Mail Config
            $ownerEmail = $salon->owner->email ?? $salon->email ?? config('mail.from.address');

            if ($ownerEmail) {
                $emailSubject = "💰 New Payment Submitted: " . $payment->transaction_ref;
                $emailBody = "Client <strong>{$client->name}</strong> ne payment submit ki hai.<br><br>" .
                             "<strong>Amount:</strong> PKR " . number_format($validated['amount']) . "<br>" .
                             "<strong>Payment Method:</strong> " . ucfirst($validated['method']) . "<br>" .
                             "<strong>Transaction Ref:</strong> {$payment->transaction_ref}<br>" .
                             "<strong>Booking Ref:</strong> {$appointment->booking_ref}";

                Mail::to($ownerEmail)->send(new OwnerNotificationEmail($emailSubject, $emailBody));
            }

        } catch (\Exception $e) {
            \Log::error('Payment notification/email error: ' . $e->getMessage());
        }

        return redirect()->route('client.payments.index')
            ->with('success', 'Payment submitted successfully! Waiting for approval.');
    }

    public function downloadReceipt(Payment $payment)
    {
        if ($payment->appointment->client_id !== Auth::id()) {
            abort(403);
        }

        $payment->load(['appointment.salon', 'appointment.service']);

        $pdf = Pdf::loadView('client.payments.receipt', compact('payment'));
        return $pdf->download('receipt-' . $payment->id . '.pdf');
    }
}