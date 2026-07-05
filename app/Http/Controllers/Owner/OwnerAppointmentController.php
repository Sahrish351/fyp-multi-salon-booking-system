<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Models\Appointment;
use App\Models\Salon;
use App\Models\User;
use App\Models\Service;
use App\Models\Stylist;
use App\Models\Payment;

class OwnerAppointmentController extends Controller
{
    /**
     * Display a listing of appointments.
     */
    public function index(Request $request)
    {
        try {
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

            $appointments = Appointment::where('salon_id', $salonId)
                ->with(['client', 'service', 'stylist', 'payment'])
                ->orderBy('appointment_date', 'desc')
                ->orderBy('start_time', 'desc')
                ->get();

            $totalToday = Appointment::where('salon_id', $salonId)
                ->whereDate('appointment_date', $today)
                ->count();

            $confirmed = Appointment::where('salon_id', $salonId)
                ->where('status', 'confirmed')
                ->count();

            $pending = Appointment::where('salon_id', $salonId)
                ->where('status', 'pending_payment')
                ->count();

            $revenueToday = Appointment::where('salon_id', $salonId)
                ->whereDate('appointment_date', $today)
                ->whereHas('payment', function ($query) {
                    $query->where('status', 'approved');
                })
                ->sum('total_amount');

            $stylists = Stylist::where('salon_id', $salonId)
                ->where('is_active', true)
                ->pluck('name')
                ->toArray();

            $stats = [
                'total_today' => $totalToday,
                'confirmed' => $confirmed,
                'pending' => $pending,
                'revenue_today' => $revenueToday,
            ];

            return view('owner.appointments.index', [
                'stats' => $stats,
                'appointments' => $appointments,
                'stylists' => $stylists,
                'salon' => $salon,
            ]);

        } catch (\Exception $e) {
            \Log::error('Appointment Index Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to load appointments.');
        }
    }

