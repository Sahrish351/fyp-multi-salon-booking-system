<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class WaitlistSlotAvailable extends Notification
{
    public function __construct(public $waitlist) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title'   => 'Slot Available! 🎉',
            'message' => 'A slot opened at ' . $this->waitlist->salon->name .
                         ' for ' . $this->waitlist->service->name .
                         '. You have 10 minutes to confirm!',
            'url'     => route('client.waitlist.index'),
        ];
    }
}