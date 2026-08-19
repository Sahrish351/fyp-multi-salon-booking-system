<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use App\Models\Service;
use App\Models\Category;
use App\Models\Stylist;
use App\Models\TimeSlot;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\StylistHoliday;
use App\Models\Waitlist;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class BookingController extends Controller
{
    public function step1Services($salon_id)
    {
        $salon = Salon::findOrFail($salon_id);
        $services = Service::where('salon_id', $salon->id)->get();
        $categories = Category::all();

        return view('frontend.booking.step-1-services', compact('salon', 'services', 'categories'));
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
        $stylists = Stylist::where('salon_id', $salon->id)->get();

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
            $stylist = Stylist::where('salon_id', $salon->id)->inRandomOrder()->first();
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

    public function postStep3DateTime(Request $request, $salon_id)
    {
        $salon = Salon::findOrFail($salon_id);

        if (Session::get('booking_salon_id') != $salon_id) {
            return redirect()->route('booking.step1', $salon->id)
                ->with('error', 'Your booking session doesn\'t match this salon. Please start again.');
        }

        if ($request->has('join_waitlist') && $request->join_waitlist == '1') {
            $request->validate([
                'appointment_date' => 'required|date',
            ]);

            Session::put('booking_date', $request->appointment_date);
            Session::put('booking_time', null);
            Session::put('booking_is_waitlist', true);

            return redirect()->route('booking.step4', $salon->id);
        }

        $request->validate([
            'time_slot_id'     => 'required|string',
            'appointment_date' => 'required|date_format:Y-m-d',
        ]);

        Session::put('booking_time', $request->time_slot_id);
        Session::put('booking_date', $request->appointment_date);
        Session::put('booking_is_waitlist', false);

        return redirect()->route('booking.step4', $salon->id);
    }

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
        $client         = Auth::user();
        $screenshotPath = $request->file('screenshot')->store('payment-screenshots', 'public');
        $bookingRef     = 'GLM-' . strtoupper(substr(uniqid(), -6));

        if ($isWaitlist) {
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

            $waitlistPosition = Waitlist::where('salon_id', $salon->id)
                                    ->where('status', 'waiting')->count() + 1;

            Waitlist::create([
                'client_id'      => Auth::id(),
                'salon_id'       => $salon->id,
                'stylist_id'     => $stylistId,
                'service_id'     => $service->id,
                'time_slot_id'   => null,
                'preferred_date' => $bookingDate,
                'preferred_time' => null,
                'status'         => 'waiting',
                'position'       => $waitlistPosition,
            ]);

            try {
                NotificationHelper::send(
                    $salon->id,
                    'waitlist',
                    [
                        'title'   => '⏳ New Waitlist Join',
                        'message' => "{$client->name} joined the waitlist for {$service->name} on " . \Carbon\Carbon::parse($bookingDate)->format('M d, Y') . " (position #{$waitlistPosition})",
                        'link'    => route('owner.waitlist.index'),
                    ]
                );
            } catch (\Exception $e) {
                Log::error('Waitlist join notification error: ' . $e->getMessage());
            }

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

            try {
                NotificationHelper::send(
                    $salon->id,
                    'appointment',
                    [
                        'title'   => '📅 New Appointment Booked',
                        'message' => "{$client->name} booked {$service->name} on " . \Carbon\Carbon::parse($bookingDate)->format('M d, Y') . ' at ' . \Carbon\Carbon::parse($bookingTime)->format('h:i A'),
                        'link'    => route('owner.appointments.show', $appointment->id),
                    ]
                );
            } catch (\Exception $e) {
                Log::error('Appointment booking notification error: ' . $e->getMessage());
            }
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

        try {
            NotificationHelper::send(
                $salon->id,
                'payment',
                [
                    'title'   => '💰 New Payment Received',
                    'message' => "{$client->name} made a payment of PKR 100 via " . ucfirst($request->payment_method),
                    'link'    => route('owner.payments.index'),
                ]
            );
        } catch (\Exception $e) {
            Log::error('Payment notification error: ' . $e->getMessage());
        }

        Session::forget([
            'booking_service_id', 'booking_service_ids', 'booking_stylist_id',
            'booking_time', 'booking_date', 'booking_salon_id', 'booking_is_waitlist',
        ]);

        return view('frontend.booking.confirmation', compact('appointment'));
    }

    // ============================================================
    // AJAX SLOT FETCHING (FIXED COLUMN MAPPING)
    // ============================================================
    public function getSlots(Request $request, $salon_id)
    {
        try {
            $salon     = Salon::findOrFail($salon_id);
            $date      = $request->query('date');
            $stylistId = $request->query('stylist_id') ?? Session::get('booking_stylist_id');

            if (!$date || !$stylistId) {
                return response()->json(['slots' => [], 'holiday' => false]);
            }

            // 1. Safe Holiday Check
            $isHoliday = false;
            if (class_exists(\App\Models\StylistHoliday::class)) {
                $holidayQuery = StylistHoliday::where('stylist_id', $stylistId);

                if (Schema::hasColumn('stylist_holidays', 'date')) {
                    $holidayQuery->whereDate('date', $date);
                    $isHoliday = $holidayQuery->exists();
                } elseif (Schema::hasColumn('stylist_holidays', 'holiday_date')) {
                    $holidayQuery->whereDate('holiday_date', $date);
                    $isHoliday = $holidayQuery->exists();
                } elseif (Schema::hasColumn('stylist_holidays', 'start_date')) {
                    $holidayQuery->where(function($q) use ($date) {
                        $q->whereDate('start_date', '<=', $date)
                          ->whereDate('end_date', '>=', $date);
                    });
                    $isHoliday = $holidayQuery->exists();
                }
            }

            if ($isHoliday) {
                return response()->json(['slots' => [], 'holiday' => true]);
            }

            // 2. Fetch Booked Times
            $bookedTimes = Appointment::where('salon_id', $salon->id)
                ->where('stylist_id', $stylistId)
                ->where('appointment_date', $date)
                ->whereNotIn('status', ['cancelled', 'rejected'])
                ->pluck('start_time')
                ->map(fn($t) => \Carbon\Carbon::parse($t)->format('H:i'))
                ->toArray();

            // 3. Query TimeSlots
            $slotsQuery = TimeSlot::where('salon_id', $salon->id);

            if (Schema::hasColumn('time_slots', 'stylist_id')) {
                $slotsQuery->where(function($q) use ($stylistId) {
                    $q->where('stylist_id', $stylistId)->orWhereNull('stylist_id');
                });
            }

            if (Schema::hasColumn('time_slots', 'slot_date')) {
                $slotsQuery->where(function($q) use ($date) {
                    $q->where('slot_date', $date)->orWhereNull('slot_date');
                });
            }

            $dbSlots = $slotsQuery->orderBy('start_time', 'asc')->get();
            $slots = [];

            foreach ($dbSlots as $slot) {
                $timeStr = \Carbon\Carbon::parse($slot->start_time ?? $slot->time)->format('H:i');
                $label   = \Carbon\Carbon::parse($slot->start_time ?? $slot->time)->format('h:i A');

                $isAvailable = ($slot->status ?? 'available') === 'available' && !in_array($timeStr, $bookedTimes);

                $slots[] = [
                    'id'        => $slot->id,
                    'time'      => $timeStr,
                    'label'     => $label,
                    'time_24'   => $timeStr,
                    'available' => (bool)$isAvailable,
                ];
            }

            return response()->json(['slots' => $slots, 'holiday' => false]);

        } catch (\Exception $e) {
            Log::error('Slot fetch error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function confirmation($booking_id)
    {
        $booking = Appointment::with(['salon', 'service', 'stylist'])
            ->where('client_id', Auth::id())
            ->findOrFail($booking_id);

        return view('frontend.booking.confirmation', compact('booking'));
    }
}