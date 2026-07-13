<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Salon;
use App\Models\Appointment;
use App\Helpers\NotificationHelper; 
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

        $appointments = Appointment::where('salon_id', $salon->id)
            ->whereDoesntHave('payment')
            ->with(['client', 'service'])
            ->orderByDesc('appointment_date')
            ->get();

        return view('owner.payments.create', compact('appointments', 'salon'));
    }

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
     * ✅ FIXED: YEH SHOW METHOD SAB FIELDS BHAREGA
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

        // ✅ COMPLETE DATA ARRAY - SAB FIELDS YAHAN SE AAYENGI
        $paymentData = [
            'id' => $paymentModel->id,
            'payment_id' => 'PAY-' . str_pad($paymentModel->id, 3, '0', STR_PAD_LEFT),
            'invoice_no' => $paymentModel->invoice_no ?? 'INV-' . str_pad($paymentModel->id, 4, '0', STR_PAD_LEFT),
            
            // Client Info
            'client_name' => $paymentModel->appointment->client->name ?? 'N/A',
            'client_email' => $paymentModel->appointment->client->email ?? 'N/A',
            'client_phone' => $paymentModel->appointment->client->phone ?? 'N/A',
            
            // Service Info
            'service' => $paymentModel->appointment->service->name ?? 'N/A',
            'service_price' => $paymentModel->appointment->service->price ?? 0,
            'stylist_name' => $paymentModel->appointment->stylist->name ?? 'N/A',
            
            // Payment Info
            'amount' => number_format($paymentModel->amount, 2),
            'method' => ucfirst(str_replace('_', ' ', $paymentModel->method ?? 'N/A')),
            'status' => ucfirst($paymentModel->status ?? 'pending'),
            'transaction_ref' => $paymentModel->transaction_ref ?? 'N/A',
            'sender_number' => $paymentModel->sender_number ?? 'N/A',
            'screenshot' => $paymentModel->screenshot ?? null,
            'rejection_reason' => $paymentModel->rejection_reason ?? null,
            
            // Dates
            'date' => Carbon::parse($paymentModel->created_at)->format('M d, Y'),
            'time' => Carbon::parse($paymentModel->created_at)->format('h:i A'),
            'verified_at' => $paymentModel->verified_at ? Carbon::parse($paymentModel->verified_at)->format('M d, Y h:i A') : null,
            'appointment_date' => $paymentModel->appointment->appointment_date ? Carbon::parse($paymentModel->appointment->appointment_date)->format('M d, Y') : 'N/A',
            'appointment_time' => $paymentModel->appointment->start_time ? Carbon::parse($paymentModel->appointment->start_time)->format('h:i A') : 'N/A',
        ];

        return view('owner.payments.show', [
            'payment' => $paymentData,  // ✅ ARRAY PASS KARO
            'paymentModel' => $paymentModel,
            'salon' => $salon
        ]);
    }

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

        try {
            NotificationHelper::send($salon->id, 'payment_approved', [
                'title'   => '✅ Payment Approved',
                'message' => 'Your payment of PKR ' . $paymentModel->amount . ' has been approved!',
                'link'    => route('client.payments.show', $paymentModel->id),
            ]);
        } catch (\Exception $e) {
            \Log::warning('Payment approved notification failed: ' . $e->getMessage());
        }

        return redirect()->route('owner.payments.show', ['payment' => $payment])
            ->with('success', 'Payment approved!');
    }

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

        try {
            NotificationHelper::send($salon->id, 'payment_rejected', [
                'title'   => '❌ Payment Rejected',
                'message' => 'Your payment of PKR ' . $paymentModel->amount . ' was rejected. Please submit again.',
                'link'    => route('client.payments.show', $paymentModel->id),
            ]);
        } catch (\Exception $e) {
            \Log::warning('Payment rejected notification failed: ' . $e->getMessage());
        }

        return redirect()->route('owner.payments.show', ['payment' => $payment])
            ->with('success', 'Payment rejected.');
    }

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