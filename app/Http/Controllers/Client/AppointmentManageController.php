<?php
 
namespace App\Http\Controllers\Client;
 
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\TimeSlot;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
 
class AppointmentManageController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with([
            'salon',
            'service' => fn ($q) => $q->withTrashed(),
            'stylist' => fn ($q) => $q->withTrashed(),
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
        $this->authorizeAppointmentOwner($appointment);
 
        $appointment->load([
            'salon',
            'service' => fn ($q) => $q->withTrashed(),
            'stylist' => fn ($q) => $q->withTrashed(),
            'payment',
            'review'
        ]);
 
        return view('client.appointments.show', compact('appointment'));
    }
 
    public function cancel(Request $request, Appointment $appointment)
    {
        $this->authorizeAppointmentOwner($appointment);
 
        if (in_array($appointment->status, ['cancelled', 'completed'])) {
            return back()->with('error', 'This appointment can no longer be cancelled.');
        }
 
        $request->validate(['cancellation_reason' => 'required|string|max:500']);
 
        $appointment->update([
            'status'              => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
            'cancelled_at'        => now(),
        ]);
 
        if (class_exists('App\Http\Controllers\Client\WaitlistJoinController')) {
            try {
                \App\Http\Controllers\Client\WaitlistJoinController::offerToNext(
                    $appointment->salon_id,
                    $appointment->stylist_id,
                    Carbon::parse($appointment->appointment_date)->format('Y-m-d')
                );
            } catch (\Exception $e) {
                \Log::warning('Waitlist offer error: ' . $e->getMessage());
            }
        }
 
        try {
            $client = Auth::user();
            
            NotificationHelper::send(
                $appointment->salon_id,
                'appointment',
                [
                    'title'   => '❌ Appointment Cancelled',
                    'message' => "{$client->name} cancelled their appointment for " . Carbon::parse($appointment->appointment_date)->format('M d, Y'),
                    'link'    => route('owner.appointments.show', $appointment->id),
                ]
            );
 
            $appointment->load('salon.owner');
            $ownerEmail = $appointment->salon->owner->email ?? $appointment->salon->email ?? config('mail.from.address');
 
            if ($ownerEmail && class_exists('\App\Mail\OwnerNotificationEmail')) {
                $emailSubject = "❌ Appointment Cancelled — " . $appointment->booking_ref;
                $emailBody = "Client <strong>{$client->name}</strong> has cancelled their appointment.<br><br>" .
                             "<strong>Original Date:</strong> " . Carbon::parse($appointment->appointment_date)->format('M d, Y') . "<br>" .
                             "<strong>Reason Given:</strong> {$request->cancellation_reason}<br>" .
                             "<strong>Booking Reference:</strong> {$appointment->booking_ref}";
 
                Mail::to($ownerEmail)->send(new \App\Mail\OwnerNotificationEmail($emailSubject, $emailBody));
            }
 
        } catch (\Exception $e) {
            \Log::error('Cancel notification/email error: ' . $e->getMessage());
        }
 
        return redirect()->route('client.appointments.show', $appointment->id)
            ->with('success', 'Your appointment has been cancelled.');
    }
 
    public function rescheduleForm(Appointment $appointment)
    {
        $this->authorizeAppointmentOwner($appointment);
 
        if (in_array($appointment->status, ['cancelled', 'completed'])) {
            return redirect()->route('client.appointments.index')
                ->with('error', 'This appointment can no longer be rescheduled.');
        }
 
        $appointment->load(['salon', 'service', 'stylist']);
 
        return view('client.appointments.reschedule', compact('appointment'));
    }
 
    public function reschedule(Request $request, Appointment $appointment)
    {
        $this->authorizeAppointmentOwner($appointment);
 
        if (in_array($appointment->status, ['cancelled', 'completed'])) {
            return back()->with('error', 'This appointment can no longer be rescheduled.');
        }
 
        $date = $request->input('new_date') ?? $request->input('date');
        $time = $request->input('new_time') ?? $request->input('start_time');
 
        $request->merge([
            'new_date' => $date,
            'new_time' => $time,
        ]);
 
        $request->validate([
            'new_date' => 'required|date|after_or_equal:' . now()->format('Y-m-d'),
            'new_time' => 'required',
        ], [
            'new_date.after_or_equal' => 'Please select today or a future date.',
        ]);
 
        $service  = $appointment->service;
        $newStart = Carbon::parse($time);
        $newEnd   = $newStart->copy()->addMinutes($service->duration ?? 60);
 
        $slotConflict = Appointment::where('stylist_id', $appointment->stylist_id)
            ->where('id', '!=', $appointment->id)
            ->where('appointment_date', $date)
            ->whereNotIn('status', ['cancelled'])
            ->where(function ($q) use ($newStart, $newEnd) {
                $q->whereBetween('start_time', [$newStart->format('H:i:s'), $newEnd->format('H:i:s')])
                  ->orWhereBetween('end_time', [$newStart->format('H:i:s'), $newEnd->format('H:i:s')]);
            })->exists();
 
        if ($slotConflict) {
            return back()->with('error', 'The selected time slot is already booked. Please choose another time.')->withInput();
        }
 
        $oldDate = Carbon::parse($appointment->appointment_date)->format('d M Y');
        $oldTime = Carbon::parse($appointment->start_time)->format('h:i A');
 
        $appointment->update([
            'appointment_date' => $date,
            'start_time'       => $newStart->format('H:i:s'),
            'end_time'         => $newEnd->format('H:i:s'),
            'notes'            => trim(
                ($appointment->notes ? $appointment->notes . ' | ' : '') .
                "Rescheduled from {$oldDate} {$oldTime}" .
                ($request->filled('reschedule_reason') ? " — Reason: {$request->reschedule_reason}" : '')
            ),
        ]);
 
        try {
            $client = Auth::user();
 
            NotificationHelper::send(
                $appointment->salon_id,
                'appointment',
                [
                    'title'   => '🔄 Appointment Rescheduled',
                    'message' => "{$client->name} rescheduled appointment from {$oldDate} {$oldTime} to " . Carbon::parse($date)->format('M d, Y') . ' at ' . $newStart->format('h:i A'),
                    'link'    => route('owner.appointments.show', $appointment->id),
                ]
            );
 
            $appointment->load('salon.owner');
            $ownerEmail = $appointment->salon->owner->email ?? $appointment->salon->email ?? config('mail.from.address');
 
            if ($ownerEmail && class_exists('\App\Mail\OwnerNotificationEmail')) {
                $emailSubject = "🔄 Appointment Rescheduled — " . $appointment->booking_ref;
                $emailBody = "Client <strong>{$client->name}</strong> has rescheduled their appointment.<br><br>" .
                             "<strong>Previous Time:</strong> {$oldDate} at {$oldTime}<br>" .
                             "<strong>New Time:</strong> " . Carbon::parse($date)->format('M d, Y') . " at " . $newStart->format('h:i A') . "<br>" .
                             "<strong>Booking Reference:</strong> {$appointment->booking_ref}";
 
                Mail::to($ownerEmail)->send(new \App\Mail\OwnerNotificationEmail($emailSubject, $emailBody));
            }
 
        } catch (\Exception $e) {
            \Log::error('Reschedule notification/email error: ' . $e->getMessage());
        }
 
        return redirect()->route('client.appointments.show', $appointment->id)
            ->with('success', 'Your appointment has been rescheduled successfully!');
    }
 
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'stylist_id'     => 'required|exists:stylists,id',
            'date'           => 'required|date|after_or_equal:today',
            'appointment_id' => 'required|exists:appointments,id',
        ]);
 
        $appointment = Appointment::findOrFail($request->appointment_id);
        $this->authorizeAppointmentOwner($appointment);
 
        $slots = TimeSlot::where('stylist_id', $request->stylist_id)
            ->where('slot_date', $request->date)
            ->where('status', 'available')
            ->whereDoesntHave('appointment', function ($q) use ($request) {
                $q->where('id', '!=', $request->appointment_id)
                  ->where('status', '!=', 'cancelled');
            })
            ->get()
            ->map(fn ($slot) => [
                'id'         => $slot->id,
                'start_time' => $slot->start_time,
                'end_time'   => $slot->end_time,
                'display'    => Carbon::parse($slot->start_time)->format('h:i A') . ' - ' . Carbon::parse($slot->end_time)->format('h:i A'),
            ]);
 
        return response()->json(['slots' => $slots]);
    }
 
    private function authorizeAppointmentOwner(Appointment $appointment): void
    {
        if ($appointment->client_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }
    }
}