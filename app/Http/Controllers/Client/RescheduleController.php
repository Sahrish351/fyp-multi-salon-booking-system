<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Notifications\AppointmentUpdateNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RescheduleController extends Controller
{
    public function create(Appointment $appointment)
    {
        if ($appointment->client_id !== Auth::id()) {
            abort(403);
        }

        if (in_array($appointment->status, ['cancelled', 'completed'])) {
            return redirect()->route('client.appointments.show', $appointment->id)
                ->with('error', 'This appointment can no longer be rescheduled.');
        }

        return view('client.reschedule.create', compact('appointment'));
    }

    public function store(Request $request, Appointment $appointment)
    {
        if ($appointment->client_id !== Auth::id()) {
            abort(403);
        }

        if (in_array($appointment->status, ['cancelled', 'completed'])) {
            return back()->with('error', 'This appointment can no longer be rescheduled.');
        }

        $request->validate([
            'new_date'          => 'required|date|after_or_equal:' . now()->format('Y-m-d'),
            'new_time'          => 'required|date_format:H:i',
            'reschedule_reason' => 'nullable|string|max:500',
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