<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SalonSuspendedNotification extends Notification
{
    use Queueable;

    public function __construct(public $salon) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->theme('beautyblush')
            ->subject('Your Salon Has Been Suspended')
            ->greeting('Hi ' . $notifiable->name . ',')
            ->line('Your salon "' . $this->salon->name . '" has been temporarily suspended.')
            ->line('Please contact support for more details.')
            ->action('Contact Support', route('support'))
            ->salutation('Regards, Beauty Blush Salons Team');
    }
}