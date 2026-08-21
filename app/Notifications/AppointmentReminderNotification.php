<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $appointment;
    protected $type; 

    public function __construct($appointment, $type)
    {
        $this->appointment = $appointment;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $salonName = $this->appointment->salon->name ?? 'Our Salon';
        $serviceName = $this->appointment->service->name ?? 'Requested Service';
        $time = date('h:i A', strtotime($this->appointment->appointment_time));
        $date = date('M d, Y', strtotime($this->appointment->appointment_date));

        if ($this->type === '1_day') {
            $subject = "Reminder: Appointment Tomorrow at {$salonName}";
            $message = "This is a reminder for your upcoming appointment tomorrow ({$date}) at {$time} for {$serviceName}.";
        } else {
            $subject = "Reminder: Appointment in 2 Hours at {$salonName}";
            $message = "Your appointment is scheduled today at {$time} for {$serviceName}. Please arrive on time!";
        }

        return (new MailMessage)
            ->subject($subject)
            ->greeting("Hello " . $notifiable->name . ",")
            ->line($message)
            ->line('Thank you for choosing us!');
    }

    public function toArray($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'type'           => $this->type,
            'message'        => $this->type === '1_day' ? 'Reminder: Appointment Tomorrow' : 'Reminder: Appointment in 2 Hours',
        ];
    }
}