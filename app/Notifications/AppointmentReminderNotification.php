<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $appointment;

    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $salonName = $this->appointment->salon->name ?? 'Salon';
        $serviceName = $this->appointment->service->name ?? 'Service';
        $date = \Carbon\Carbon::parse($this->appointment->appointment_date)->format('l, F d, Y');
        $time = \Carbon\Carbon::parse($this->appointment->start_time)->format('g:i A');

        return (new MailMessage)
            ->subject('📅 Appointment Reminder - ' . $salonName)
            ->greeting('Hello ' . ($notifiable->name ?? 'Client') . '!')
            ->line("This is a reminder for your upcoming appointment.")
            ->line("**Booking Ref:** {$this->appointment->booking_ref}")
            ->line("**Salon:** {$salonName}")
            ->line("**Service:** {$serviceName}")
            ->line("**Date:** {$date}")
            ->line("**Time:** {$time}")
            ->line("Please arrive 10 minutes before your appointment.")
            ->action('View Appointment', url('/client/appointments/' . $this->appointment->id));
    }

    public function toArray($notifiable)
    {
        $salonName = $this->appointment->salon->name ?? 'Salon';
        $serviceName = $this->appointment->service->name ?? 'Service';
        $date = \Carbon\Carbon::parse($this->appointment->appointment_date)->format('d M Y');
        $time = \Carbon\Carbon::parse($this->appointment->start_time)->format('h:i A');

        return [
            'appointment_id' => $this->appointment->id,
            'booking_ref' => $this->appointment->booking_ref,
            'title' => '📅 Appointment Reminder',
            'message' => "Reminder: Your appointment at {$salonName} for {$serviceName} is on {$date} at {$time}.",
            'salon_name' => $salonName,
            'service_name' => $serviceName,
            'appointment_date' => $this->appointment->appointment_date,
            'start_time' => $this->appointment->start_time,
            'icon' => 'fa-calendar-check',
            'color' => '#3b82f6',
            'action_url' => url('/client/appointments/' . $this->appointment->id),
        ];
    }
}