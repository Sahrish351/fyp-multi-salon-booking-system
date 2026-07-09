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

    // ✅ PAYMENTS KO ARRAY MEIN CONVERT KARO
    $payments = Payment::where('salon_id', $salonId)
        ->with(['appointment.client', 'appointment.service'])
        ->latest()
        ->get()
        ->map(function ($payment) {
            return [
                'id' => $payment->id,
                'payment_id' => 'PAY-' . str_pad($payment->id, 3, '0', STR_PAD_LEFT),
                'client_name' => $payment->appointment->client->name ?? 'N/A',
                'client_email' => $payment->appointment->client->email ?? 'N/A',
                'service' => $payment->appointment->service->name ?? 'N/A',
                'amount' => number_format($payment->amount, 2),
                'method' => ucfirst(str_replace('_', ' ', $payment->method ?? 'N/A')),
                'date' => Carbon::parse($payment->created_at)->format('M d, Y'),
                'time' => Carbon::parse($payment->created_at)->format('h:i A'),
                'invoice_no' => $payment->invoice_no ?? 'N/A',
                'status' => ucfirst($payment->status),
            ];
        });

    // ✅ STATS
    $stats = [
        'total_revenue' => Payment::where('salon_id', $salonId)->where('status', 'approved')->sum('amount'),
        'completed' => Payment::where('salon_id', $salonId)->where('status', 'approved')->count(),
        'pending' => Payment::where('salon_id', $salonId)->where('status', 'pending')->sum('amount'),
        'today_total' => Payment::where('salon_id', $salonId)
            ->where('status', 'approved')
            ->whereDate('created_at', $today)
            ->sum('amount'),
    ];

    return view('owner.payments.index', compact('stats', 'payments', 'salon'));
}
   
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

        return view('owner.payments.show', ['payment' => $paymentModel, 'salon' => $salon]);
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

        $paymentModel->update(['status' => 'rejected']);

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