<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WaitlistSlotAvailable extends Notification
{
    use Queueable;

    protected $waitlist;

    public function __construct($waitlist)
    {
        $this->waitlist = $waitlist;
    }

    public function via($notifiable)
    {
        return ['database']; 
    }

    public function toArray($notifiable)
    {
        return [
            'title'        => '🎉 Slot Available!',
            'message'      => 'A slot is now available for ' . $this->waitlist->salon->name . '. You have 20 minutes to accept it!',
            'waitlist_id'  => $this->waitlist->id,
            'expires_at'   => $this->waitlist->expires_at->toIso8601String(),
            'link'         => route('client.waitlist.accept', $this->waitlist->id),
        ];
    }
}