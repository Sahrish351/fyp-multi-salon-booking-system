<?php
 
namespace App\Mail;
 
use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
 
class PaymentRejectedMail extends Mailable
{
    use Queueable, SerializesModels;
 
    public $appointment;
 
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }
 
    public function build()
    {
        $bookingRef = $this->appointment->booking_ref ?? 'N/A';
        $amount = $this->appointment->payment->amount ?? 'N/A';
        $serviceName = $this->appointment->service->name ?? 'Service';
        $clientName = $this->appointment->client->name ?? 'Customer';
 
        $hours = \App\Models\Payment::RESUBMIT_HOURS;
 
        
        $reason = $this->appointment->payment->rejection_reason ?? null;
 
        $reasonBlock = '';
        if (!empty($reason)) {
            $safeReason = e($reason);
            $reasonBlock = "
                            <div style='background: #fff5f5; border: 1px solid #feb2b2; border-radius: 8px; padding: 12px 14px; margin: 15px 0;'>
                                <p style='margin: 0 0 4px; font-size: 13px; font-weight: bold; color: #c53030;'>Reason for rejection:</p>
                                <p style='margin: 0; font-size: 14px; color: #2d3748; line-height: 1.5;'>{$safeReason}</p>
                            </div>";
        }
 
        return $this->subject("Payment Rejected - Booking Ref: {$bookingRef}")
                    ->html("
                        <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px; max-width: 500px;'>
                            <h2 style='color: #c53030;'>Payment Rejected</h2>
                            <p style='font-size: 15px; color: #2d3748;'>Dear <strong>{$clientName}</strong>,</p>
                            <p style='font-size: 14px; color: #4a5568;'>Unfortunately, your payment could not be verified and has been rejected. Please submit your payment again to confirm your appointment.</p>
                            {$reasonBlock}
                            <hr style='border: none; border-top: 1px solid #e2e8f0; margin: 15px 0;'>
                            <p style='font-size: 14px; color: #2d3748; line-height: 1.6;'>
                                <strong>Booking Ref:</strong> {$bookingRef}<br>
                                <strong>Service:</strong> {$serviceName}<br>
                                <strong>Amount:</strong> PKR {$amount}
                            </p>
                            <hr style='border: none; border-top: 1px solid #e2e8f0; margin: 20px 0;'>
                            <p style='font-size: 12px; color: #718096;'>Please resubmit your payment within {$hours} hours, otherwise your booking will be cancelled.</p>
                        </div>
                    ");
    }
}