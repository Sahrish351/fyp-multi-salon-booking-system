<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->subject('Your Verification Code - Beauty Blush')
                    ->html("
                        <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px; max-width: 500px;'>
                            <h2 style='color: #E91E8C;'>Email Verification</h2>
                            <p style='font-size: 15px; color: #2d3748;'>Use the code below to verify your email address:</p>
                            <div style='font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #1a1a2e; text-align: center; padding: 20px 0;'>
                                {$this->otp}
                            </div>
                            <p style='font-size: 13px; color: #718096;'>This code will expire in 10 minutes. If you didn't request this, please ignore this email.</p>
                        </div>
                    ");
    }
}