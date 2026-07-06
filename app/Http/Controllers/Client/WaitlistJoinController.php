<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Waitlist;
use App\Models\Appointment;
use App\Notifications\WaitlistSlotAvailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaitlistJoinController extends Controller
{
    public function index(Request $request)
    {
        $query = Waitlist::with(['salon', 'stylist', 'service'])
            ->where('client_id', Auth::id())
            ->latest();

        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $waitlists = $query->paginate(15);

        return view('client.waitlist.index', compact('waitlists'));
    }

    public function join(Request $request)
    {
        $request->validate([
            'salon_id'    => 'required|exists:salons,id',
            'service_id'  => 'required|exists:services,id',
            'stylist_id'  => 'required|exists:stylists,id',
            'preferred_date' => 'required|date',
        ]);

        // Already on waitlist check
        $exists = Waitlist::where('client_id', Auth::id())
            ->where('salon_id', $request->salon_id)
            ->where('stylist_id', $request->stylist_id)
            ->where('preferred_date', $request->preferred_date)
            ->whereIn('status', ['waiting', 'notified'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'You are already on the waitlist for this date.');
        }

        $position = Waitlist::where('salon_id', $request->salon_id)
            ->where('stylist_id', $request->stylist_id)
            ->where('preferred_date', $request->preferred_date)
            ->where('status', 'waiting')
            ->count() + 1;

        Waitlist::create([
            'client_id'      => Auth::id(),
            'salon_id'       => $request->salon_id,
            'stylist_id'     => $request->stylist_id,
            'service_id'     => $request->service_id,
            'preferred_date' => $request->preferred_date,
            'position'       => $position,
            'status'         => 'waiting',
        ]);

        return back()->with('success', 'You joined the waitlist at position #' . $position . '!');
    }

    // Client accepts the offered slot
    public function accept(Waitlist $waitlist)
    {
        if ($waitlist->client_id !== Auth::id()) abort(403);

        $waitlist->update([
            'status'       => 'accepted',
            'responded_at' => now(),
        ]);

        // Redirect to booking so client can complete payment
        return redirect()
            ->route('booking.step3', $waitlist->salon_id)
            ->with('success', 'Great! Please complete your booking for ' .
                $waitlist->preferred_date . '.');
    }

    // Client rejects the offered slot
    public function reject(Waitlist $waitlist)
    {
        if ($waitlist->client_id !== Auth::id()) abort(403);

        $waitlist->update([
            'status'       => 'rejected',
            'responded_at' => now(),
        ]);

        // Offer to next person in queue
        static::offerToNext(
            $waitlist->salon_id,
            $waitlist->stylist_id,
            $waitlist->preferred_date
        );

        return back()->with('info', 'You declined the slot.');
    }

    // When an appointment is cancelled, notify next waiting client
    // Call this from AppointmentManageController::cancel()
    public static function offerToNext(
        int    $salonId,
        int    $stylistId,
        string $preferredDate
    ): void {
        $next = Waitlist::where('salon_id', $salonId)
            ->where('stylist_id', $stylistId)
            ->where('preferred_date', $preferredDate)
            ->where('status', 'waiting')
            ->orderBy('position')
            ->first();

        if ($next) {
            $next->update([
                'status'     => 'notified',
                'expires_at' => now()->addMinutes(10),
            ]);

            try {
                $next->client->notify(new WaitlistSlotAvailable($next));
            } catch (\Exception $e) {
                \Log::warning('Waitlist notification failed: ' . $e->getMessage());
            }
        }
    }
}