<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class AppointmentStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function build()
    {
        $statusText = ucfirst(str_replace('_', ' ', $this->appointment->status ?? 'Updated'));
        $date = $this->appointment->appointment_date ? Carbon::parse($this->appointment->appointment_date)->format('M d, Y') : 'N/A';
        $time = $this->appointment->start_time ? Carbon::parse($this->appointment->start_time)->format('h:i A') : 'N/A';
        
        $serviceName = $this->appointment->service->name ?? 'Service';
        $salonName = $this->appointment->salon->name ?? 'Beauty Blush Salon';
        $bookingRef = $this->appointment->booking_ref ?? 'N/A';

        return $this->subject("Appointment {$statusText}: {$bookingRef}")
                    ->html("
                        <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #f0e8ed; border-radius: 10px; max-width: 500px;'>
                            <h2 style='color: #E85588;'>Appointment {$statusText}</h2>
                            <p style='font-size: 15px; color: #2d1f2c;'>Your appointment status has been updated to <strong>{$statusText}</strong>.</p>
                            <hr style='border: none; border-top: 1px solid #f0e8ed; margin: 15px 0;'>
                            <p style='font-size: 14px; color: #2d1f2c; line-height: 1.6;'>
                                <strong>Booking Ref:</strong> {$bookingRef}<br>
                                <strong>Salon:</strong> {$salonName}<br>
                                <strong>Service:</strong> {$serviceName}<br>
                                <strong>Date:</strong> {$date}<br>
                                <strong>Time:</strong> {$time}
                            </p>
                            <hr style='border: none; border-top: 1px solid #f0e8ed; margin: 20px 0;'>
                            <p style='font-size: 12px; color: #8a7a88;'>Beauty Blush Salons - Notification System</p>
                        </div>
                    ");
    }
}