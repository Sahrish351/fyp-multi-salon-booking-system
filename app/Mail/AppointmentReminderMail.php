<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AppointmentReminderMail extends Mailable
{
    use Queueable;

    public $appointment;
    public $client;
    public $salon;
    public $type; 

    public function __construct($appointment, $client, $salon, $type = '2_hours')
    {
        $this->appointment = $appointment;
        $this->client      = $client;
        $this->salon       = $salon;
        $this->type        = $type;
    }

    public function envelope(): Envelope
    {
        $salonName = $this->salon->name ?? 'Beauty Blush Salons';

        $subject = $this->type === '1_day'
            ? "Appointment Reminder: Tomorrow at {$salonName}"
            : "Appointment Reminder: In 2 Hours at {$salonName}";

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment-reminder',
        );
    }
}