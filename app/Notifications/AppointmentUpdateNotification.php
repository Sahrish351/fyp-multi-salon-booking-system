<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class AppointmentUpdateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Appointment $appointment;
    protected string $type;
    protected array $extra;

    public function __construct(Appointment $appointment, string $type, array $extra = [])
    {
        $this->appointment = $appointment;
        $this->type        = $type;
        $this->extra       = $extra;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $salonName = $this->appointment->salon->name ?? 'the salon';
        $timeRaw   = $this->appointment->appointment_time ?? $this->appointment->start_time ?? null;
        $newTime   = $timeRaw ? Carbon::parse($timeRaw)->format('h:i A') : 'N/A';
        $newDate   = $this->appointment->appointment_date 
            ? Carbon::parse($this->appointment->appointment_date)->format('d M Y') 
            : 'N/A';

        $title = match($this->type) {
            'cancelled'   => '❌ Appointment Cancelled',
            'rescheduled' => '🔄 Appointment Rescheduled',
            default       => '📅 Appointment Updated',
        };

        $message = match($this->type) {
            'cancelled'   => "Your appointment with {$salonName} has been cancelled.",
            'rescheduled' => "Your appointment with {$salonName} has been rescheduled to {$newDate} at {$newTime}.",
            default       => "Your appointment with {$salonName} has been updated.",
        };

        $mail = (new MailMessage)
            ->subject($title . ' - ' . $salonName)
            ->greeting('Hello ' . ($notifiable->name ?? 'Client') . '!')
            ->line($message);

        if ($this->type === 'rescheduled') {
            $mail->line("**New Date:** {$newDate}")
                 ->line("**New Time:** {$newTime}");
        }

        return $mail->action('View Details', url('/client/appointments/' . $this->appointment->id))
                    ->line('Thank you for choosing us!');
    }

    public function toDatabase($notifiable): array
    {
        return $this->buildPayload();
    }

    public function toArray($notifiable): array
    {
        return $this->buildPayload();
    }

    protected function buildPayload(): array
    {
        $appointment = $this->appointment;
        $salonName   = $appointment->salon->name ?? 'the salon';

        $messages = [
            'cancelled'   => "Your appointment with {$salonName} has been cancelled.",
            'rescheduled' => "Your appointment with {$salonName} has been rescheduled.",
        ];

        $title = match($this->type) {
            'cancelled'   => '❌ Appointment Cancelled',
            'rescheduled' => '🔄 Appointment Rescheduled',
            default       => '📅 Appointment Updated',
        };

        $timeRaw = $appointment->appointment_time ?? $appointment->start_time ?? null;

        return [
            'title'          => $title,  
            'appointment_id' => $appointment->id,
            'booking_ref'    => $appointment->booking_ref ?? null,
            'type'           => $this->type,
            'message'        => $messages[$this->type] ?? 'Your appointment has been updated.',
            'old_date'       => $this->extra['old_date'] ?? null,
            'old_time'       => $this->extra['old_time'] ?? null,
            'new_date'       => $appointment->appointment_date ? Carbon::parse($appointment->appointment_date)->format('d M Y') : null,
            'new_time'       => $timeRaw ? Carbon::parse($timeRaw)->format('h:i A') : null,
        ];
    }
}