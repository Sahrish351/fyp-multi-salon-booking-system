<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        $this->title = $title;
        $this->message = $message;
        $this->actionUrl = $actionUrl;
        $this->icon = $icon;
        $this->color = $color;
        $this->reply = $reply;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $data = [
            'title' => $this->title,
            'message' => $this->message,
            'action_url' => $this->actionUrl,
            'icon' => $this->icon,
            'color' => $this->color,
        ];

        if ($this->reply) {
            $data['reply'] = $this->reply;
        }

        return $data;
    }
}