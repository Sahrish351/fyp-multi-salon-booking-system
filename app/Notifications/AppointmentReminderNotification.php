<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class AppointmentReminderNotification extends Notification
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
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $a       = $this->appointment;
        $salon   = $a->salon->name ?? 'Our Salon';
        $service = $a->service->name ?? 'your service';
        $date    = Carbon::parse($a->appointment_date)->format('M d, Y');
        $time    = Carbon::parse($a->start_time)->format('h:i A');

        if ($this->type === '1_day') {
            $subject = "Reminder: Your appointment is tomorrow at {$salon}";
            $line    = "This is a reminder that your appointment for {$service} is tomorrow, {$date} at {$time}.";
        } else {
            $subject = "Reminder: Your appointment is in 2 hours at {$salon}";
            $line    = "Your appointment for {$service} is today at {$time}, which is in about 2 hours. Please arrive on time!";
        }

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line($line)
            ->line("Booking Ref: {$a->booking_ref}")
            ->line('Thank you for choosing us!');
    }
}