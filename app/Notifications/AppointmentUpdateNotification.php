<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class AppointmentUpdateNotification extends Notification
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
        return ['database'];
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
            'cancelled' => '❌ Appointment Cancelled',
            'rescheduled' => '🔄 Appointment Rescheduled',
            default => '📅 Appointment Updated',
        };

        return [
            'id' => (string) Str::uuid(),
            'title' => $title,  
            'appointment_id' => $appointment->id,
            'booking_ref' => $appointment->booking_ref,
            'type' => $this->type,
            'message' => $messages[$this->type] ?? 'Your appointment has been updated.',
            'old_date' => $this->extra['old_date'] ?? null,
            'old_time' => $this->extra['old_time'] ?? null,
            'new_date' => optional($appointment->appointment_date)->format('d M Y'),
            'new_time' => $appointment->start_time
                                    ? \Carbon\Carbon::parse($appointment->start_time)->format('h:i A')
                                    : null,
        ];
    }
}