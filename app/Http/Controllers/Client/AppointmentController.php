<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Salon;
use App\Models\Service;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\OwnerNotificationEmail;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'salon_id'         => 'required|exists:salons,id',
            'service_id'       => 'required|exists:services,id',
            'stylist_id'       => 'required|exists:stylists,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'start_time'       => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user      = Auth::user();
            $service   = Service::findOrFail($request->service_id);
            $startTime = Carbon::parse($request->start_time);
            $endTime   = $startTime->copy()->addMinutes($service->duration ?? 60);

            $existingAppointment = Appointment::where('stylist_id', $request->stylist_id)
                ->where('appointment_date', $request->appointment_date)
                ->whereIn('status', ['confirmed', 'pending_payment', 'pending'])
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->whereBetween('start_time', [$startTime->format('H:i:s'), $endTime->format('H:i:s')])
                        ->orWhereBetween('end_time', [$startTime->format('H:i:s'), $endTime->format('H:i:s')]);
                })->exists();

            if ($existingAppointment) {
                return redirect()->back()
                    ->with('error', 'The selected stylist is not available at this time slot.')
                    ->withInput();
            }

            $appointment = DB::transaction(function () use ($request, $user, $service, $startTime, $endTime) {
                return Appointment::create([
                    'salon_id'         => $request->salon_id,
                    'client_id'        => $user->id,
                    'service_id'       => $request->service_id,
                    'stylist_id'       => $request->stylist_id,
                    'appointment_date' => $request->appointment_date,
                    'start_time'       => $startTime->format('H:i:s'),
                    'end_time'         => $endTime->format('H:i:s'),
                    'total_amount'     => $service->price ?? 0,
                    'status'           => 'pending_payment',
                    'booking_ref'      => 'BK-' . strtoupper(uniqid()),
                ]);
            });

            // Notification & Email Sending Block
            try {
                // Dashboard Notification
                NotificationHelper::send(
                    $request->salon_id, 
                    'appointment',
                    [
                        'title'   => '📅 New Appointment Booked',
                        'message' => "{$user->name} booked {$service->name} on " . Carbon::parse($request->appointment_date)->format('M d, Y') . ' at ' . $startTime->format('h:i A'),
                        'link'    => route('owner.appointments.show', $appointment->id),
                    ]
                );

                // Salon Owner Email Fetching
                $salon      = Salon::with('owner')->find($request->salon_id);
                $ownerEmail = $salon->owner->email ?? $salon->email ?? null;

                // Log trace to verify email value in log file
                \Log::info('DEBUG OWNER EMAIL: ' . ($ownerEmail ?? 'NOT FOUND'));

                if ($ownerEmail) {
                    $emailSubject = "📅 New Appointment Alert: " . $appointment->booking_ref;
                    $emailBody    = "Client <strong>{$user->name}</strong> ne nayi appointment book ki hai.<br><br>" .
                                   "<strong>Service:</strong> {$service->name}<br>" .
                                   "<strong>Date:</strong> " . Carbon::parse($request->appointment_date)->format('M d, Y') . "<br>" .
                                   "<strong>Time:</strong> " . $startTime->format('h:i A') . "<br>" .
                                   "<strong>Booking Ref:</strong> {$appointment->booking_ref}";

                    Mail::to($ownerEmail)->send(new OwnerNotificationEmail($emailSubject, $emailBody));
                    \Log::info('MAIL SENT SUCCESSFULLY TO: ' . $ownerEmail);
                } else {
                    \Log::warning('NO OWNER EMAIL FOUND FOR SALON ID: ' . $request->salon_id);
                }

            } catch (\Exception $e) {
                \Log::error('Mail Exception Error: ' . $e->getMessage());
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