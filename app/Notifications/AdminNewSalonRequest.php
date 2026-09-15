<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AdminNewSalonRequest extends Notification
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
            ->subject('New Salon Registration Request')
            ->greeting('Hi Admin,')
            ->line('A new salon has registered and is waiting for approval.')
            ->line('Salon: ' . $this->salon->name)
            ->line('Owner: ' . $this->salon->owner->name)
            ->line('City: ' . $this->salon->city)
            ->action('Review Request', route('admin.salon-requests.show', $this->salon->id))
            ->salutation('Regards, Beauty Blush Salons System');
    }
}