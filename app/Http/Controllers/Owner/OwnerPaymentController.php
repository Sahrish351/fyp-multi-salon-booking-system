<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Salon;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OwnerPaymentController extends Controller
{
    /**
     * Convert a raw Payment model into the exact array shape that
     * owner.payments.index and owner.payments.show blade files expect.
     */
    private function formatPayment(Payment $payment): array
    {
        $appointment = $payment->appointment;
        $client      = $appointment->client ?? null;
        $service     = $appointment->service ?? null;

        $statusMap = [
            'approved' => 'Completed',
            'pending'  => 'Pending',
            'rejected' => 'Failed',
            'refunded' => 'Refunded',
        ];

        return [
            'id'               => $payment->id,
            'appointment_id'   => $payment->appointment_id,
            'payment_id'       => 'PAY-' . str_pad($payment->id, 3, '0', STR_PAD_LEFT),
            'client_name'      => $client->name ?? 'N/A',
            'client_email'     => $client->email ?? 'N/A',
            'service'          => $service->name ?? 'N/A',
            'amount'           => number_format($payment->amount, 2),
            'method'           => ucfirst($payment->method ?? 'N/A'),
            'date'             => $payment->created_at->format('M d, Y'),
            'time'             => $payment->created_at->format('h:i A'),
            'invoice_no'       => 'INV-' . $payment->created_at->format('Y') . '-' . str_pad($payment->id, 3, '0', STR_PAD_LEFT),
            'status'           => $statusMap[$payment->status] ?? ucfirst($payment->status),
            'transaction_ref'  => $payment->transaction_ref ?? 'N/A',
            'sender_number'    => $payment->sender_number ?? null,
            'screenshot'       => $payment->screenshot ? asset('storage/' . $payment->screenshot) : null,
            'verified_at'      => $payment->reviewed_at ? Carbon::parse($payment->reviewed_at)->format('M d, Y') : null,
            'rejection_reason' => $payment->rejection_reason ?? null,
        ];
    }

    /**
     * Display a listing of payments — real data, scoped to this owner's
     * salon only (same pattern as OwnerAppointmentController::index()).
     */
    public function index(Request $request)
    {
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
        $today = Carbon::today();

        $payments = Payment::where('salon_id', $salonId)
            ->with(['appointment.client', 'appointment.service'])
            ->latest()
            ->get()
            ->map(fn ($p) => $this->formatPayment($p));

        $stats = [
            'total_revenue' => Payment::where('salon_id', $salonId)->where('status', 'approved')->sum('amount'),
            'completed'     => Payment::where('salon_id', $salonId)->where('status', 'approved')->count(),
            'pending'       => Payment::where('salon_id', $salonId)->where('status', 'pending')->sum('amount'),
            'today_total'   => Payment::where('salon_id', $salonId)
                                ->where('status', 'approved')
                                ->whereDate('created_at', $today)
                                ->sum('amount'),
        ];

        return view('owner.payments.index', compact('stats', 'payments', 'salon'));
    }

    /**
     * Show the form to manually record a payment (e.g. cash payment)
     * for one of this salon's appointments.
     */
    public function create()
    {
        $user = auth()->user();

        if ($user->role !== 'owner') {
            abort(403, 'Unauthorized access.');
        }

        $salon = Salon::where('owner_id', $user->id)->first();

        if (!$salon) {
            return redirect()->route('owner.salons.create')
                ->with('error', 'Please create your salon first.');
        }

        // Only appointments from this salon that don't already have a payment
        $appointments = Appointment::where('salon_id', $salon->id)
            ->whereDoesntHave('payment')
            ->with(['client', 'service'])
            ->orderByDesc('appointment_date')
            ->get();

        return view('owner.payments.create', compact('appointments', 'salon'));
    }

    /**
     * Store a manually recorded payment.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->role !== 'owner') {
            abort(403, 'Unauthorized access.');
        }

        $salon = Salon::where('owner_id', $user->id)->first();

        if (!$salon) {
            return redirect()->route('owner.salons.create')->with('error', 'Salon not found.');
        }

        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'amount'         => 'required|numeric|min:0',
            'method'         => 'required|in:cash,credit_card,debit_card,online,other',
            'status'         => 'required|in:pending,approved,rejected',
        ]);

        $appointment = Appointment::where('salon_id', $salon->id)->findOrFail($validated['appointment_id']);

        $payment = Payment::create([
            'appointment_id'  => $appointment->id,
            'client_id'       => $appointment->client_id,
            'salon_id'        => $salon->id,
            'amount'          => $validated['amount'],
            'method'          => $validated['method'],
            'status'          => $validated['status'],
            'transaction_ref' => strtoupper($validated['method']) . '-' . strtoupper(uniqid()),
        ]);

        if ($validated['status'] === 'approved') {
            $appointment->update(['status' => 'confirmed']);
        }

        return redirect()->route('owner.payments.index')->with('success', 'Payment recorded successfully!');
    }

    /**
     * Display the specified payment.
     */
    public function show($payment)
    {
        $user = auth()->user();

        if ($user->role !== 'owner') {
            abort(403, 'Unauthorized access.');
        }

        $salon = Salon::where('owner_id', $user->id)->first();

        if (!$salon) {
            return redirect()->route('owner.salons.create')->with('error', 'Salon not found.');
        }

        $paymentModel = Payment::where('salon_id', $salon->id)
            ->with(['appointment.client', 'appointment.service', 'appointment.stylist'])
            ->find($payment);

        if (!$paymentModel) {
            return redirect()->route('owner.payments.index')->with('error', 'Payment not found.');
        }

        return view('owner.payments.show', [
            'payment' => $this->formatPayment($paymentModel),
            'salon'   => $salon,
        ]);
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit($payment)
    {
        $user = auth()->user();

        if ($user->role !== 'owner') {
            abort(403, 'Unauthorized access.');
        }

        $salon = Salon::where('owner_id', $user->id)->first();

        if (!$salon) {
            return redirect()->route('owner.salons.create')->with('error', 'Salon not found.');
        }

        $paymentModel = Payment::where('salon_id', $salon->id)
            ->with(['appointment.client', 'appointment.service'])
            ->find($payment);

        if (!$paymentModel) {
            return redirect()->route('owner.payments.index')->with('error', 'Payment not found.');
        }

        return view('owner.payments.edit', ['payment' => $paymentModel, 'salon' => $salon]);
    }

    /**
     * Update the specified payment.
     */
    public function update(Request $request, $payment)
    {
        $user = auth()->user();

        if ($user->role !== 'owner') {
            abort(403, 'Unauthorized access.');
        }

        $salon = Salon::where('owner_id', $user->id)->first();

        if (!$salon) {
            return redirect()->route('owner.salons.create')->with('error', 'Salon not found.');
        }

        $paymentModel = Payment::where('salon_id', $salon->id)->find($payment);

        if (!$paymentModel) {
            return redirect()->route('owner.payments.index')->with('error', 'Payment not found.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'method' => 'required|in:easypaisa,jazzcash,bank,cash,credit_card,debit_card,online,other',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $paymentModel->update($validated);

        if ($validated['status'] === 'approved' && $paymentModel->appointment) {
            $paymentModel->appointment->update(['status' => 'confirmed']);
        } elseif ($validated['status'] === 'rejected' && $paymentModel->appointment) {
            $paymentModel->appointment->update(['status' => 'pending_payment']);
        }

        return redirect()->route('owner.payments.index')->with('success', 'Payment updated successfully!');
    }

    /**
     * Remove the specified payment.
     */
    public function destroy(Request $request, $payment)
    {
        $user = auth()->user();

        if ($user->role !== 'owner') {
            abort(403, 'Unauthorized access.');
        }

        $salon = Salon::where('owner_id', $user->id)->first();

        if (!$salon) {
            return redirect()->route('owner.salons.create')->with('error', 'Salon not found.');
        }

        $paymentModel = Payment::where('salon_id', $salon->id)->find($payment);

        if (!$paymentModel) {
            return redirect()->route('owner.payments.index')->with('error', 'Payment not found.');
        }

        $paymentModel->delete();

        return redirect()->route('owner.payments.index')->with('success', 'Payment deleted successfully!');
    }

    /**
     * Approve a payment — and confirm the linked appointment, same as
     * OwnerAppointmentController::verifyPayment() does.
     */
    public function approve(Request $request, $payment)
    {
        $user = auth()->user();

        if ($user->role !== 'owner') {
            abort(403, 'Unauthorized access.');
        }

        $salon = Salon::where('owner_id', $user->id)->first();

        if (!$salon) {
            return redirect()->route('owner.salons.create')->with('error', 'Salon not found.');
        }

        $paymentModel = Payment::where('salon_id', $salon->id)->find($payment);

        if (!$paymentModel) {
            return redirect()->route('owner.payments.index')->with('error', 'Payment not found.');
        }

        $paymentModel->update(['status' => 'approved']);

        if ($paymentModel->appointment) {
            $paymentModel->appointment->update(['status' => 'confirmed']);
        }

        return redirect()->route('owner.payments.show', ['payment' => $payment])
            ->with('success', 'Payment approved!');
    }

    /**
     * Reject a payment — and send the linked appointment back to
     * pending_payment, same as OwnerAppointmentController::rejectPayment() does.
     */
    public function reject(Request $request, $payment)
    {
        $user = auth()->user();

        if ($user->role !== 'owner') {
            abort(403, 'Unauthorized access.');
        }

        $salon = Salon::where('owner_id', $user->id)->first();

        if (!$salon) {
            return redirect()->route('owner.salons.create')->with('error', 'Salon not found.');
        }

        $paymentModel = Payment::where('salon_id', $salon->id)->find($payment);

        if (!$paymentModel) {
            return redirect()->route('owner.payments.index')->with('error', 'Payment not found.');
        }

        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $paymentModel->update([
            'status'            => 'rejected',
            'rejection_reason'  => $validated['reason'] ?? null,
        ]);

        if ($paymentModel->appointment) {
            $paymentModel->appointment->update(['status' => 'pending_payment']);
        }

        return redirect()->route('owner.payments.show', ['payment' => $payment])
            ->with('success', 'Payment rejected.');
    }

    /**
     * Export this salon's real payments as CSV.
     */
    public function export(Request $request)
    {
        $user = auth()->user();

        if ($user->role !== 'owner') {
            abort(403, 'Unauthorized access.');
        }

        $salon = Salon::where('owner_id', $user->id)->first();

        if (!$salon) {
            return redirect()->route('owner.salons.create')->with('error', 'Salon not found.');
        }

        $payments = Payment::where('salon_id', $salon->id)
            ->with(['appointment.client', 'appointment.service'])
            ->latest()
            ->get();

        $csv = "Payment ID,Client,Service,Amount,Method,Date,Status\n";
        foreach ($payments as $p) {
            $csv .= $p->id . ",";
            $csv .= (optional($p->appointment)->client->name ?? 'N/A') . ",";
            $csv .= (optional($p->appointment)->service->name ?? 'N/A') . ",";
            $csv .= $p->amount . ",";
            $csv .= $p->method . ",";
            $csv .= $p->created_at->format('M d, Y') . ",";
            $csv .= $p->status . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="payments-' . now()->format('Y-m-d') . '.csv"');
    }
}