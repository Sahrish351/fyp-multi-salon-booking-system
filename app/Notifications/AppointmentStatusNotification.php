<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $appointment;
    public $status;
    public $message;

    public function __construct($appointment, $status, $message = null)
    {
        $this->appointment = $appointment;
        $this->status = $status;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $statusText = ucfirst($this->status);
        $salonName = $this->appointment->salon->name ?? 'Salon';
        $serviceName = $this->appointment->service->name ?? 'Service';
        $date = \Carbon\Carbon::parse($this->appointment->appointment_date)->format('l, F d, Y');
        $time = \Carbon\Carbon::parse($this->appointment->start_time)->format('g:i A');

        $subject = $this->status === 'confirmed' 
            ? '✅ Appointment Confirmed - ' . $salonName 
            : '❌ Appointment Cancelled - ' . $salonName;

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . ($notifiable->name ?? 'Client') . '!')
            ->line("Your appointment has been **" . $statusText . "**.")
            ->line("**Booking Ref:** {$this->appointment->booking_ref}")
            ->line("**Salon:** {$salonName}")
            ->line("**Service:** {$serviceName}")
            ->line("**Date:** {$date}")
            ->line("**Time:** {$time}")
            ->line($this->message ?? 'Thank you for choosing us!')
            ->action('View Appointment', url('/client/appointments/' . $this->appointment->id));
    }

    public function toArray($notifiable)
    {
        $statusText = ucfirst($this->status);

        return [
            'appointment_id' => $this->appointment->id,
            'booking_ref' => $this->appointment->booking_ref,
            'status' => $this->status,
            'status_text' => $statusText,
            'message' => $this->message ?? "Your appointment has been {$statusText}.",
            'salon_name' => $this->appointment->salon->name ?? 'Salon',
            'service_name' => $this->appointment->service->name ?? 'Service',
            'appointment_date' => $this->appointment->appointment_date,
            'start_time' => $this->appointment->start_time,
        ];
    }
}