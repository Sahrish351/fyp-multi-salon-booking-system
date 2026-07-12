<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Salon;
use App\Models\Service;
use App\Models\Stylist;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'salon_id' => 'required|exists:salons,id',
                'service_id' => 'required|exists:services,id',
                'stylist_id' => 'required|exists:stylists,id',
                'appointment_date' => 'required|date|after_or_equal:today',
                'start_time' => 'required|date_format:H:i',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $service = Service::find($request->service_id);
            $startTime = Carbon::parse($request->start_time);
            $endTime = $startTime->copy()->addMinutes($service->duration ?? 60);

            $appointment = Appointment::create([
                'salon_id' => $request->salon_id,
                'client_id' => $user->id,
                'service_id' => $request->service_id,
                'stylist_id' => $request->stylist_id,
                'appointment_date' => $request->appointment_date,
                'start_time' => $startTime->format('H:i:s'),
                'end_time' => $endTime->format('H:i:s'),
                'total_amount' => $service->price ?? 0,
                'status' => 'pending_payment',
                'booking_ref' => 'BK-' . strtoupper(uniqid()),
            ]);

            // ✅ NOTIFICATION: Client ne appointment book ki
            try {
                $salon = Salon::find($request->salon_id);
                $client = Auth::user();
                
                NotificationHelper::send(
                    $request->salon_id,
                    'appointment',
                    [
                        'title' => '📅 New Appointment Booked',
                        'message' => "{$client->name} booked {$service->name} on " . Carbon::parse($request->appointment_date)->format('M d, Y') . ' at ' . $startTime->format('h:i A'),
                        'link' => route('owner.appointments.show', $appointment->id),
                    ]
                );
            } catch (\Exception $e) {
                \Log::error('Appointment notification error: ' . $e->getMessage());
            }

            return redirect()->route('client.appointments.show', $appointment->id)
                ->with('success', 'Appointment booked successfully! Please complete payment.');

        } catch (\Exception $e) {
            \Log::error('Appointment Store Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Unable to book appointment: ' . $e->getMessage())
                ->withInput();
        }
    }
}