    /**
     * Show the form for creating a new appointment.
     */
    public function create()
    {
        try {
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

            $services = Service::where('salon_id', $salonId)
                ->where('is_active', true)
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();

            $stylists = Stylist::where('salon_id', $salonId)
                ->where('is_active', true)
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();

            // ✅ FIXED: 'salons' (plural) - User model ke hisaab se
            $clients = User::where('role', 'client')
                ->whereHas('salons', function ($query) use ($salonId) {
                    $query->where('id', $salonId);
                })
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();

            return view('owner.appointments.create', [
                'services' => $services,
                'stylists' => $stylists,
                'clients' => $clients,
                'salon' => $salon,
            ]);

        } catch (\Exception $e) {
            \Log::error('Appointment Create Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to load create page: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created appointment.
     */
    public function store(Request $request)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Salon not found.');
            }

            $validator = Validator::make($request->all(), [
                'client_id' => 'required|exists:users,id',
                'service_id' => 'required|exists:services,id',
                'stylist_id' => 'required|exists:stylists,id',
                'appointment_date' => 'required|date|after_or_equal:today',
                'start_time' => 'required',
                'end_time' => 'required|after:start_time',
                'total_amount' => 'required|numeric|min:0',
                'advance_amount' => 'nullable|numeric|min:0',
                'status' => 'required|in:pending_payment,confirmed,in_progress,completed,cancelled',
                'notes' => 'nullable|string|max:500',
                'payment_status' => 'nullable|in:pending,approved,rejected',
                'payment_method' => 'nullable|in:cash,credit_card,debit_card,online,other',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $status = $request->status;
            if ($request->payment_status === 'approved' && $request->total_amount > 0) {
                $status = 'confirmed';
            }

            $appointment = Appointment::create([
                'salon_id' => $salon->id,
                'client_id' => $request->client_id,
                'service_id' => $request->service_id,
                'stylist_id' => $request->stylist_id,
                'appointment_date' => $request->appointment_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'total_amount' => $request->total_amount,
                'advance_amount' => $request->advance_amount ?? 0,
                'status' => $status,
                'notes' => $request->notes,
                'booking_ref' => 'BK-' . strtoupper(uniqid()),
            ]);

            if ($request->payment_status === 'approved' && $request->total_amount > 0) {
                Payment::create([
                    'appointment_id' => $appointment->id,
                    'client_id' => $request->client_id,
                    'salon_id' => $salon->id,
                    'amount' => $request->total_amount,
                    'method' => $request->payment_method ?? 'cash',
                    'status' => 'approved',
                    'payment_date' => now(),
                    'transaction_ref' => 'CASH-' . strtoupper(uniqid()),
                ]);
            }

            return redirect()->route('owner.appointments.index')
                ->with('success', 'Appointment booked successfully!');

        } catch (\Exception $e) {
            \Log::error('Appointment Store Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to book appointment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified appointment.
     */
    public function show($appointment)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Salon not found.');
            }

            $appointment = Appointment::where('salon_id', $salon->id)
                ->with(['client', 'service', 'stylist', 'payment'])
                ->find($appointment);

            if (!$appointment) {
                return redirect()->route('owner.appointments.index')
                    ->with('error', 'Appointment not found.');
            }

            $statusDisplay = [
                'pending_payment' => 'Pending',
                'confirmed' => 'Confirmed',
                'in_progress' => 'In Progress',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
            ];

            $appointmentData = [
                'id' => $appointment->id,
                'client_name' => $appointment->client->name ?? 'N/A',
                'client_email' => $appointment->client->email ?? 'N/A',
                'client_phone' => $appointment->client->phone ?? 'N/A',
                'service' => $appointment->service->name ?? 'N/A',
                'stylist' => $appointment->stylist->name ?? 'N/A',
                'date' => $appointment->appointment_date->format('M d, Y'),
                'date_raw' => $appointment->appointment_date->format('Y-m-d'),
                'time_range' => Carbon::parse($appointment->start_time)->format('g:i A') . ' - ' . Carbon::parse($appointment->end_time)->format('g:i A'),
                'start_time_raw' => $appointment->start_time,
                'end_time_raw' => $appointment->end_time,
                'price' => $appointment->total_amount,
                'advance_amount' => $appointment->advance_amount,
                'status' => $statusDisplay[$appointment->status] ?? ucfirst($appointment->status),
                'status_raw' => $appointment->status,
                'notes' => $appointment->notes,
                'booking_ref' => $appointment->booking_ref,
                'payment_status' => $appointment->payment->status ?? 'pending',
                'payment_method' => $appointment->payment->method ?? 'N/A',
            ];

            return view('owner.appointments.show', [
                'appointment' => $appointmentData,
                'salon' => $salon,
            ]);

        } catch (\Exception $e) {
            \Log::error('Appointment Show Error: ' . $e->getMessage());
            return redirect()->route('owner.appointments.index')
                ->with('error', 'Appointment not found.');
        }
    }

    /**
     * Show the form for editing the specified appointment.
     */
    public function edit($appointment)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Salon not found.');
            }

            $appointment = Appointment::where('salon_id', $salon->id)
                ->with(['client', 'service', 'stylist', 'payment'])
                ->find($appointment);

            if (!$appointment) {
                return redirect()->route('owner.appointments.index')
                    ->with('error', 'Appointment not found.');
            }

            $salonId = $salon->id;

            $services = Service::where('salon_id', $salonId)
                ->where('is_active', true)
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();

            $stylists = Stylist::where('salon_id', $salonId)
                ->where('is_active', true)
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();

            // ✅ FIXED: 'salons' (plural) - User model ke hisaab se
            $clients = User::where('role', 'client')
                ->whereHas('salons', function ($query) use ($salonId) {
                    $query->where('id', $salonId);
                })
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();

            $appointmentData = [
                'id' => $appointment->id,
                'client_name' => $appointment->client->name ?? 'N/A',
                'client_email' => $appointment->client->email ?? 'N/A',
                'client_phone' => $appointment->client->phone ?? 'N/A',
                'client_id' => $appointment->client_id,
                'service_id' => $appointment->service_id,
                'stylist_id' => $appointment->stylist_id,
                'service' => $appointment->service->name ?? 'N/A',
                'stylist' => $appointment->stylist->name ?? 'N/A',
                'date' => $appointment->appointment_date->format('M d, Y'),
                'date_raw' => $appointment->appointment_date->format('Y-m-d'),
                'time_range' => Carbon::parse($appointment->start_time)->format('g:i A') . ' - ' . Carbon::parse($appointment->end_time)->format('g:i A'),
                'start_time_raw' => $appointment->start_time,
                'end_time_raw' => $appointment->end_time,
                'price' => $appointment->total_amount,
                'advance_amount' => $appointment->advance_amount,
                'status' => $appointment->status,
                'notes' => $appointment->notes,
                'payment_status' => $appointment->payment->status ?? 'pending',
                'payment_method' => $appointment->payment->method ?? 'cash',
            ];

            return view('owner.appointments.edit', [
                'appointment' => $appointmentData,
                'services' => $services,
                'stylists' => $stylists,
                'clients' => $clients,
                'salon' => $salon,
            ]);

        } catch (\Exception $e) {
            \Log::error('Appointment Edit Error: ' . $e->getMessage());
            return redirect()->route('owner.appointments.index')
                ->with('error', 'Appointment not found.');
        }
    }

    /**
     * Update the specified appointment.
     */
    public function update(Request $request, $appointment)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Salon not found.');
            }

            $appointment = Appointment::where('salon_id', $salon->id)->find($appointment);

            if (!$appointment) {
                return redirect()->route('owner.appointments.index')
                    ->with('error', 'Appointment not found.');
            }

            $validator = Validator::make($request->all(), [
                'client_id' => 'required|exists:users,id',
                'service_id' => 'required|exists:services,id',
                'stylist_id' => 'required|exists:stylists,id',
                'appointment_date' => 'required|date',
                'start_time' => 'required',
                'end_time' => 'required|after:start_time',
                'total_amount' => 'required|numeric|min:0',
                'advance_amount' => 'nullable|numeric|min:0',
                'status' => 'required|in:pending_payment,confirmed,in_progress,completed,cancelled',
                'notes' => 'nullable|string|max:500',
                'payment_status' => 'nullable|in:pending,approved,rejected',
                'payment_method' => 'nullable|in:cash,credit_card,debit_card,online,other',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $status = $request->status;
            if ($request->payment_status === 'approved' && $request->total_amount > 0) {
                $status = 'confirmed';
            }

            $appointment->update([
                'client_id' => $request->client_id,
                'service_id' => $request->service_id,
                'stylist_id' => $request->stylist_id,
                'appointment_date' => $request->appointment_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'total_amount' => $request->total_amount,
                'advance_amount' => $request->advance_amount ?? 0,
                'status' => $status,
                'notes' => $request->notes,
            ]);

            if ($request->payment_status === 'approved' && $request->total_amount > 0) {
                $payment = Payment::where('appointment_id', $appointment->id)->first();
                if ($payment) {
                    $payment->update([
                        'amount' => $request->total_amount,
                        'method' => $request->payment_method ?? 'cash',
                        'status' => 'approved',
                    ]);
                } else {
                    Payment::create([
                        'appointment_id' => $appointment->id,
                        'client_id' => $request->client_id,
                        'salon_id' => $salon->id,
                        'amount' => $request->total_amount,
                        'method' => $request->payment_method ?? 'cash',
                        'status' => 'approved',
                        'payment_date' => now(),
                        'transaction_ref' => 'CASH-' . strtoupper(uniqid()),
                    ]);
                }
            }

            return redirect()->route('owner.appointments.index')
                ->with('success', 'Appointment updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Appointment Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to update appointment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified appointment.
     */
    public function destroy($appointment)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Salon not found.');
            }

            $appointment = Appointment::where('salon_id', $salon->id)->find($appointment);

            if (!$appointment) {
                return redirect()->route('owner.appointments.index')
                    ->with('error', 'Appointment not found.');
            }

            if ($appointment->payment) {
                $appointment->payment->delete();
            }

            $appointment->delete();

            return redirect()->route('owner.appointments.index')
                ->with('success', 'Appointment deleted successfully!');

        } catch (\Exception $e) {
            \Log::error('Appointment Delete Error: ' . $e->getMessage());
            return redirect()->route('owner.appointments.index')
                ->with('error', 'Unable to delete appointment.');
        }
    }

    /**
     * Approve/Confirm Appointment
     */
    public function approve(Request $request, $id)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Salon not found.');
            }

            $appointment = Appointment::where('salon_id', $salon->id)->find($id);

            if (!$appointment) {
                return redirect()->route('owner.appointments.index')
                    ->with('error', 'Appointment not found.');
            }

            $appointment->status = 'confirmed';
            $appointment->save();

            return redirect()->route('owner.appointments.show', ['appointment' => $id])
                ->with('success', 'Appointment confirmed successfully!');

        } catch (\Exception $e) {
            \Log::error('Appointment Confirm Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to confirm appointment.');
        }
    }

    /**
     * Mark Appointment as Completed
     */
    public function complete(Request $request, $id)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Salon not found.');
            }

            $appointment = Appointment::where('salon_id', $salon->id)->find($id);

            if (!$appointment) {
                return redirect()->route('owner.appointments.index')
                    ->with('error', 'Appointment not found.');
            }

            $appointment->status = 'completed';
            $appointment->save();

            return redirect()->route('owner.appointments.show', ['appointment' => $id])
                ->with('success', 'Appointment marked as completed!');

        } catch (\Exception $e) {
            \Log::error('Appointment Complete Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to complete appointment.');
        }
    }

    /**
     * Reject/Cancel Appointment
     */
    public function cancel(Request $request, $id)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Salon not found.');
            }

            $appointment = Appointment::where('salon_id', $salon->id)->find($id);

            if (!$appointment) {
                return redirect()->route('owner.appointments.index')
                    ->with('error', 'Appointment not found.');
            }

            $appointment->status = 'cancelled';
            $appointment->cancelled_at = now();
            $appointment->save();

            return redirect()->route('owner.appointments.show', ['appointment' => $id])
                ->with('success', 'Appointment cancelled successfully.');

        } catch (\Exception $e) {
            \Log::error('Appointment Cancel Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to cancel appointment.');
        }
    }

    /**
     * Verify/Approve Payment
     */
    public function verifyPayment(Request $request, $id)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Salon not found.');
            }

            $appointment = Appointment::where('salon_id', $salon->id)->find($id);

            if (!$appointment) {
                return redirect()->route('owner.appointments.index')
                    ->with('error', 'Appointment not found.');
            }

            $payment = Payment::where('appointment_id', $appointment->id)->first();

            if (!$payment) {
                return redirect()->route('owner.appointments.show', ['appointment' => $id])
                    ->with('error', 'No payment found for this appointment.');
            }

            $payment->status = 'approved';
            $payment->save();

            $appointment->status = 'confirmed';
            $appointment->save();

            return redirect()->route('owner.appointments.show', ['appointment' => $id])
                ->with('success', 'Payment verified and appointment confirmed!');

        } catch (\Exception $e) {
            \Log::error('Payment Verify Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to verify payment.');
        }
    }

    /**
     * Reject Payment
     */
    public function rejectPayment(Request $request, $id)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Salon not found.');
            }

            $appointment = Appointment::where('salon_id', $salon->id)->find($id);

            if (!$appointment) {
                return redirect()->route('owner.appointments.index')
                    ->with('error', 'Appointment not found.');
            }

            $payment = Payment::where('appointment_id', $appointment->id)->first();

            if (!$payment) {
                return redirect()->route('owner.appointments.show', ['appointment' => $id])
                    ->with('error', 'No payment found for this appointment.');
            }

            $payment->status = 'rejected';
            $payment->save();

            $appointment->status = 'pending_payment';
            $appointment->save();

            return redirect()->route('owner.appointments.show', ['appointment' => $id])
                ->with('success', 'Payment rejected.');

        } catch (\Exception $e) {
            \Log::error('Payment Reject Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to reject payment.');
        }
    }

    /**
     * View Invoice
     */
    public function invoice($id)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Salon not found.');
            }

            $appointment = Appointment::where('salon_id', $salon->id)
                ->with(['client', 'service', 'stylist', 'payment'])
                ->find($id);

            if (!$appointment) {
                return redirect()->route('owner.appointments.index')
                    ->with('error', 'Appointment not found.');
            }

            $appointmentData = [
                'id' => $appointment->id,
                'client_name' => $appointment->client->name ?? 'N/A',
                'client_email' => $appointment->client->email ?? 'N/A',
                'client_phone' => $appointment->client->phone ?? 'N/A',
                'service' => $appointment->service->name ?? 'N/A',
                'stylist' => $appointment->stylist->name ?? 'N/A',
                'date' => $appointment->appointment_date->format('M d, Y'),
                'time_range' => Carbon::parse($appointment->start_time)->format('g:i A') . ' - ' . Carbon::parse($appointment->end_time)->format('g:i A'),
                'price' => $appointment->total_amount,
                'advance_amount' => $appointment->advance_amount,
                'booking_ref' => $appointment->booking_ref,
                'payment_status' => $appointment->payment->status ?? 'pending',
                'payment_method' => $appointment->payment->method ?? 'N/A',
            ];

            return view('owner.appointments.invoice', [
                'appointment' => $appointmentData,
                'salon' => $salon,
            ]);

        } catch (\Exception $e) {
            \Log::error('Invoice Error: ' . $e->getMessage());
            return redirect()->route('owner.appointments.index')
                ->with('error', 'Unable to generate invoice.');
        }
    }

    /**
     * Export CSV
     */
    public function export(Request $request)
    {
        try {
            $user = auth()->user();

            if ($user->role !== 'owner') {
                abort(403, 'Unauthorized access.');
            }

            $salon = Salon::where('owner_id', $user->id)->first();

            if (!$salon) {
                return redirect()->route('owner.salons.create')
                    ->with('error', 'Salon not found.');
            }

            $appointments = Appointment::where('salon_id', $salon->id)
                ->with(['client', 'service', 'stylist'])
                ->get();

            $csv = "Booking Ref,Client,Service,Date,Time,Stylist,Amount,Status\n";
            foreach ($appointments as $appt) {
                $csv .= $appt->booking_ref . ",";
                $csv .= ($appt->client->name ?? 'N/A') . ",";
                $csv .= ($appt->service->name ?? 'N/A') . ",";
                $csv .= $appt->appointment_date->format('M d, Y') . ",";
                $csv .= Carbon::parse($appt->start_time)->format('g:i A') . " - " . Carbon::parse($appt->end_time)->format('g:i A') . ",";
                $csv .= ($appt->stylist->name ?? 'N/A') . ",";
                $csv .= $appt->total_amount . ",";
                $csv .= $appt->status . "\n";
            }

            return response($csv)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="appointments-' . now()->format('Y-m-d') . '.csv"');

        } catch (\Exception $e) {
            \Log::error('Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to export appointments.');
        }
    }
}