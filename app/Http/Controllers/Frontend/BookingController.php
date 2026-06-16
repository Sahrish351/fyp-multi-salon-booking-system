<?php
// FILE: app/Http/Controllers/Frontend/BookingController.php
 
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
use Illuminate\Support\Facades\Log;
 
class BookingController extends Controller
{
    // ── STEP 1 GET: Select Service ──────────────────────────────
    public function step1Services($salon_id)
    {
        $salon    = Salon::with('services.category')->findOrFail($salon_id);
        $services = $salon->services()->where('is_active', true)->with('category')->get();
        return view('frontend.booking.step-1-services', compact('salon', 'services'));
    }
 
    // ── STEP 1 POST ─────────────────────────────────────────────
    public function postStep1Services(Request $request, $salon_id)
    {
        $salon = Salon::findOrFail($salon_id);
 
        if ($request->has('service_ids') && count($request->service_ids) > 0) {
            Session::put('booking_service_id',  $request->service_ids[0]);
            Session::put('booking_service_ids', $request->service_ids);
        } elseif ($request->has('service_id')) {
            Session::put('booking_service_id',  $request->service_id);
            Session::put('booking_service_ids', [$request->service_id]);
        } else {
            return back()->with('error', 'Please select at least one service.');
        }
 
        return redirect()->route('booking.step2', $salon->id);
    }
 
    // ── STEP 2 GET: Select Stylist ──────────────────────────────
    public function step2Stylist($salon_id)
    {
        $salon     = Salon::findOrFail($salon_id);
        $serviceId = Session::get('booking_service_id');
 
        if (!$serviceId) {
            return redirect()->route('booking.step1', $salon->id)
                ->with('error', 'Please select a service first.');
        }
 
        $service  = Service::findOrFail($serviceId);
        $stylists = $salon->stylists()->where('is_active', true)->get();
 
        return view('frontend.booking.step-2-stylist', compact('salon', 'service', 'stylists'));
    }
 
    // ── STEP 2 POST ─────────────────────────────────────────────
    public function postStep2Stylist(Request $request, $salon_id)
    {
        $salon = Salon::findOrFail($salon_id);
 
        // "any" = no preference, pick first active stylist
        if ($request->stylist_id === 'any') {
            $stylist = $salon->stylists()->where('is_active', true)->first();
            if (!$stylist) {
                return back()->with('error', 'No stylists available.');
            }
            Session::put('booking_stylist_id', $stylist->id);
        } else {
            $request->validate(['stylist_id' => 'required|exists:stylists,id']);
            Session::put('booking_stylist_id', $request->stylist_id);
        }
 
        return redirect()->route('booking.step3', $salon->id);
    }
 
