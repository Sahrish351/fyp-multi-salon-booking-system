<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentReschedule;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RescheduleController extends Controller
{
    public function create(Appointment $appointment)
    {
        if ($appointment->client_id !== Auth::id()) abort(403);
        
        if (!in_array($appointment->status, ['confirmed', 'payment_approved'])) {
            return back()->with('error', 'Only confirmed appointments can be rescheduled.');
        }

        $salon = $appointment->salon;
        $stylists = $salon->stylists()->where('is_active', true)->get();
        
        return view('client.reschedule.create', compact('appointment', 'salon', 'stylists'));
    }

    public function store(Request $request, Appointment $appointment)
    {
        if ($appointment->client_id !== Auth::id()) abort(403);

        $request->validate([
            'new_date'       => 'required|date|after:today',
            'new_time_slot_id' => 'required|exists:time_slots,id',
            'new_stylist_id' => 'nullable|exists:stylists,id',
            'reason'         => 'nullable|string',
        ]);

        $newSlot = TimeSlot::findOrFail($request->new_time_slot_id);
        
        if (!$newSlot->isAvailable()) {
            return back()->with('error', 'Selected slot is no longer available.');
        }

        DB::transaction(function () use ($request, $appointment, $newSlot) {
            // Lock the new slot
            $newSlot->update([
                'status'          => 'locked',
                'locked_by'       => Auth::id(),
                'locked_at'       => now(),
                'lock_expires_at' => now()->addMinutes(10),
            ]);

            // Create reschedule request
            AppointmentReschedule::create([
                'appointment_id'   => $appointment->id,
                'old_date'         => $appointment->appointment_date,
                'old_time'         => $appointment->start_time,
                'new_date'         => $request->new_date,
                'new_time'         => $newSlot->start_time,
                'old_stylist_id'   => $appointment->stylist_id,
                'new_stylist_id'   => $request->new_stylist_id ?? $appointment->stylist_id,
                'reason'           => $request->reason,
                'requested_by'     => Auth::id(),
                'status'           => 'pending',
            ]);

            $appointment->update(['status' => 'pending_reschedule']);
        });

        return redirect()->route('client.appointments.show', $appointment)->with('success', 'Reschedule request submitted! Awaiting owner approval.');
    }
}