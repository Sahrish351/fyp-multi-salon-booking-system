<?php
 
namespace App\Mail;
 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
 
class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;
 
    public string $resetUrl;
    public string $userName;
 
    /**
     * @param string $resetUrl  Full reset link (with token)
     * @param string $userName  Name shown inside the email greeting
     */
    public function __construct(string $resetUrl, string $userName)
    {
        $this->resetUrl = $resetUrl;
        $this->userName = $userName;
    }
 
    public function build()
    {
        return $this->subject('Reset Your Password — Beauty Blush Salons')
                    ->view('emails.reset-password');
    }
}
 