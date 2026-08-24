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
    public $actionUrl;
    public $actionLabel;
 
    /**
     * @param string      $subjectTitle Title/subject shown in email (can include an emoji icon at the start)
     * @param string      $messageBody  HTML body content (existing calls already pass formatted HTML — no change needed there)
     * @param string|null $actionUrl    Optional dashboard link — pass this from any controller to show a button
     * @param string      $actionLabel  Button text, only shown if $actionUrl is provided
     */
    public function __construct($subjectTitle = 'Salon Alert', $messageBody = '', $actionUrl = null, $actionLabel = 'View in Dashboard')
    {
        $this->subjectTitle = $subjectTitle;
        $this->messageBody  = $messageBody;
        $this->actionUrl    = $actionUrl;
        $this->actionLabel  = $actionLabel;
    }
 
    public function build()
    {
        // Agar title ke shuru mein emoji hai to usay alag nikaal lo taake header mein bada icon dikha sakein
        $icon  = '🔔';
        $title = $this->subjectTitle;
 
        if (preg_match('/^(\p{So}|\p{Sk}|\x{200D}|\x{FE0F})+\s*/u', $this->subjectTitle, $matches)) {
            $icon  = trim($matches[0]);
            $title = trim(mb_substr($this->subjectTitle, mb_strlen($matches[0])));
        }
 
        $actionButton = '';
        if (!empty($this->actionUrl)) {
            $actionButton = "
                <tr>
                    <td align='center' style='padding: 8px 0 28px 0;'>
                        <a href='{$this->actionUrl}' target='_blank' style='
                            display: inline-block;
                            background: #E85588;
                            color: #ffffff;
                            text-decoration: none;
                            font-family: Arial, Helvetica, sans-serif;
                            font-size: 14px;
                            font-weight: bold;
                            padding: 13px 32px;
                            border-radius: 8px;
                            letter-spacing: 0.3px;
                        '>{$this->actionLabel} &rarr;</a>
                    </td>
                </tr>
            ";
        }
 
        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>{$title}</title>
        </head>
        <body style='margin:0; padding:0; background-color:#f4eef1; font-family: Arial, Helvetica, sans-serif;'>
            <table role='presentation' width='100%' cellpadding='0' cellspacing='0' style='background-color:#f4eef1; padding: 32px 16px;'>
                <tr>
                    <td align='center'>
                        <table role='presentation' width='100%' cellpadding='0' cellspacing='0' style='max-width: 560px; background:#ffffff; border-radius: 14px; overflow: hidden; box-shadow: 0 2px 10px rgba(45,31,44,0.08);'>
 
                            <!-- Header -->
                            <tr>
                                <td style='background: linear-gradient(135deg, #E85588 0%, #c73e70 100%); padding: 30px 32px; text-align:center;'>
                                    <div style='font-size: 15px; letter-spacing: 1.5px; color: #ffe3ee; text-transform: uppercase; font-weight: 600; margin-bottom: 6px;'>
                                        Beauty Blush Salons
                                    </div>
                                    <div style='font-size: 13px; color: #ffd3e6;'>
                                        Salon Owner Notification
                                    </div>
                                </td>
                            </tr>
 
                            <!-- Icon + Title -->
                            <tr>
                                <td style='padding: 32px 32px 0 32px; text-align:center;'>
                                    <div style='
                                        width: 64px; height: 64px; line-height: 64px;
                                        background: #fdeef4; border-radius: 50%;
                                        font-size: 30px; margin: 0 auto 16px auto;
                                    '>{$icon}</div>
                                    <h1 style='margin:0; font-size: 20px; color:#2d1f2c; font-weight: 700;'>
                                        {$title}
                                    </h1>
                                </td>
                            </tr>
 
                            <!-- Body -->
                            <tr>
                                <td style='padding: 20px 32px 8px 32px;'>
                                    <div style='
                                        background: #fbf8f9;
                                        border: 1px solid #f0e2ea;
                                        border-radius: 10px;
                                        padding: 20px 22px;
                                        font-size: 15px;
                                        line-height: 1.7;
                                        color: #3d2e3c;
                                    '>
                                        {$this->messageBody}
                                    </div>
                                </td>
                            </tr>
 
                            {$actionButton}
 
                            <!-- Divider -->
                            <tr>
                                <td style='padding: 0 32px;'>
                                    <hr style='border:none; border-top:1px solid #f0e2ea; margin: 8px 0 0 0;'>
                                </td>
                            </tr>
 
                            <!-- Footer -->
                            <tr>
                                <td style='padding: 20px 32px 28px 32px; text-align:center;'>
                                    <p style='margin:0 0 4px 0; font-size: 13px; color:#8a7a88;'>
                                        This is an automated notification from your Beauty Blush Salons dashboard.
                                    </p>
                                    <p style='margin:0; font-size: 12px; color:#b7a7b4;'>
                                        &copy; " . date('Y') . " Beauty Blush Salons &middot; All rights reserved
                                    </p>
                                </td>
                            </tr>
 
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>
        ";
 
        return $this->subject($title)->html($html);
    }
}
 