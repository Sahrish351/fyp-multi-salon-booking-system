<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Waitlist;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaitlistJoinController extends Controller
{
    public function index()
    {
        $waitlists = Waitlist::with(['salon', 'stylist', 'service', 'timeSlot'])
            ->where('client_id', Auth::id())
            ->latest()
            ->paginate(15);
        return view('client.waitlist.index', compact('waitlists'));
    }

    public function join(Request $request)
    {
        $request->validate([
            'time_slot_id' => 'required|exists:time_slots,id',
            'service_id'   => 'required|exists:services,id',
        ]);

        $slot = TimeSlot::findOrFail($request->time_slot_id);

        $position = Waitlist::where('time_slot_id', $slot->id)->where('status', 'waiting')->count() + 1;

        Waitlist::create([
            'client_id'      => Auth::id(),
            'salon_id'       => $slot->salon_id,
            'stylist_id'     => $slot->stylist_id,
            'service_id'     => $request->service_id,
            'time_slot_id'   => $slot->id,
            'preferred_date' => $slot->slot_date,
            'position'       => $position,
            'status'         => 'waiting',
        ]);

        return back()->with('success', 'Added to waitlist at position #' . $position);
    }

    public function accept(Waitlist $waitlist)
    {
        $waitlist->update(['status' => 'accepted', 'responded_at' => now()]);
        return redirect()->route('client.booking.step4', $waitlist->salon_id)->with('success', 'Slot accepted! Complete your booking.');
    }

    public function reject(Waitlist $waitlist)
    {
        $waitlist->update(['status' => 'rejected', 'responded_at' => now()]);
        return back()->with('success', 'Slot declined.');
    }
}