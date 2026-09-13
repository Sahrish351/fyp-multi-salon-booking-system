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

        return $this->subject("Payment Rejected - Booking Ref: {$bookingRef}")
                    ->html("
                        <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px; max-width: 500px;'>
                            <h2 style='color: #c53030;'>Payment Rejected</h2>
                            <p style='font-size: 15px; color: #2d3748;'>Dear <strong>{$clientName}</strong>,</p>
                            <p style='font-size: 14px; color: #4a5568;'>Unfortunately, your payment could not be verified and has been rejected. Please submit your payment again to confirm your appointment.</p>
                            <hr style='border: none; border-top: 1px solid #e2e8f0; margin: 15px 0;'>
                            <p style='font-size: 14px; color: #2d3748; line-height: 1.6;'>
                                <strong>Booking Ref:</strong> {$bookingRef}<br>
                                <strong>Service:</strong> {$serviceName}<br>
                                <strong>Amount:</strong> PKR {$amount}
                            </p>
                            <hr style='border: none; border-top: 1px solid #e2e8f0; margin: 20px 0;'>
                            <p style='font-size: 12px; color: #718096;'>Please resubmit your payment at your earliest convenience.</p>
                        </div>
                    ");
    }
}