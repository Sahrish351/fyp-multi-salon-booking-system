<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CustomNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $title;
    protected $message;
    protected $actionUrl;
    protected $icon;
    protected $color;
    protected $reply;

    public function __construct($title, $message, $actionUrl = null, $icon = 'bell', $color = '#E91E8C', $reply = null)
    {
        $this->title     = $title;
        $this->message   = $message;
        $this->actionUrl = $actionUrl;
        $this->icon      = $icon;
        $this->color     = $color;
        $this->reply     = $reply;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject($this->title)
            ->greeting('Hello ' . ($notifiable->name ?? 'User') . '!')
            ->line($this->message);

        if ($this->reply) {
            $mail->line("**Response:** " . $this->reply);
        }

        if ($this->actionUrl) {
            $mail->action('View Details', $this->actionUrl);
        }

        return $mail->line('Thank you!');
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
        $data = [
            'title'      => $this->title,
            'message'    => $this->message,
            'action_url' => $this->actionUrl,
            'icon'       => $this->icon,
            'color'      => $this->color,
        ];

        if ($this->reply) {
            $data['reply'] = $this->reply;
        }

        return $data;
    }
}