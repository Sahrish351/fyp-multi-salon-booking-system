<?php
 
namespace App\Console\Commands;
 
use App\Helpers\NotificationHelper;
use App\Mail\AppointmentStatusMail;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
 
class ExpireRejectedPayments extends Command
{
    protected $signature = 'appointments:expire-rejected';
 
    protected $description = 'Cancel appointments whose rejected payment was not re-submitted within the allowed time';
 
    public function handle(): int
    {
        $hours  = Payment::RESUBMIT_HOURS;
        $cutoff = now()->subHours($hours);
 
        
        $appointments = Appointment::where('status', 'pending_payment')
            ->whereHas('payment', function ($q) use ($cutoff) {
                $q->where('status', 'rejected')
                  ->where('updated_at', '<=', $cutoff);
            })
            ->with(['client', 'service', 'stylist', 'payment'])
            ->get();
 
        if ($appointments->isEmpty()) {
            $this->info('No appointments to cancel.');
            return self::SUCCESS;
        }
 
        foreach ($appointments as $appointment) {
            $appointment->update([
                'status'              => 'cancelled',
                'cancelled_at'        => now(),
                'cancellation_reason' => "Payment was rejected and not re-submitted within {$hours} hours.",
            ]);
 
           
            try {
                if ($appointment->client_id) {
                    NotificationHelper::send($appointment->client_id, 'appointment_cancelled', [
                        'title'   => '⚠️ Booking Cancelled',
                        'message' => "Your booking {$appointment->booking_ref} was cancelled because the payment was not re-submitted within {$hours} hours.",
                        'link'    => route('client.appointments.show', $appointment->id),
                    ]);
                }
            } catch (\Exception $e) {
                \Log::warning('Expire notification failed: ' . $e->getMessage());
            }
 
           
            if ($appointment->client && $appointment->client->email) {
                try {
                    Mail::to($appointment->client->email)->send(new AppointmentStatusMail($appointment));
                } catch (\Exception $e) {
                    \Log::error('Mail Error (Expire rejected payment): ' . $e->getMessage());
                }
            }
 
            $this->line("Cancelled: {$appointment->booking_ref}");
        }
 
        $this->info("Done. {$appointments->count()} appointment(s) cancelled.");
 
        return self::SUCCESS;
    }
}