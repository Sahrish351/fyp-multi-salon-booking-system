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

        if (!$serviceId || !$stylistId) {
            return redirect()->route('booking.step1', $salon->id)
                ->with('error', 'Please complete previous steps first.');
        }

        $service = Service::findOrFail($serviceId);
        $stylist = Stylist::findOrFail($stylistId);

        return view('frontend.booking.step-3-datetime', compact('salon', 'service', 'stylist'));
    }


    public function postStep3DateTime(Request $request, $salon_id)
    {
        $salon = Salon::findOrFail($salon_id);


        if ($request->has('join_waitlist') && $request->join_waitlist == '1') {
            return $this->joinWaitlistFromStep3($request, $salon);
        }

        // Use app timezone (Asia/Karachi) for "today" comparison instead of
        // the server's default timezone, so late-night bookings don't get
        // silently rejected as "before today".
        $request->validate([
            'time_slot_id'     => 'required|string',
            'appointment_date' => 'required|date_format:Y-m-d|after_or_equal:' . now()->format('Y-m-d'),
        ], [
            'appointment_date.after_or_equal' => 'Please select today or a future date.',
            'appointment_date.date_format'    => 'Invalid date format selected. Please pick the date again.',
        ]);

        Session::put('booking_time', $request->time_slot_id);
        Session::put('booking_date', $request->appointment_date);


        return redirect()->route('booking.step4', $salon->id);
    }

    private function joinWaitlistFromStep3(Request $request, Salon $salon)
    {
        $request->validate([
            'appointment_date' => 'required|date',
        ]);

        $serviceId = Session::get('booking_service_id');
        $stylistId = Session::get('booking_stylist_id');

        Waitlist::create([
            'client_id'       => Auth::id(),
            'salon_id'        => $salon->id,
            'stylist_id'      => $stylistId,
            'service_id'      => $serviceId,
            'preferred_date'  => $request->appointment_date,
            'status'          => 'waiting',
            'position'        => Waitlist::where('salon_id', $salon->id)
                                    ->where('status', 'waiting')->count() + 1,
        ]);

        return redirect()->route('client.waitlist.index')
            ->with('success', 'You have been added to the waitlist! We will notify you when a slot opens up.');
    }


    public function step4Payment($salon_id)
    {
        $salon       = Salon::findOrFail($salon_id);
        $serviceId   = Session::get('booking_service_id');
        $stylistId   = Session::get('booking_stylist_id');
        $bookingTime = Session::get('booking_time');
        $bookingDate = Session::get('booking_date');

        if (!$serviceId || !$stylistId) {
            return redirect()->route('booking.step1', $salon->id)
                ->with('error', 'Please complete all booking steps.');
        }

        if (!$bookingTime || !$bookingDate) {
            return redirect()->route('booking.step3', $salon->id)
                ->with('error', 'Please select a date and time first.');
        }

        $service = Service::findOrFail($serviceId);
        $stylist = Stylist::findOrFail($stylistId);

        $slot = (object) [
            'slot_date'  => $bookingDate,
            'start_time' => \Carbon\Carbon::parse($bookingTime)->format('H:i:s'),
            'end_time'   => \Carbon\Carbon::parse($bookingTime)
                                ->addMinutes($service->duration ?? 60)
                                ->format('H:i:s'),
        ];

        return view('frontend.booking.step-4-payment', compact(
            'salon', 'service', 'stylist', 'slot'
        ));
    }


    // ── STEP 4 POST: Receive screenshot + create pending appointment ──
    public function postPayment(Request $request, $salon_id)
    {
        $salon = Salon::findOrFail($salon_id);

        $serviceId   = Session::get('booking_service_id');
        $stylistId   = Session::get('booking_stylist_id');
        $bookingTime = Session::get('booking_time');
        $bookingDate = Session::get('booking_date');

        // If any part of the booking session is missing, start over instead
        // of silently defaulting the date to "now" (which caused wrong
        // bookings before).
        if (!$serviceId || !$stylistId || !$bookingTime || !$bookingDate) {
            return redirect()->route('booking.step1', $salon->id)
                ->with('error', 'Session expired. Please start again.');
        }

        // Validate the form
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

        $service = Service::findOrFail($serviceId);

        $startTime = \Carbon\Carbon::parse($bookingTime)->format('H:i:s');
        $endTime   = \Carbon\Carbon::parse($bookingTime)
                        ->addMinutes($service->duration ?? 60)
                        ->format('H:i:s');

        $bookingRef = 'GLM-' . strtoupper(substr(uniqid(), -6));

        // Save screenshot to storage/app/public/payment-screenshots/
        $screenshotPath = $request->file('screenshot')
            ->store('payment-screenshots', 'public');

        // Create appointment with status 'pending_payment' (becomes 'confirmed'
        // when admin approves the screenshot in the admin panel)
        // NOTE: time_slot_id column was made nullable via migration because
        // this app does not use a separate time_slots table — the actual
        // slot is represented by start_time / end_time below.
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

        // Save payment record with screenshot path
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

        // Clear booking session
        Session::forget([
            'booking_service_id', 'booking_service_ids', 'booking_stylist_id',
            'booking_time', 'booking_date', 'booking_salon_id',
        ]);

        // // Optional: notify admin that a new payment screenshot arrived
        // try {
        //     $appointment->salon->owner->notify(
        //         new \App\Notifications\NewPaymentAlert($appointment)
        //     );
        // } catch (\Exception $e) {
        //     Log::warning('Owner payment alert failed: ' . $e->getMessage());
        // }

        // Show the pending confirmation page
        return view('frontend.booking.confirmation', compact('appointment'));
    }


    // public function payfastReturn(Request $request)
    // {
    //     $paymentId = Session::get('pending_payment_id');
    //     $payment   = Payment::find($paymentId);

    //     if ($payment) {
    //         $payment->update([
    //             'status'         => 'approved',
    //             'transaction_id' => 'SANDBOX-' . strtoupper(uniqid()),
    //         ]);

    //         $appointment = $payment->appointment;
    //         $appointment->update(['status' => 'payment_submitted']);

    //         Session::forget([
    //             'booking_service_id', 'booking_service_ids', 'booking_stylist_id',
    //             'booking_time', 'booking_date', 'booking_salon_id', 'pending_payment_id',
    //         ]);

    //         try {
    //             $appointment->salon->owner->notify(
    //                 new \App\Notifications\NewPaymentAlert($appointment)
    //             );
    //         } catch (\Exception $e) {
    //             Log::warning('Owner notification failed: ' . $e->getMessage());
    //         }

    //         // ✅ Show the confetti success page directly with the real
    //         // appointment data, instead of redirecting to appointments list
    //         return view('frontend.booking.confirmation', compact('appointment'));
    //     }

    //     return redirect()->route('client.appointments.index')
    //         ->with('error', 'Payment record not found.');
    // }


    // public function payfastCancel(Request $request)
    // {
    //     $paymentId = Session::get('pending_payment_id');
    //     $payment   = Payment::find($paymentId);

    //     if ($payment) {
    //         $payment->update(['status' => 'cancelled']);
    //         $payment->appointment->update(['status' => 'cancelled']);
    //     }

    //     return redirect()->route('booking.step4', Session::get('booking_salon_id'))
    //         ->with('error', 'Payment was cancelled. Please try again.');
    // }


    // public function payfastNotify(Request $request)
    // {
    //     Log::info('PayFast IPN received: ' . json_encode($request->all()));

    //     // In sandbox/demo mode just acknowledge receipt.
    //     // In production verify the signature/hash here before trusting data.
    //     return response('OK', 200);
    // }


    // public function getSlots(Request $request, $salon_id)
    // {
    //     $salon     = Salon::findOrFail($salon_id);
    //     $date      = $request->date;
    //     $stylistId = $request->stylist_id ?? Session::get('booking_stylist_id');
    //     $serviceId = $request->service_id ?? Session::get('booking_service_id');

    //     $bookedTimes = Appointment::where('salon_id', $salon->id)
    //         ->where('stylist_id', $stylistId)
    //         ->where('appointment_date', $date)
    //         ->whereNotIn('status', ['cancelled'])
    //         ->pluck('start_time')
    //         ->map(fn($t) => \Carbon\Carbon::parse($t)->format('H:i'))
    //         ->toArray();

    //     $isHoliday = StylistHoliday::where('stylist_id', $stylistId)
    //         ->whereDate('date', $date)
    //         ->exists();

    //     if ($isHoliday) {
    //         return response()->json(['slots' => [], 'holiday' => true]);
    //     }

    //     $openTime  = \Carbon\Carbon::parse($salon->opening_time  ?? '09:00');
    //     $closeTime = \Carbon\Carbon::parse($salon->closing_time  ?? '20:00');
    //     $duration  = $serviceId
    //         ? (Service::find($serviceId)->duration_minutes ?? 60)
    //         : 60;

    //     $slots   = [];
    //     $current = $openTime->copy();

    //     while ($current->copy()->addMinutes($duration)->lte($closeTime)) {
    //         $timeStr = $current->format('H:i');
    //         $label   = $current->format('h:i A');
    //         $slots[] = [
    //             'time'      => $label,
    //             'time_24'   => $timeStr,
    //             'label'     => $label,
    //             'available' => !in_array($timeStr, $bookedTimes),
    //         ];
    //         $current->addMinutes(30);
    //     }

    //     return response()->json(['slots' => $slots, 'holiday' => false]);
    // }


    public function confirmation($booking_id)
    {
        $booking = Appointment::with(['salon', 'service', 'stylist'])
            ->where('client_id', Auth::id())
            ->findOrFail($booking_id);

        return view('frontend.booking.confirmation', compact('booking'));
    }
}