<?php
 
namespace App\Http\Controllers\Frontend;
 
use App\Http\Controllers\Controller;
use App\Models\Salon;
use App\Models\Service;
use App\Models\Stylist;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\StylistHoliday;
use App\Models\Waitlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
 
class BookingController extends Controller
{
    public function step1Services($salon_id)
    {
        $salon    = Salon::with('services.category')->findOrFail($salon_id);
        $services = $salon->services()->where('is_active', true)->with('category')->get();
 
        return view('frontend.booking.step-1-services', compact('salon', 'services'));
    }
 
 
    public function postStep1Services(Request $request, $salon_id)
    {
        $salon = Salon::findOrFail($salon_id);
 
        if ($request->has('service_ids') && count(array_filter($request->service_ids)) > 0) {
            $ids = array_filter($request->service_ids);
            Session::put('booking_service_id',  $ids[0]);
            Session::put('booking_service_ids', $ids);
        } elseif ($request->filled('service_id')) {
            Session::put('booking_service_id',  $request->service_id);
            Session::put('booking_service_ids', [$request->service_id]);
        } else {
            return back()->with('error', 'Please select at least one service.');
        }
 
        Session::put('booking_salon_id', $salon->id);
 
        return redirect()->route('booking.step2', $salon->id);
    }
 
 
    public function step2Stylist($salon_id)
    {
        $salon     = Salon::findOrFail($salon_id);
        $serviceId = Session::get('booking_service_id');
 
        if (!$serviceId || Session::get('booking_salon_id') != $salon_id) {
            return redirect()->route('booking.step1', $salon->id)
                ->with('error', 'Please select a service first.');
        }
 
        $service  = Service::findOrFail($serviceId);
        $stylists = $salon->stylists()->where('is_active', true)->get();
 
        return view('frontend.booking.step-2-stylist', compact('salon', 'service', 'stylists'));
    }
 
 
    public function postStep2Stylist(Request $request, $salon_id)
    {
        $salon = Salon::findOrFail($salon_id);
 
        if (Session::get('booking_salon_id') != $salon_id) {
            return redirect()->route('booking.step1', $salon->id)
                ->with('error', 'Your booking session doesn\'t match this salon. Please start again.');
        }
 
        if ($request->stylist_id === 'any') {
            $stylist = $salon->stylists()->where('is_active', true)->inRandomOrder()->first();
            if (!$stylist) {
                return back()->with('error', 'No stylists available right now.');
            }
            Session::put('booking_stylist_id', $stylist->id);
        } else {
            $request->validate(['stylist_id' => 'required|exists:stylists,id']);
            Session::put('booking_stylist_id', $request->stylist_id);
        }
 
        return redirect()->route('booking.step3', $salon->id);
    }
 
 
    public function step3DateTime($salon_id)
    {
        $salon     = Salon::findOrFail($salon_id);
        $serviceId = Session::get('booking_service_id');
        $stylistId = Session::get('booking_stylist_id');
 
        if (!$serviceId || !$stylistId || Session::get('booking_salon_id') != $salon_id) {
            return redirect()->route('booking.step1', $salon->id)
                ->with('error', 'Please complete previous steps first.');
        }
 
        $service = Service::findOrFail($serviceId);
        $stylist = Stylist::findOrFail($stylistId);
 
        return view('frontend.booking.step-3-datetime', compact('salon', 'service', 'stylist'));
    }
 
 
    /**
     * UPDATED: waitlist branch no longer creates the Waitlist record here and
     * no longer redirects to client.waitlist.index. It now only stores the
     * chosen date + a `booking_is_waitlist` flag in session and sends the
     * client to Step 4 (Payment) — exactly like the regular flow — because
     * waitlist bookings also require the Rs.100 advance and must show up as
     * a pending_payment appointment for the salon owner to approve.
     */
    public function postStep3DateTime(Request $request, $salon_id)
    {
        $salon = Salon::findOrFail($salon_id);
 
        if (Session::get('booking_salon_id') != $salon_id) {
            return redirect()->route('booking.step1', $salon->id)
                ->with('error', 'Your booking session doesn\'t match this salon. Please start again.');
        }
 
        // Waitlist branch: only the date matters, no time slot yet.
        if ($request->has('join_waitlist') && $request->join_waitlist == '1') {
            $request->validate([
                'appointment_date' => 'required|date',
            ]);
 
            Session::put('booking_date', $request->appointment_date);
            Session::put('booking_time', null);
            Session::put('booking_is_waitlist', true);
 
            return redirect()->route('booking.step4', $salon->id);
        }
 
        // Regular booking: date + time slot required.
        $request->validate([
            'time_slot_id'     => 'required|string',
            'appointment_date' => 'required|date_format:Y-m-d|after_or_equal:' . now()->format('Y-m-d'),
        ]);
 
        Session::put('booking_time', $request->time_slot_id);
        Session::put('booking_date', $request->appointment_date);
        Session::put('booking_is_waitlist', false);
 
        return redirect()->route('booking.step4', $salon->id);
    }
 
 
    /**
     * UPDATED: reads the `booking_is_waitlist` flag. When true, no time slot
     * exists yet, so validation and the $slot object skip start/end time.
     */
    public function step4Payment($salon_id)
    {
        $salon       = Salon::findOrFail($salon_id);
        $serviceId   = Session::get('booking_service_id');
        $stylistId   = Session::get('booking_stylist_id');
        $bookingTime = Session::get('booking_time');
        $bookingDate = Session::get('booking_date');
        $isWaitlist  = Session::get('booking_is_waitlist', false);
 
        if (Session::get('booking_salon_id') != $salon_id) {
            return redirect()->route('booking.step1', $salon->id)
                ->with('error', 'Your booking session doesn\'t match this salon. Please start again.');
        }
 
        if (!$serviceId || !$stylistId) {
            return redirect()->route('booking.step1', $salon->id)
                ->with('error', 'Please complete all booking steps.');
        }
 
        if (!$bookingDate || (!$bookingTime && !$isWaitlist)) {
            return redirect()->route('booking.step3', $salon->id)
                ->with('error', 'Please select a date and time first.');
        }
 
        $service = Service::findOrFail($serviceId);
        $stylist = Stylist::findOrFail($stylistId);
 
        $slot = $isWaitlist
            ? (object) [
                'slot_date'   => $bookingDate,
                'start_time'  => null,
                'end_time'    => null,
                'is_waitlist' => true,
            ]
            : (object) [
                'slot_date'   => $bookingDate,
                'start_time'  => \Carbon\Carbon::parse($bookingTime)->format('H:i:s'),
                'end_time'    => \Carbon\Carbon::parse($bookingTime)
                                    ->addMinutes($service->duration ?? 60)
                                    ->format('H:i:s'),
                'is_waitlist' => false,
            ];
 
        return view('frontend.booking.step-4-payment', compact(
            'salon', 'service', 'stylist', 'slot'
        ));
    }
 
 
    /**
     * UPDATED: handles both regular and waitlist bookings.
     *
     * `payments.appointment_id` is NOT NULL in the DB, so every payment —
     * waitlist included — must be linked to a real Appointment row. Since
     * `appointments.start_time` / `end_time` are also NOT NULL, waitlist
     * appointments are created with a '00:00:00' placeholder time and a
     * note flagging them as unassigned. A matching Waitlist row is also
     * created so the salon can track queue position. When a real slot is
     * assigned later, update this Appointment's start_time/end_time then.
     */
    public function postPayment(Request $request, $salon_id)
    {
        $salon = Salon::findOrFail($salon_id);
 
        if (Session::get('booking_salon_id') != $salon_id) {
            return redirect()->route('booking.step1', $salon->id)
                ->with('error', 'Your booking session doesn\'t match this salon. Please start again.');
        }
 
        $serviceId   = Session::get('booking_service_id');
        $stylistId   = Session::get('booking_stylist_id');
        $bookingTime = Session::get('booking_time');
        $bookingDate = Session::get('booking_date');
        $isWaitlist  = Session::get('booking_is_waitlist', false);
 
        if (!$serviceId || !$stylistId || !$bookingDate || (!$bookingTime && !$isWaitlist)) {
            return redirect()->route('booking.step1', $salon->id)
                ->with('error', 'Session expired. Please start again.');
        }
 
        $request->validate([
            'payment_method'  => 'required|in:easypaisa,jazzcash,bank',
            'transaction_ref' => 'required|string|max:255',
            'sender_number'   => 'required|string|max:20',
            'screenshot'      => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'screenshot.required' => 'Please upload your payment screenshot.',
            'screenshot.image'    => 'The file must be an image.',
            'screenshot.max'      => 'Screenshot must be under 5MB.',
        ]);
 
        $service        = Service::findOrFail($serviceId);
        $screenshotPath = $request->file('screenshot')->store('payment-screenshots', 'public');
        $bookingRef     = 'GLM-' . strtoupper(substr(uniqid(), -6));
 
        if ($isWaitlist) {
            // Placeholder appointment — real start/end time gets assigned
            // once a slot actually opens up for this client.
            $appointment = Appointment::create([
                'booking_ref'      => $bookingRef,
                'client_id'        => Auth::id(),
                'salon_id'         => $salon->id,
                'stylist_id'       => $stylistId,
                'service_id'       => $service->id,
                'appointment_date' => $bookingDate,
                'start_time'       => '00:00:00',
                'end_time'         => '00:00:00',
                'total_amount'     => $service->price,
                'advance_amount'   => 100,
                'status'           => 'pending_payment',
                'notes'            => 'Waitlist — awaiting slot assignment',
            ]);
 
            Waitlist::create([
                'client_id'      => Auth::id(),
                'salon_id'       => $salon->id,
                'stylist_id'     => $stylistId,
                'service_id'     => $service->id,
                'time_slot_id'   => null,
                'preferred_date' => $bookingDate,
                'preferred_time' => null,
                'status'         => 'waiting',
                'position'       => Waitlist::where('salon_id', $salon->id)
                                        ->where('status', 'waiting')->count() + 1,
            ]);
        } else {
            $startTime = \Carbon\Carbon::parse($bookingTime)->format('H:i:s');
            $endTime   = \Carbon\Carbon::parse($bookingTime)
                            ->addMinutes($service->duration ?? 60)
                            ->format('H:i:s');
 
            $appointment = Appointment::create([
                'booking_ref'      => $bookingRef,
                'client_id'        => Auth::id(),
                'salon_id'         => $salon->id,
                'stylist_id'       => $stylistId,
                'service_id'       => $service->id,
                'appointment_date' => $bookingDate,
                'start_time'       => $startTime,
                'end_time'         => $endTime,
                'total_amount'     => $service->price,
                'advance_amount'   => 100,
                'status'           => 'pending_payment',
            ]);
        }
 
        Payment::create([
            'appointment_id'  => $appointment->id,
            'client_id'       => Auth::id(),
            'salon_id'        => $salon->id,
            'amount'          => 100,
            'method'          => $request->payment_method,
            'transaction_ref' => $request->transaction_ref,
            'sender_number'   => $request->sender_number,
            'screenshot'      => $screenshotPath,
            'status'          => 'pending',
        ]);
 
        Session::forget([
            'booking_service_id', 'booking_service_ids', 'booking_stylist_id',
            'booking_time', 'booking_date', 'booking_salon_id', 'booking_is_waitlist',
        ]);
 
        return view('frontend.booking.confirmation', compact('appointment'));
    }
 
 
    // ── AJAX: Load available time slots for a date ──
    public function getSlots(Request $request, $salon_id)
    {
        $salon     = Salon::findOrFail($salon_id);
        $date      = $request->date;
        $stylistId = $request->stylist_id ?? Session::get('booking_stylist_id');
        $serviceId = $request->service_id ?? Session::get('booking_service_id');
 
        // Stylist holiday check
        $isHoliday = StylistHoliday::where('stylist_id', $stylistId)
            ->whereDate('date', $date)
            ->exists();
 
        if ($isHoliday) {
            return response()->json(['slots' => [], 'holiday' => true]);
        }
 
        // Already booked times for this stylist on this date
        $bookedTimes = Appointment::where('salon_id', $salon->id)
            ->where('stylist_id', $stylistId)
            ->where('appointment_date', $date)
            ->whereNotIn('status', ['cancelled'])
            ->pluck('start_time')
            ->map(fn($t) => \Carbon\Carbon::parse($t)->format('H:i'))
            ->toArray();
 
        // Generate slots from salon opening to closing time
        $openTime  = \Carbon\Carbon::parse($salon->opening_time  ?? '09:00');
        $closeTime = \Carbon\Carbon::parse($salon->closing_time  ?? '20:00');
        $duration  = $serviceId
            ? (Service::find($serviceId)?->duration_minutes ?? 60)
            : 60;
 
        $slots   = [];
        $current = $openTime->copy();
 
        while ($current->copy()->addMinutes($duration)->lte($closeTime)) {
            $timeStr   = $current->format('H:i');
            $label     = $current->format('h:i A');
            $available = !in_array($timeStr, $bookedTimes);
 
            $slots[] = [
                'time'      => $timeStr,
                'label'     => $label,
                'time_24'   => $timeStr,
                'available' => $available,
            ];
 
            $current->addMinutes(30);
        }
 
        return response()->json(['slots' => $slots, 'holiday' => false]);
    }
 
 
    public function confirmation($booking_id)
    {
        $booking = Appointment::with(['salon', 'service', 'stylist'])
            ->where('client_id', Auth::id())
            ->findOrFail($booking_id);
 
        return view('frontend.booking.confirmation', compact('booking'));
    }
}
 