<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

use App\Models\Appointment;
use App\Mail\AppointmentReminderMail;
use App\Helpers\NotificationHelper;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-reminders';
    protected $description = 'Send reminders 1 day and 2 hours before appointments';

    public function handle()
    {
        $now = Carbon::now();

        $appointments = Appointment::where('status', 'confirmed')
            ->whereDate('appointment_date', '>=', $now->toDateString())
            ->whereDate('appointment_date', '<=', $now->copy()->addDay()->toDateString())
            ->with(['client', 'service', 'stylist', 'salon'])
            ->get();

        $sent = 0;

        foreach ($appointments as $appointment) {
            $client = $appointment->client;
            if (!$client) {
                continue;
            }

            $start = Carbon::parse(
                Carbon::parse($appointment->appointment_date)->toDateString() . ' ' . $appointment->start_time
            );

            
            $mins = $now->diffInMinutes($start, false);

            $type = null;

            
            if ($mins > 1380 && $mins <= 1440 && !$appointment->reminder_1day_sent_at) {
                $type = '1_day';

            } elseif ($mins > 0 && $mins <= 120 && !$appointment->reminder_2h_sent_at) {
                $type = '2_hours';
            }

            if (!$type) {
                continue;
            }

            try {
                $service = $appointment->service->name ?? 'your service';
                $salon   = $appointment->salon->name ?? 'the salon';
                $time    = Carbon::parse($appointment->start_time)->format('h:i A');

                
                if ($client->email) {
                    Mail::to($client->email)->send(
                        new AppointmentReminderMail($appointment, $client, $appointment->salon, $type)
                    );
                }

              
                NotificationHelper::sendToUser(
                    $client->id,
                    $appointment->salon_id,
                    'reminder',
                    [
                        'title'   => $type === '1_day' ? 'Appointment Tomorrow' : 'Appointment in 2 Hours',
                        'message' => $type === '1_day'
                            ? "Your appointment for {$service} at {$salon} is tomorrow at {$time}."
                            : "Your appointment for {$service} at {$salon} is in 2 hours ({$time}).",
                        'link'    => null,
                    ]
                );

          
                $appointment->forceFill([
                    $type === '1_day' ? 'reminder_1day_sent_at' : 'reminder_2h_sent_at' => now(),
                ])->save();

                $sent++;
                $this->info("{$type} reminder sent: {$client->email} - {$appointment->booking_ref}");
            } catch (\Exception $e) {
                Log::error('Reminder failed for ' . $appointment->booking_ref . ': ' . $e->getMessage());
                $this->error("Failed: {$appointment->booking_ref}");
            }
        }

        $this->info("Total reminders sent: {$sent}");

        return Command::SUCCESS;
    }
}