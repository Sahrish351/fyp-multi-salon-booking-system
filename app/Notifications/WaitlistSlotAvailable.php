<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WaitlistSlotAvailable extends Notification implements ShouldQueue
{
    use Queueable;

    protected $waitlist;

    public function __construct($waitlist)
    {
        $this->waitlist = $waitlist;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $salonName   = $this->waitlist->salon->name ?? 'Our Salon';
        $serviceName = $this->waitlist->service->name ?? 'your requested service';
        $acceptUrl   = url('/waitlist/accept/' . $this->waitlist->id);
        $userName    = $notifiable->name ?? 'Valued Client';

        return (new MailMessage)
            ->subject('Slot Available at ' . $salonName)
            ->greeting('Hello ' . $userName . '!')
            ->line("A slot has opened up for your service ({$serviceName}) at {$salonName}.")
            ->line('You have **20 minutes** to claim this slot before it is offered to the next person.')
            ->action('Accept Slot', $acceptUrl)
            ->line('Thank you for choosing us!');
    }

    public function toDatabase($notifiable)
    {
        return $this->buildPayload();
    }

    public function toArray($notifiable)
    {
        return $this->buildPayload();
    }

    protected function buildPayload()
    {
        $salonName   = $this->waitlist->salon->name ?? 'the salon';
        $serviceName = $this->waitlist->service->name ?? 'service';

        return [
            'waitlist_id'  => $this->waitlist->id,
            'title'        => '🎉 Slot Available!',
            'message'      => "A slot opened at {$salonName} for {$serviceName}. You have 20 minutes to confirm!",
            'salon_name'   => $salonName,
            'service_name' => $serviceName,
            'icon'         => 'fa-bell',
            'color'        => '#22c55e',
            'action_url'   => url('/waitlist/accept/' . $this->waitlist->id),
        ];
    }
}