<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $client;
    public $salon;

    public function __construct($appointment, $client, $salon)
    {
        $this->appointment = $appointment;
        $this->client = $client;
        $this->salon = $salon;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⏰ Appointment Reminder - ' . $this->salon->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment-reminder',
        );
    }
}