    // ── STEP 3 GET: Select Date & Time ──────────────────────────
    public function step3DateTime($salon_id)
    {
        $salon     = Salon::findOrFail($salon_id);
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
 
    // ── STEP 3 POST ─────────────────────────────────────────────
    // FIX: time_slot_id is a TIME STRING (e.g. "10:00 AM"), not a DB id
    public function postStep3DateTime(Request $request, $salon_id)
    {
        $salon = Salon::findOrFail($salon_id);
 
        $request->validate([
            'time_slot_id'     => 'required|string',   // time string e.g. "10:00 AM"
            'appointment_date' => 'nullable|date',
        ]);
 
        // Save time string and date to session
        Session::put('booking_time',  $request->time_slot_id);
        Session::put('booking_date',  $request->appointment_date ?? now()->format('Y-m-d'));
 
        return redirect()->route('booking.step4', $salon->id);
    }
 
    // ── STEP 4 GET: Payment Page ────────────────────────────────
    public function step4Payment($salon_id)
    {
        $salon     = Salon::findOrFail($salon_id);
        $serviceId = Session::get('booking_service_id');
        $stylistId = Session::get('booking_stylist_id');
        $bookingTime = Session::get('booking_time');
        $bookingDate = Session::get('booking_date');
 
        // Redirect if steps incomplete
        if (!$serviceId || !$stylistId) {
            return redirect()->route('booking.step1', $salon->id)
                ->with('error', 'Please complete all booking steps.');
        }
 
        if (!$bookingTime) {
            return redirect()->route('booking.step3', $salon->id)
                ->with('error', 'Please select a date and time first.');
        }
 
        $service = Service::findOrFail($serviceId);
        $stylist = Stylist::findOrFail($stylistId);
 
        // Build a fake slot object for the view
        $slot = (object)[
            'slot_date'  => $bookingDate,
            'start_time' => \Carbon\Carbon::parse($bookingTime)->format('H:i:s'),
            'end_time'   => \Carbon\Carbon::parse($bookingTime)->addMinutes($service->duration_minutes ?? 60)->format('H:i:s'),
        ];
 
        $paymentDetails = $salon->paymentDetails()->where('is_active', true)->get();
 
        return view('frontend.booking.step-4-payment', compact(
            'salon', 'service', 'stylist', 'slot', 'paymentDetails'
        ));
    }
 
    // ── STEP 4 POST: Process Payment ────────────────────────────
    public function postPayment(Request $request, $salon_id)
    {
        $salon = Salon::findOrFail($salon_id);
 
        if (!Auth::check()) {
            Session::put('booking_redirect_after_login', route('booking.step4', $salon->id));
            return redirect()->route('login')
                ->with('info', 'Please login to complete your booking.');
        }
 
        $request->validate([
            'payment_method' => 'required|in:stripe,easypaisa,jazzcash,bank_transfer',
        ]);
 
        $serviceId   = Session::get('booking_service_id');
        $stylistId   = Session::get('booking_stylist_id');
        $bookingTime = Session::get('booking_time');
        $bookingDate = Session::get('booking_date', now()->format('Y-m-d'));
 
        if (!$serviceId || !$stylistId || !$bookingTime) {
            return redirect()->route('booking.step1', $salon->id)
                ->with('error', 'Session expired. Please start again.');
        }
 
        $service = Service::findOrFail($serviceId);
 
        try {
            DB::transaction(function () use ($request, $salon, $service, $stylistId, $bookingDate, $bookingTime) {
 
                $startTime = \Carbon\Carbon::parse($bookingTime)->format('H:i:s');
                $endTime   = \Carbon\Carbon::parse($bookingTime)
                                ->addMinutes($service->duration_minutes ?? 60)
                                ->format('H:i:s');
 
                // Generate booking ref
                $bookingRef = 'GLM-' . strtoupper(substr(uniqid(), -6));
 
              
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
                    'status'           => 'payment_submitted',
                ]);
 
            
                $screenshotPath = null;
                if ($request->hasFile('screenshot')) {
                    $screenshotPath = $request->file('screenshot')
                        ->store('payment-screenshots', 'public');
                }
 
                
                $stripeId = null;
                if ($request->payment_method === 'stripe') {
                    $stripeId = $request->stripe_payment_method_id;
                }
 
            
                Payment::create([
                    'appointment_id'    => $appointment->id,
                    'client_id'         => Auth::id(),
                    'salon_id'          => $salon->id,
                    'amount'            => 100,
                    'method'            => $request->payment_method,
                    'sender_number'     => $request->sender_number ?? null,
                    'transaction_id'    => $request->transaction_id ?? $stripeId,
                    'screenshot'        => $screenshotPath,
                    'status'            => $request->payment_method === 'stripe' ? 'approved' : 'pending',
                    'stripe_payment_id' => $stripeId,
                ]);
 
             
                Session::forget([
                    'booking_service_id', 'booking_service_ids',
                    'booking_stylist_id', 'booking_time', 'booking_date',
                    'booking_slot_id',
                ]);
 
            
                try {
                    $salon->owner->notify(
                        new \App\Notifications\NewPaymentAlert($appointment)
                    );
                } catch (\Exception $e) {
                    Log::warning('Owner notification failed: ' . $e->getMessage());
                }
            });
 
            return redirect()->route('client.appointments.index')
                ->with('success', '🎉 Booking confirmed! Awaiting payment verification.');
 
        } catch (\Exception $e) {
            Log::error('Booking error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
 
    
    public function getSlots(Request $request, $salon_id)
    {
        $salon     = Salon::findOrFail($salon_id);
        $date      = $request->date;
        $stylistId = $request->stylist_id ?? Session::get('booking_stylist_id');
        $serviceId = $request->service_id ?? Session::get('booking_service_id');
 
       
        $bookedTimes = Appointment::where('salon_id', $salon->id)
            ->where('stylist_id', $stylistId)
            ->where('appointment_date', $date)
            ->whereNotIn('status', ['cancelled'])
            ->pluck('start_time')
            ->map(fn($t) => \Carbon\Carbon::parse($t)->format('H:i'))
            ->toArray();
 
        $isHoliday = \App\Models\StylistHoliday::where('stylist_id', $stylistId)
            ->whereDate('date', $date)
            ->exists();
 
        if ($isHoliday) {
            return response()->json(['slots' => [], 'message' => 'Stylist is on holiday']);
        }
 
        // Generate slots
        $openTime  = \Carbon\Carbon::parse($salon->opening_time  ?? '09:00');
        $closeTime = \Carbon\Carbon::parse($salon->closing_time  ?? '20:00');
        $duration  = $serviceId
            ? (Service::find($serviceId)->duration_minutes ?? 60)
            : 60;
 
        $slots   = [];
        $current = $openTime->copy();
 
        while ($current->copy()->addMinutes($duration)->lte($closeTime)) {
            $timeStr = $current->format('H:i');
            $label   = $current->format('h:i A');
            $slots[] = [
                'time'      => $label,        // "10:00 AM"  — what JS sends back
                'time_24'   => $timeStr,      // "10:00"
                'label'     => $label,
                'available' => !in_array($timeStr, $bookedTimes),
            ];
            $current->addMinutes(30);
        }
 
        return response()->json(['slots' => $slots]);
    }
}