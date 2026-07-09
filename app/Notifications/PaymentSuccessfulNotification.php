<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentSuccessfulNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $appointment;
    public $payment;

    public function __construct($appointment, $payment)
    {
        $this->appointment = $appointment;
        $this->payment = $payment;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $salonName = $this->appointment->salon->name ?? 'Salon';
        $serviceName = $this->appointment->service->name ?? 'Service';
        $amount = $this->payment->amount ?? 0;
        $transactionId = $this->payment->transaction_id ?? 'N/A';

        return (new MailMessage)
            ->subject('💳 Payment Successful - ' . $salonName)
            ->greeting('Hello ' . ($notifiable->name ?? 'Client') . '!')
            ->line("Your payment has been successfully processed.")
            ->line("**Booking Ref:** {$this->appointment->booking_ref}")
            ->line("**Salon:** {$salonName}")
            ->line("**Service:** {$serviceName}")
            ->line("**Amount Paid:** $" . number_format($amount, 2))
            ->line("**Transaction ID:** {$transactionId}")
            ->line("Thank you for your payment!")
            ->action('View Appointment', url('/client/appointments/' . $this->appointment->id));
    }

    public function toArray($notifiable)
    {
        $salonName = $this->appointment->salon->name ?? 'Salon';
        $serviceName = $this->appointment->service->name ?? 'Service';
        $amount = $this->payment->amount ?? 0;

        return [
            'appointment_id' => $this->appointment->id,
            'booking_ref' => $this->appointment->booking_ref,
            'title' => '💳 Payment Successful',
            'message' => "Your payment of $" . number_format($amount, 2) . " for {$serviceName} at {$salonName} was successful.",
            'amount' => $amount,
            'salon_name' => $salonName,
            'service_name' => $serviceName,
            'transaction_id' => $this->payment->transaction_id ?? null,
            'icon' => 'fa-check-circle',
            'color' => '#22c55e',
            'action_url' => url('/client/appointments/' . $this->appointment->id),
        ];
    }
}
