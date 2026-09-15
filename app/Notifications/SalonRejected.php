<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SalonRejected extends Notification
{
    use Queueable;

    public function __construct(public $salon, public $reason) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->theme('beautyblush')
            ->subject('Update on Your Salon Registration')
            ->greeting('Hi ' . $notifiable->name . ',')
            ->line('Unfortunately, your salon "' . $this->salon->name . '" was not approved.')
            ->line('Reason: ' . $this->reason)
            ->line('If you believe this is a mistake, contact our support team.')
            ->action('Contact Support', route('support'))
            ->salutation('Regards, Beauty Blush Salons Team');
    }
}