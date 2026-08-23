<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Waitlist;
use App\Models\Appointment;
use App\Models\Salon;
use App\Notifications\WaitlistSlotAvailable;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OwnerNotificationEmail;

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
            'salon_id'       => 'required|exists:salons,id',
            'service_id'     => 'required|exists:services,id',
            'stylist_id'     => 'required|exists:stylists,id',
            'preferred_date' => 'required|date',
        ]);

      
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

        $waitlistEntry = Waitlist::create([
            'client_id'      => Auth::id(),
            'salon_id'       => $request->salon_id,
            'stylist_id'     => $request->stylist_id,
            'service_id'     => $request->service_id,
            'preferred_date' => $request->preferred_date,
            'position'       => $position,
            'status'         => 'waiting',
        ]);

       
        try {
            $client  = Auth::user();
            $service = $waitlistEntry->service;
            $formattedDate = \Carbon\Carbon::parse($request->preferred_date)->format('M d, Y');

            
            NotificationHelper::send(
                $request->salon_id,
                'waitlist',
                [
                    'title'   => '⏳ New Waitlist Join',
                    'message' => "{$client->name} joined the waitlist for " . ($service->name ?? 'a service') . " on {$formattedDate} (position #{$position})",
                    'link'    => route('owner.waitlist.show', $waitlistEntry->id),
                ]
            );

            $salon = Salon::find($request->salon_id);
            $ownerEmail = $salon->owner->email ?? config('mail.from.address');

            if ($ownerEmail) {
                $emailSubject = "⏳ New Client Joined Waitlist";
                $emailBody = "Client <strong>{$client->name}</strong> ne waitlist join ki hai.<br><br>" .
                             "<strong>Service:</strong> " . ($service->name ?? 'N/A') . "<br>" .
                             "<strong>Date:</strong> {$formattedDate}<br>" .
                             "<strong>Position:</strong> #{$position}";

                Mail::to($ownerEmail)->send(new OwnerNotificationEmail($emailSubject, $emailBody));
            }

        } catch (\Exception $e) {
            \Log::error('Waitlist join notification/email error: ' . $e->getMessage());
        }

        return back()->with('success', 'You joined the waitlist at position #' . $position . '!');
    }

    
    public function accept(Waitlist $waitlist)
    {
        if ($waitlist->client_id !== Auth::id()) abort(403);

      
        if ($waitlist->expires_at && now()->greaterThan($waitlist->expires_at)) {
            $waitlist->update(['status' => 'expired']);

          
            static::offerToNext(
                $waitlist->salon_id,
                $waitlist->stylist_id,
                $waitlist->preferred_date
            );

            return back()->with('error', 'Sorry, your 20-minute window to accept this slot has expired.');
        }

       
        $waitlist->update([
            'status'       => 'accepted',
            'responded_at' => now(),
        ]);

      
        try {
            $servicePrice = $waitlist->service ? $waitlist->service->price : 0;

            Appointment::create([
                'client_id'      => Auth::id(),
                'salon_id'       => $waitlist->salon_id,
                'stylist_id'     => $waitlist->stylist_id,
                'service_id'     => $waitlist->service_id,
                'booking_date'   => $waitlist->preferred_date,
                'price'          => $servicePrice,
                'status'         => 'confirmed',
                'payment_status' => 'pending',
            ]);
        } catch (\Exception $e) {
            \Log::error('Appointment creation error on waitlist accept: ' . $e->getMessage());
        }

        return redirect()
            ->route('client.appointments.index')
            ->with('success', '🎉 Slot accepted! Your appointment has been successfully booked for ' . $waitlist->preferred_date . '.');
    }

    
    public function reject(Waitlist $waitlist)
    {
        if ($waitlist->client_id !== Auth::id()) abort(403);

        $waitlist->update([
            'status'       => 'rejected',
            'responded_at' => now(),
        ]);

       
        static::offerToNext(
            $waitlist->salon_id,
            $waitlist->stylist_id,
            $waitlist->preferred_date
        );

        return back()->with('info', 'You declined the slot.');
    }

   
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
                'expires_at' => now()->addMinutes(20),
            ]);

            try {
                if ($next->client) {
                    $next->client->notify(new WaitlistSlotAvailable($next));
                }
            } catch (\Exception $e) {
                \Log::warning('Waitlist notification failed: ' . $e->getMessage());
            }
        }
    }
}