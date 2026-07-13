<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentManageController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with([
            'salon',
            'service' => function ($q) {
                $q->withTrashed();
            },
            'stylist' => function ($q) {
                $q->withTrashed();
            },
            'payment'
        ])
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

        $appointment->load([
            'salon',
            'service' => function ($q) {
                $q->withTrashed();
            },
            'stylist' => function ($q) {
                $q->withTrashed();
            },
            'payment',
            'review'
        ]);

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

        // ── Waitlist: next waiting client ko notify karo ──
        if (class_exists('App\Http\Controllers\Client\WaitlistJoinController')) {
            try {
                WaitlistJoinController::offerToNext(
                    $appointment->salon_id,
                    $appointment->stylist_id,
                    $appointment->appointment_date->format('Y-m-d')
                );
            } catch (\Exception $e) {
                // Silently ignore
            }
        }

        // ✅ NOTIFICATION: Client ne appointment cancel ki
        try {
            $client = Auth::user();
            NotificationHelper::send(
                $appointment->salon_id,
                'appointment',
                [
                    'title' => '❌ Appointment Cancelled',
                    'message' => "{$client->name} cancelled their appointment for " . Carbon::parse($appointment->appointment_date)->format('M d, Y'),
                    'link' => route('owner.appointments.show', $appointment->id),
                ]
            );
        } catch (\Exception $e) {
            \Log::error('Cancel notification error: ' . $e->getMessage());
        }

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

        $newStart = Carbon::parse($request->new_time)->format('H:i:s');
        $newEnd   = Carbon::parse($request->new_time)
                        ->addMinutes($service->duration ?? 60)
                        ->format('H:i:s');

        $oldDate = Carbon::parse($appointment->appointment_date)->format('d M Y');
        $oldTime = Carbon::parse($appointment->start_time)->format('h:i A');

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

        // ✅ NOTIFICATION: Client ne appointment reschedule ki
        try {
            $client = Auth::user();
            NotificationHelper::send(
                $appointment->salon_id,
                'appointment',
                [
                    'title' => '🔄 Appointment Rescheduled',
                    'message' => "{$client->name} rescheduled appointment from {$oldDate} {$oldTime} to " . Carbon::parse($request->new_date)->format('M d, Y') . ' at ' . Carbon::parse($request->new_time)->format('h:i A'),
                    'link' => route('owner.appointments.show', $appointment->id),
                ]
            );
        } catch (\Exception $e) {
            \Log::error('Reschedule notification error: ' . $e->getMessage());
        }

        return redirect()->route('client.appointments.show', $appointment->id)
            ->with('success', 'Your appointment has been rescheduled successfully!');
    }

    public function rescheduleForm(Appointment $appointment)
    {
        if ($appointment->client_id !== Auth::id()) {
            abort(403);
        }

        if (in_array($appointment->status, ['cancelled', 'completed'])) {
            return redirect()->route('client.appointments.index')
                ->with('error', 'This appointment can no longer be rescheduled.');
        }

        $timeSlots = \App\Models\TimeSlot::where('stylist_id', $appointment->stylist_id)
            ->where('slot_date', $appointment->appointment_date)
            ->where('status', 'available')
            ->get();

        return view('client.appointments.reschedule', compact('appointment', 'timeSlots'));
    }

    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'stylist_id' => 'required|exists:stylists,id',
            'date' => 'required|date',
            'appointment_id' => 'required|exists:appointments,id',
        ]);

        $appointment = Appointment::find($request->appointment_id);
        
        if ($appointment->client_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $slots = \App\Models\TimeSlot::where('stylist_id', $request->stylist_id)
            ->where('slot_date', $request->date)
            ->where('status', 'available')
            ->whereDoesntHave('appointment', function ($q) use ($request) {
                $q->where('id', '!=', $request->appointment_id)
                  ->where('status', '!=', 'cancelled');
            })
            ->get()
            ->map(function ($slot) {
                return [
                    'id' => $slot->id,
                    'start_time' => $slot->start_time,
                    'end_time' => $slot->end_time,
                    'display' => Carbon::parse($slot->start_time)->format('h:i A') . ' - ' . Carbon::parse($slot->end_time)->format('h:i A'),
                ];
            });

        return response()->json(['slots' => $slots]);
    }
}