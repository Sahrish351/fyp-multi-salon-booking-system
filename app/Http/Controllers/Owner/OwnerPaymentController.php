<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Salon;
use App\Models\Service;

class OwnerPaymentController extends Controller
{
    private function getOwnerSalon()
    {
        return Salon::where('owner_id', auth()->id())->first();
    }

    /**
     * Route: GET /owner/payments --> owner.payments.index
     */
    public function index(Request $request)
    {
        try {
            $salon = $this->getOwnerSalon();
            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Please create your salon first.');
            }

            // ── Stats ──────────────────────────────────────────────────
            $stats = [
                'total_revenue' => Payment::where('salon_id', $salon->id)
                                    ->where('status', 'approved')
                                    ->sum('amount'),

                'completed'     => Payment::where('salon_id', $salon->id)
                                    ->where('status', 'approved')
                                    ->sum('amount'),

                'pending'       => Payment::where('salon_id', $salon->id)
                                    ->where('status', 'pending')
                                    ->sum('amount'),

                'today_total'   => Payment::where('salon_id', $salon->id)
                                    ->whereDate('created_at', today())
                                    ->sum('amount'),
            ];

            // ── Payments List ──────────────────────────────────────────
            $paymentsRaw = Payment::where('salon_id', $salon->id)
                ->with(['appointment.service', 'client'])
                ->latest()
                ->get();

            $payments = $paymentsRaw->map(function ($p) {
                // Client name
                $clientName  = optional($p->client)->name ?? 'N/A';
                $clientEmail = optional($p->client)->email ?? 'N/A';

                // Service name appointment se
                $serviceName = optional(optional($p->appointment)->service)->name ?? 'N/A';

                // Status display mapping
                $statusMap = [
                    'pending'   => 'Pending',
                    'approved'  => 'Completed',
                    'rejected'  => 'Failed',
                    'refunded'  => 'Refunded',
                ];
                $status = $statusMap[$p->status] ?? ucfirst($p->status);

                return [
                    'id'           => $p->id,
                    'payment_id'   => 'PAY-' . str_pad($p->id, 3, '0', STR_PAD_LEFT),
                    'client_name'  => $clientName,
                    'client_email' => $clientEmail,
                    'service'      => $serviceName,
                    'amount'       => number_format($p->amount, 2),
                    'method'       => ucfirst($p->method ?? 'N/A'),
                    'transaction_ref' => $p->transaction_ref ?? 'N/A',
                    'sender_number'   => $p->sender_number ?? 'N/A',
                    'screenshot'      => $p->screenshot
                                            ? asset('storage/' . $p->screenshot)
                                            : null,
                    'date'         => optional($p->created_at)->format('M d, Y') ?? 'N/A',
                    'date_raw'     => optional($p->created_at)->format('Y-m-d') ?? '',
                    'time'         => optional($p->created_at)->format('h:i A') ?? 'N/A',
                    'time_raw'     => optional($p->created_at)->format('H:i') ?? '',
                    'invoice_no'   => 'INV-' . ($p->created_at ? $p->created_at->format('Y') : date('Y'))
                                       . '-' . str_pad($p->id, 3, '0', STR_PAD_LEFT),
                    'status'       => $status,
                    'rejection_reason' => $p->rejection_reason,
                    'verified_at'  => $p->verified_at
                                        ? \Carbon\Carbon::parse($p->verified_at)->format('M d, Y h:i A')
                                        : null,
                ];
            })->toArray();

            return view('owner.payments.index', compact('stats', 'payments'));

        } catch (\Exception $e) {
            \Log::error('Payment Index Error: ' . $e->getMessage());
            $stats = ['total_revenue' => 0, 'completed' => 0, 'pending' => 0, 'today_total' => 0];
            $payments = [];
            return view('owner.payments.index', compact('stats', 'payments'))
                ->with('error', 'Could not load payments: ' . $e->getMessage());
        }
    }

    /**
     * Route: GET /owner/payments/create --> owner.payments.create
     */
    public function create()
    {
        $salon    = $this->getOwnerSalon();
        $services = Service::where('salon_id', $salon->id)
                        ->where('is_active', true)
                        ->orderBy('name')
                        ->pluck('name')
                        ->toArray();

        return view('owner.payments.create', compact('services'));
    }

    /**
     * Route: POST /owner/payments --> owner.payments.store
     */
    public function store(Request $request)
    {
        try {
            $salon = $this->getOwnerSalon();

            $request->validate([
                'amount' => 'required|numeric|min:0',
                'method' => 'required|string',
                'status' => 'required|string',
            ]);

            Payment::create([
                'salon_id'      => $salon->id,
                'client_id'     => $request->client_id ?? null,
                'appointment_id'=> $request->appointment_id ?? null,
                'amount'        => $request->amount,
                'method'        => $request->method,
                'transaction_ref' => $request->transaction_ref ?? null,
                'sender_number' => $request->sender_number ?? null,
                'status'        => $request->status,
            ]);

            return redirect()->route('owner.payments.index')
                ->with('success', 'Payment recorded successfully!');

        } catch (\Exception $e) {
            \Log::error('Payment Store Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Could not save: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Route: GET /owner/payments/{payment} --> owner.payments.show
     */
    public function show($id)
    {
        try {
            $salon = $this->getOwnerSalon();

            $p = Payment::where('salon_id', $salon->id)
                    ->with(['appointment.service', 'client'])
                    ->findOrFail($id);

            $clientName  = optional($p->client)->name ?? 'N/A';
            $clientEmail = optional($p->client)->email ?? 'N/A';
            $serviceName = optional(optional($p->appointment)->service)->name ?? 'N/A';

            $statusMap = [
                'pending'  => 'Pending',
                'approved' => 'Completed',
                'rejected' => 'Failed',
                'refunded' => 'Refunded',
            ];
            $status = $statusMap[$p->status] ?? ucfirst($p->status);

            $payment = [
                'id'               => $p->id,
                'payment_id'       => 'PAY-' . str_pad($p->id, 3, '0', STR_PAD_LEFT),
                'client_name'      => $clientName,
                'client_email'     => $clientEmail,
                'service'          => $serviceName,
                'amount'           => number_format($p->amount, 2),
                'method'           => ucfirst($p->method ?? 'N/A'),
                'transaction_ref'  => $p->transaction_ref ?? 'N/A',
                'sender_number'    => $p->sender_number ?? 'N/A',
                'screenshot'       => $p->screenshot
                                        ? asset('storage/' . $p->screenshot)
                                        : null,
                'date'             => optional($p->created_at)->format('M d, Y') ?? 'N/A',
                'date_raw'         => optional($p->created_at)->format('Y-m-d') ?? '',
                'time'             => optional($p->created_at)->format('h:i A') ?? 'N/A',
                'time_raw'         => optional($p->created_at)->format('H:i') ?? '',
                'invoice_no'       => 'INV-' . ($p->created_at ? $p->created_at->format('Y') : date('Y'))
                                       . '-' . str_pad($p->id, 3, '0', STR_PAD_LEFT),
                'status'           => $status,
                'raw_status'       => $p->status,
                'rejection_reason' => $p->rejection_reason,
                'verified_at'      => $p->verified_at
                                        ? \Carbon\Carbon::parse($p->verified_at)->format('M d, Y h:i A')
                                        : null,
            ];

            return view('owner.payments.show', compact('payment'));

        } catch (\Exception $e) {
            \Log::error('Payment Show Error: ' . $e->getMessage());
            return redirect()->route('owner.payments.index')
                ->with('error', 'Payment not found.');
        }
    }

    /**
     * Route: GET /owner/payments/{payment}/edit
     */
    public function edit($id)
    {
        try {
            $salon = $this->getOwnerSalon();
            $p     = Payment::where('salon_id', $salon->id)->findOrFail($id);

            $statusMap = ['pending' => 'Pending', 'approved' => 'Completed', 'rejected' => 'Failed', 'refunded' => 'Refunded'];

            $payment = [
                'id'              => $p->id,
                'payment_id'      => 'PAY-' . str_pad($p->id, 3, '0', STR_PAD_LEFT),
                'client_name'     => optional($p->client)->name ?? '',
                'client_email'    => optional($p->client)->email ?? '',
                'service'         => optional(optional($p->appointment)->service)->name ?? '',
                'amount'          => $p->amount,
                'method'          => $p->method ?? '',
                'transaction_ref' => $p->transaction_ref ?? '',
                'sender_number'   => $p->sender_number ?? '',
                'date_raw'        => optional($p->created_at)->format('Y-m-d') ?? '',
                'time_raw'        => optional($p->created_at)->format('H:i') ?? '',
                'invoice_no'      => 'INV-' . date('Y') . '-' . str_pad($p->id, 3, '0', STR_PAD_LEFT),
                'status'          => $statusMap[$p->status] ?? ucfirst($p->status),
            ];

            $services = Service::where('salon_id', $salon->id)
                            ->where('is_active', true)
                            ->pluck('name')->toArray();

            return view('owner.payments.edit', compact('payment', 'services'));

        } catch (\Exception $e) {
            return redirect()->route('owner.payments.index')->with('error', 'Payment not found.');
        }
    }

    /**
     * Route: PUT /owner/payments/{payment}
     */
    public function update(Request $request, $id)
    {
        try {
            $salon = $this->getOwnerSalon();
            $p     = Payment::where('salon_id', $salon->id)->findOrFail($id);

            $statusReverseMap = ['Completed' => 'approved', 'Pending' => 'pending', 'Failed' => 'rejected', 'Refunded' => 'refunded'];

            $p->update([
                'amount'          => $request->amount,
                'method'          => $request->method,
                'transaction_ref' => $request->transaction_ref,
                'sender_number'   => $request->sender_number,
                'status'          => $statusReverseMap[$request->status] ?? strtolower($request->status),
            ]);

            return redirect()->route('owner.payments.index')
                ->with('success', 'Payment updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Could not update: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Route: DELETE /owner/payments/{payment}
     */
    public function destroy($id)
    {
        try {
            $salon = $this->getOwnerSalon();
            Payment::where('salon_id', $salon->id)->findOrFail($id)->delete();
            return redirect()->route('owner.payments.index')->with('success', 'Payment deleted!');
        } catch (\Exception $e) {
            return redirect()->route('owner.payments.index')->with('error', 'Could not delete payment.');
        }
    }

    /**
     * Route: POST /owner/payments/{payment}/approve
     * Payment approve karna — status 'pending' → 'approved'
     */
    public function approve(Request $request, $id)
    {
        try {
            $salon = $this->getOwnerSalon();
            $p     = Payment::where('salon_id', $salon->id)->findOrFail($id);

            $p->update([
                'status'      => 'approved',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'rejection_reason' => null,
            ]);

            return redirect()->route('owner.payments.show', ['payment' => $id])
                ->with('success', 'Payment approved successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Could not approve payment.');
        }
    }

    /**
     * Route: POST /owner/payments/{payment}/reject
     * Payment reject karna — status 'pending' → 'rejected'
     */
    public function reject(Request $request, $id)
    {
        try {
            $salon = $this->getOwnerSalon();
            $p     = Payment::where('salon_id', $salon->id)->findOrFail($id);

            $p->update([
                'status'           => 'rejected',
                'rejection_reason' => $request->reason ?? 'Rejected by salon owner',
                'verified_by'      => auth()->id(),
                'verified_at'      => now(),
            ]);

            return redirect()->route('owner.payments.show', ['payment' => $id])
                ->with('success', 'Payment rejected.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Could not reject payment.');
        }
    }

    /**
     * Route: GET /owner/payments/export
     * Real CSV export
     */
    public function export(Request $request)
    {
        try {
            $salon    = $this->getOwnerSalon();
            $payments = Payment::where('salon_id', $salon->id)
                            ->with(['client', 'appointment.service'])
                            ->latest()
                            ->get();

            $csv = "Payment ID,Client,Service,Amount,Method,Transaction Ref,Date,Status\n";
            foreach ($payments as $p) {
                $csv .= sprintf(
                    "PAY-%s,%s,%s,%s,%s,%s,%s,%s\n",
                    str_pad($p->id, 3, '0', STR_PAD_LEFT),
                    str_replace(',', ' ', optional($p->client)->name ?? 'N/A'),
                    str_replace(',', ' ', optional(optional($p->appointment)->service)->name ?? 'N/A'),
                    $p->amount,
                    ucfirst($p->method ?? 'N/A'),
                    $p->transaction_ref ?? 'N/A',
                    optional($p->created_at)->format('Y-m-d'),
                    ucfirst($p->status)
                );
            }

            return response($csv)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="payments-' . date('Y-m-d') . '.csv"');

        } catch (\Exception $e) {
            return redirect()->route('owner.payments.index')
                ->with('error', 'Could not export.');
        }
    }
}