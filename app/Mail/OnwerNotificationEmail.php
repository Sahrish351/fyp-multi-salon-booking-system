<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OwnerNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectTitle;
    public $messageBody;

    public function __construct($subjectTitle = 'Salon Alert', $messageBody = '')
    {
        $this->subjectTitle = $subjectTitle;
        $this->messageBody  = $messageBody;
    }

    public function build()
    {
        return $this->subject($this->subjectTitle)
                    ->html("
                        <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #f0e8ed; border-radius: 10px;'>
                            <h2 style='color: #E85588;'>{$this->subjectTitle}</h2>
                            <p style='font-size: 15px; color: #2d1f2c;'>{$this->messageBody}</p>
                            <hr style='border: none; border-top: 1px solid #f0e8ed; margin: 20px 0;'>
                            <p style='font-size: 12px; color: #8a7a88;'>Beauty Blush Salons - Notification System</p>
                        </div>
                    ");
    }
}