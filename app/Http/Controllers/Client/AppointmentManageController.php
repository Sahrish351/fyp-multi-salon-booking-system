<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Notifications\AppointmentUpdateNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentManageController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['salon', 'stylist', 'service', 'payment'])
            ->where('client_id', Auth::id())
            ->latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $timeframe = $request->get('timeframe', 'all');
        $today = now()->format('Y-m-d');

        if ($timeframe === 'upcoming') {
            $query->whereDate('appointment_date', '>=', $today)
                  ->whereNotIn('status', ['cancelled', 'completed']);
        } elseif ($timeframe === 'past') {
            $query->where(function ($q) use ($today) {
                $q->whereDate('appointment_date', '<', $today)
                  ->orWhereIn('status', ['completed', 'cancelled']);
            });
        }

        $appointments = $query->paginate(15)->withQueryString();

        return view('client.appointments.index', compact('appointments', 'timeframe'));
    }

    public function show(Appointment $appointment)
    {
        if ($appointment->client_id !== Auth::id()) {
            abort(403);
        }

        $appointment->load('salon', 'stylist', 'service', 'payment', 'review');

        return view('client.appointments.show', compact('appointment'));
    }

    public function cancel(Request $request, Appointment $appointment)
    {
        if ($appointment->client_id !== Auth::id()) {
            abort(403);
        }

        if (in_array($appointment->status, ['cancelled', 'completed'])) {
            return back()->with('error', 'This appointment can no longer be cancelled.');
        }

        $request->validate(['cancellation_reason' => 'required|string|max:500']);

        $appointment->update([
            'status'              => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
            'cancelled_at'        => now(),
        ]);

        Auth::user()->notify(new AppointmentUpdateNotification($appointment, 'cancelled'));

        return redirect()->route('client.appointments.show', $appointment->id)
            ->with('success', 'Your appointment has been cancelled.');
    }

    public function reschedule(Request $request, Appointment $appointment)
    {
        if ($appointment->client_id !== Auth::id()) {
            abort(403);
        }

        if (in_array($appointment->status, ['cancelled', 'completed'])) {
            return back()->with('error', 'This appointment can no longer be rescheduled.');
        }

        $request->validate([
            'new_date' => 'required|date|after_or_equal:' . now()->format('Y-m-d'),
            'new_time' => 'required|date_format:H:i',
        ], [
            'new_date.after_or_equal' => 'Please select today or a future date.',
        ]);

        $service = $appointment->service;

        $newStart = \Carbon\Carbon::parse($request->new_time)->format('H:i:s');
        $newEnd   = \Carbon\Carbon::parse($request->new_time)
                        ->addMinutes($service->duration ?? 60)
                        ->format('H:i:s');

        $oldDate = \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y');
        $oldTime = \Carbon\Carbon::parse($appointment->start_time)->format('h:i A');

        $appointment->update([
            'appointment_date' => $request->new_date,
            'start_time'       => $newStart,
            'end_time'         => $newEnd,
            'notes'            => trim(
                ($appointment->notes ? $appointment->notes . ' | ' : '') .
                "Rescheduled from {$oldDate} {$oldTime}" .
                ($request->filled('reschedule_reason') ? " — Reason: {$request->reschedule_reason}" : '')
            ),
        ]);

        Auth::user()->notify(new AppointmentUpdateNotification($appointment, 'rescheduled', [
            'old_date' => $oldDate,
            'old_time' => $oldTime,
        ]));

        return redirect()->route('client.appointments.show', $appointment->id)
            ->with('success', 'Your appointment has been rescheduled successfully!');
    }
}