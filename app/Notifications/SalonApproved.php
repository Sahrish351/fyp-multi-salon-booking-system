<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SalonApproved extends Notification
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
            ->subject('Your Salon Has Been Approved! 🎉')
            ->greeting('Congratulations, ' . $notifiable->name . '!')
            ->line('Your salon "' . $this->salon->name . '" has been approved.')
            ->line('You can now log in and start managing your salon.')
            ->action('Go to Dashboard', route('owner.dashboard'))
            ->line('Welcome to Beauty Blush Salons!')
            ->salutation('With love, Beauty Blush Salons 💖');
    }
}