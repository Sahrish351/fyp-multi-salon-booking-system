<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\Appointment;
use App\Mail\AppointmentReminderMail;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-reminders';
    protected $description = 'Send email reminders to clients 2 hours before their appointment';

    public function handle()
    {
        $now = Carbon::now();
        $twoHoursLater = $now->copy()->addHours(2);

        // Find appointments starting in exactly 2 hours (±5 minutes)
        $appointments = Appointment::where('status', 'confirmed')
            ->whereDate('appointment_date', $now->toDateString())
            ->whereTime('start_time', '>=', $twoHoursLater->copy()->subMinutes(5)->format('H:i:s'))
            ->whereTime('start_time', '<=', $twoHoursLater->copy()->addMinutes(5)->format('H:i:s'))
            ->with(['client', 'service', 'stylist', 'salon'])
            ->get();

        $sentCount = 0;

        foreach ($appointments as $appointment) {
            $client = $appointment->client;
            $salon = $appointment->salon;

            if ($client && $client->email) {
                try {
                    Mail::to($client->email)->send(new AppointmentReminderMail($appointment, $client, $salon));
                    $sentCount++;
                    $this->info("Reminder sent to: {$client->email} - {$appointment->booking_ref}");
                } catch (\Exception $e) {
                    Log::error("Reminder email failed: " . $e->getMessage());
                    $this->error("Failed to send reminder to: {$client->email}");
                }
            }
        }

        $this->info("Total reminders sent: {$sentCount}");

        return Command::SUCCESS;
    }
}