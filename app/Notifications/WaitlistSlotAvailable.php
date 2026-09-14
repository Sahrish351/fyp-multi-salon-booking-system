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
        $userName    = $notifiable->name ?? 'Valued Client';
        $acceptUrl   = route('client.waitlist.index');
        $preferredDate = $this->waitlist->preferred_date
            ? \Carbon\Carbon::parse($this->waitlist->preferred_date)->format('M d, Y')
            : null;
        $expiresAt = $this->waitlist->expires_at
            ? \Carbon\Carbon::parse($this->waitlist->expires_at)->format('h:i A')
            : null;
 
        return (new MailMessage)
            ->subject('Your Slot is Ready — ' . $salonName)
            ->view('emails.waitlist-slot-available', [
                'userName'      => $userName,
                'salonName'     => $salonName,
                'serviceName'   => $serviceName,
                'preferredDate' => $preferredDate,
                'acceptUrl'     => $acceptUrl,
                'expiresAt'     => $expiresAt,
            ]);
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
            'action_url'   => route('client.waitlist.index'),
        ];
    }
}
 