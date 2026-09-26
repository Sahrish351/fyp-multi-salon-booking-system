<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountBlockedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $blockedUser;

    public function __construct(User $blockedUser)
    {
        $this->blockedUser = $blockedUser;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Account Has Been Blocked',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.account-blocked',
        );
    }
}