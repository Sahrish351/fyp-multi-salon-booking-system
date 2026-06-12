<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use App\Models\Service;
use App\Models\Stylist;
use App\Models\TimeSlot;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BookingController extends Controller
{
    // Step 1 - Show Service Selection Form (GET)
    public function step1Services($salon_id)
    {
        $salon = Salon::with('services.category')->findOrFail($salon_id);
        $services = $salon->services()->where('is_active', true)->with('category')->get();
        
        return view('frontend.booking.step-1-services', compact('salon', 'services'));
    }

    // Step 1 POST - Save Selected Services
    public function postStep1Services(Request $request, $salon_id)
    {
        $salon = Salon::findOrFail($salon_id);
        
        // For single service
        if ($request->has('service_id')) {
            Session::put('booking_service_id', $request->service_id);
        }
        
        // For multiple services
        if ($request->has('service_ids')) {
            Session::put('booking_service_ids', $request->service_ids);
            Session::put('booking_service_id', $request->service_ids[0]);
        }
        
        return redirect()->route('booking.step2', $salon->id);
    }

    // Step 2 - Show Stylist Selection Form (GET)
    public function step2Stylist($salon_id)
    {
        $salon = Salon::findOrFail($salon_id);
        $serviceId = Session::get('booking_service_id');
        
        if (!$serviceId) {
            return redirect()->route('booking.step1', $salon->id)
                ->with('error', 'Please select a service first.');
        }
        
        $service = Service::findOrFail($serviceId);
        $stylists = $salon->stylists()->where('is_active', true)->get();
        
        return view('frontend.booking.step-2-stylist', compact('salon', 'service', 'stylists'));
    }

    // Step 2 POST - Save Selected Stylist
    public function postStep2Stylist(Request $request, $salon_id)
    {
        $salon = Salon::findOrFail($salon_id);
        
        $request->validate([
            'stylist_id' => 'required|exists:stylists,id'
        ]);

        Session::put('booking_stylist_id', $request->stylist_id);

        return redirect()->route('booking.step3', $salon->id);
    }

    // Step 3 - Show Date & Time Selection Form (GET)
    public function step3DateTime($salon_id)
    {
        $salon = Salon::findOrFail($salon_id);
        $serviceId = Session::get('booking_service_id');
        $stylistId = Session::get('booking_stylist_id');
        
        if (!$serviceId || !$stylistId) {
            return redirect()->route('booking.step1', $salon->id)
                ->with('error', 'Please complete previous steps.');
        }
        
        $service = Service::findOrFail($serviceId);
        $stylist = Stylist::findOrFail($stylistId);

        return view('frontend.booking.step-3-datetime', compact('salon', 'service', 'stylist'));
    }

    // Step 3 POST - Save Selected Date & Time
    public function postStep3DateTime(Request $request, $salon_id)
    {
        $salon = Salon::findOrFail($salon_id);
        
        $request->validate([
            'time_slot_id' => 'required|exists:time_slots,id'
        ]);

        Session::put('booking_slot_id', $request->time_slot_id);

        return redirect()->route('booking.step4', $salon->id);
    }

    // Step 4 - Show Payment Page (GET)
    public function step4Payment($salon_id)
    {
        $salon = Salon::findOrFail($salon_id);
        $timeSlotId = Session::get('booking_slot_id');

        if (!$timeSlotId) {
            return redirect()->route('booking.step3', $salon->id)
                             ->with('error', 'Please select a time slot first.');
        }

        $slot = TimeSlot::findOrFail($timeSlotId);

        if (!$slot->isAvailable()) {
            return redirect()->route('booking.step3', $salon->id)
                             ->with('error', 'This slot is no longer available.');
        }

        // Lock the slot for 10 minutes
        $slot->update([
            'status'          => 'locked',
            'locked_by'       => Auth::id(),
            'locked_at'       => now(),
            'lock_expires_at' => now()->addMinutes(10),
        ]);

        $service = Service::findOrFail(Session::get('booking_service_id'));
        $stylist = Stylist::findOrFail(Session::get('booking_stylist_id'));
        $paymentDetails = $salon->paymentDetails()->where('is_active', true)->get();

        return view('frontend.booking.step-4-payment', compact('salon', 'service', 'stylist', 'slot', 'paymentDetails'));
    }

    // Step 4 POST - Process Payment and Confirm Booking
    public function postPayment(Request $request, $salon_id)
    {
        $salon = Salon::findOrFail($salon_id);
        
        // If guest user, redirect to login
        if (!Auth::check()) {
            Session::put('booking_redirect_after_login', route('booking.step4', $salon->id));
            return redirect()->route('login')->with('info', 'Please login to complete your booking.');
        }
        
        $request->validate([
            'method'        => 'required|in:easypaisa,jazzcash',
            'sender_number' => 'required|string',
            'screenshot'    => 'required|image|max:2048',
        ]);

        try {
            DB::transaction(function () use ($request, $salon) {
                $slot    = TimeSlot::findOrFail(Session::get('booking_slot_id'));
                $service = Service::findOrFail(Session::get('booking_service_id'));

                $appointment = Appointment::create([
                    'booking_ref'      => Appointment::generateRef(),
                    'client_id'        => Auth::id(),
                    'salon_id'         => $salon->id,
                    'stylist_id'       => Session::get('booking_stylist_id'),
                    'service_id'       => $service->id,
                    'time_slot_id'     => $slot->id,
                    'appointment_date' => $slot->slot_date,
                    'start_time'       => $slot->start_time,
                    'end_time'         => $slot->end_time,
                    'total_amount'     => $service->price,
                    'advance_amount'   => 100,
                    'status'           => 'payment_submitted',
                ]);

                $screenshotPath = $request->file('screenshot')->store('payment-screenshots', 'public');

                Payment::create([
                    'appointment_id' => $appointment->id,
                    'client_id'      => Auth::id(),
                    'salon_id'       => $salon->id,
                    'amount'         => 100,
                    'method'         => $request->method,
                    'sender_number'  => $request->sender_number,
                    'screenshot'     => $screenshotPath,
                    'status'         => 'pending',
                ]);

                $slot->update(['status' => 'booked']);

                // Clear session
                Session::forget(['booking_service_id', 'booking_service_ids', 'booking_stylist_id', 'booking_slot_id']);
            });

            return redirect()->route('client.appointments.index')
                             ->with('success', 'Booking submitted successfully! Waiting for payment verification.');

        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }
    
    // Confirmation Page
    public function confirmation($booking_id)
    {
        $booking = Appointment::with(['salon', 'service', 'stylist', 'timeSlot'])->findOrFail($booking_id);
        return view('frontend.booking.confirmation', compact('booking'));
    }
}