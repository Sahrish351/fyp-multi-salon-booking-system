<?php

namespace App\Notifications;

use App\Models\Appointment;
use App\Models\Complaint;
use App\Models\Review;
use App\Models\Waitlist;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ClientNotifications extends Notification
{
    use Queueable;

    // ============================================================ //
    // NOTIFICATION TYPES (Constants)
    // ============================================================ //
    const TYPE_APPOINTMENT_CONFIRMED = 'appointment_confirmed';
    const TYPE_APPOINTMENT_CANCELLED = 'appointment_cancelled';
    const TYPE_APPOINTMENT_RESCHEDULED = 'appointment_rescheduled';
    const TYPE_APPOINTMENT_REMINDER = 'appointment_reminder';
    const TYPE_PAYMENT_SUCCESS = 'payment_success';
    const TYPE_PAYMENT_REJECTED = 'payment_rejected';
    const TYPE_REVIEW_REPLY = 'review_reply';
    const TYPE_COMPLAINT_STATUS = 'complaint_status';
    const TYPE_WAITLIST_JOINED = 'waitlist_joined';
    const TYPE_WAITLIST_SLOT_AVAILABLE = 'waitlist_slot_available';
    const TYPE_WAITLIST_CANCELLED = 'waitlist_cancelled';

    // ============================================================ //
    // PROPERTIES (Same pattern as AppointmentUpdateNotification)
    // ============================================================ //
    protected string $type;
    protected $data;
    protected array $extra;

    /**
     * @param string $type  Notification type (use constants above)
     * @param mixed $data   Main data object (Appointment, Complaint, etc.)
     * @param array $extra  Extra data (old_date, amount, reason, position, etc.)
     */
    public function __construct(string $type, $data, array $extra = [])
    {
        $this->type = $type;
        $this->data = $data;
        $this->extra = $extra;
    }

    // ============================================================ //
    // DELIVERY CHANNELS
    // ============================================================ //
    public function via($notifiable): array
    {
        return ['database'];
    }

    // ============================================================ //
    // DATABASE METHODS (Same as AppointmentUpdateNotification)
    // ============================================================ //
    public function toDatabase($notifiable): array
    {
        return $this->buildPayload();
    }

    public function toArray($notifiable): array
    {
        return $this->buildPayload();
    }

    // ============================================================ //
    // BUILD PAYLOAD - All Notification Types
    // ============================================================ //
    protected function buildPayload(): array
    {
        switch ($this->type) {
            // ============================================================ //
            // 1. APPOINTMENT CONFIRMED
            // ============================================================ //
            case self::TYPE_APPOINTMENT_CONFIRMED:
                $appointment = $this->data;
                $salonName = $appointment->salon->name ?? 'the salon';
                $serviceName = $appointment->service->name ?? 'service';
                $date = optional($appointment->appointment_date)->format('d M Y');
                $time = $appointment->start_time 
                    ? \Carbon\Carbon::parse($appointment->start_time)->format('h:i A')
                    : null;

                return [
                    'appointment_id' => $appointment->id,
                    'booking_ref' => $appointment->booking_ref ?? 'N/A',
                    'title' => '✅ Appointment Confirmed',
                    'message' => "Your appointment at {$salonName} for {$serviceName} on {$date} at {$time} has been confirmed.",
                    'salon_name' => $salonName,
                    'service_name' => $serviceName,
                    'appointment_date' => $appointment->appointment_date,
                    'start_time' => $appointment->start_time,
                    'icon' => 'fa-check-circle',
                    'color' => '#22c55e',
                    'action_url' => url('/client/appointments/' . $appointment->id),
                ];

            // ============================================================ //
            // 2. APPOINTMENT CANCELLED (Same as AppointmentUpdateNotification)
            // ============================================================ //
            case self::TYPE_APPOINTMENT_CANCELLED:
                $appointment = $this->data;
                $salonName = $appointment->salon->name ?? 'the salon';
                $serviceName = $appointment->service->name ?? 'service';

                return [
                    'appointment_id' => $appointment->id,
                    'booking_ref' => $appointment->booking_ref ?? 'N/A',
                    'title' => '❌ Appointment Cancelled',
                    'message' => "Your appointment at {$salonName} for {$serviceName} has been cancelled.",
                    'salon_name' => $salonName,
                    'service_name' => $serviceName,
                    'old_date' => $this->extra['old_date'] ?? null,
                    'old_time' => $this->extra['old_time'] ?? null,
                    'icon' => 'fa-times-circle',
                    'color' => '#ef4444',
                    'action_url' => url('/client/appointments/' . $appointment->id),
                ];

            // ============================================================ //
            // 3. APPOINTMENT RESCHEDULED (Same as AppointmentUpdateNotification)
            // ============================================================ //
            case self::TYPE_APPOINTMENT_RESCHEDULED:
                $appointment = $this->data;
                $salonName = $appointment->salon->name ?? 'the salon';
                $serviceName = $appointment->service->name ?? 'service';
                $oldDate = $this->extra['old_date'] ?? 'N/A';
                $oldTime = $this->extra['old_time'] ?? 'N/A';
                $newDate = optional($appointment->appointment_date)->format('d M Y');
                $newTime = $appointment->start_time 
                    ? \Carbon\Carbon::parse($appointment->start_time)->format('h:i A')
                    : null;

                return [
                    'appointment_id' => $appointment->id,
                    'booking_ref' => $appointment->booking_ref ?? 'N/A',
                    'title' => '📅 Appointment Rescheduled',
                    'message' => "Your appointment at {$salonName} for {$serviceName} has been rescheduled from {$oldDate} {$oldTime} to {$newDate} {$newTime}.",
                    'salon_name' => $salonName,
                    'service_name' => $serviceName,
                    'old_date' => $oldDate,
                    'old_time' => $oldTime,
                    'new_date' => $newDate,
                    'new_time' => $newTime,
                    'icon' => 'fa-calendar-alt',
                    'color' => '#3b82f6',
                    'action_url' => url('/client/appointments/' . $appointment->id),
                ];

            // ============================================================ //
            // 4. APPOINTMENT REMINDER
            // ============================================================ //
            case self::TYPE_APPOINTMENT_REMINDER:
                $appointment = $this->data;
                $salonName = $appointment->salon->name ?? 'the salon';
                $serviceName = $appointment->service->name ?? 'service';
                $date = optional($appointment->appointment_date)->format('d M Y');
                $time = $appointment->start_time 
                    ? \Carbon\Carbon::parse($appointment->start_time)->format('h:i A')
                    : null;

                return [
                    'appointment_id' => $appointment->id,
                    'booking_ref' => $appointment->booking_ref ?? 'N/A',
                    'title' => '📅 Appointment Reminder',
                    'message' => "Reminder: Your appointment at {$salonName} for {$serviceName} is on {$date} at {$time}.",
                    'salon_name' => $salonName,
                    'service_name' => $serviceName,
                    'appointment_date' => $appointment->appointment_date,
                    'start_time' => $appointment->start_time,
                    'icon' => 'fa-calendar-check',
                    'color' => '#3b82f6',
                    'action_url' => url('/client/appointments/' . $appointment->id),
                ];

            // ============================================================ //
            // 5. PAYMENT SUCCESS
            // ============================================================ //
            case self::TYPE_PAYMENT_SUCCESS:
                $appointment = $this->data;
                $payment = $this->extra['payment'] ?? null;
                $salonName = $appointment->salon->name ?? 'the salon';
                $serviceName = $appointment->service->name ?? 'service';
                $amount = $payment->amount ?? $this->extra['amount'] ?? 0;
                $transactionId = $payment->transaction_id ?? $this->extra['transaction_id'] ?? 'N/A';

                return [
                    'appointment_id' => $appointment->id,
                    'booking_ref' => $appointment->booking_ref ?? 'N/A',
                    'title' => '💳 Payment Successful',
                    'message' => "Your payment of $" . number_format($amount, 2) . " for {$serviceName} at {$salonName} was successful.",
                    'amount' => $amount,
                    'salon_name' => $salonName,
                    'service_name' => $serviceName,
                    'transaction_id' => $transactionId,
                    'icon' => 'fa-check-circle',
                    'color' => '#22c55e',
                    'action_url' => url('/client/appointments/' . $appointment->id),
                ];

            // ============================================================ //
            // 6. PAYMENT REJECTED
            // ============================================================ //
            case self::TYPE_PAYMENT_REJECTED:
                $appointment = $this->data;
                $payment = $this->extra['payment'] ?? null;
                $salonName = $appointment->salon->name ?? 'the salon';
                $serviceName = $appointment->service->name ?? 'service';
                $amount = $payment->amount ?? $this->extra['amount'] ?? 0;
                $reason = $this->extra['reason'] ?? 'Payment verification failed.';

                return [
                    'appointment_id' => $appointment->id,
                    'booking_ref' => $appointment->booking_ref ?? 'N/A',
                    'title' => '⚠ Payment Rejected',
                    'message' => "Your payment of $" . number_format($amount, 2) . " for {$serviceName} at {$salonName} was rejected. Reason: {$reason}",
                    'amount' => $amount,
                    'salon_name' => $salonName,
                    'service_name' => $serviceName,
                    'reason' => $reason,
                    'icon' => 'fa-exclamation-triangle',
                    'color' => '#ef4444',
                    'action_url' => url('/client/appointments/' . $appointment->id . '/payment'),
                ];

            // ============================================================ //
            // 7. REVIEW REPLY
            // ============================================================ //
            case self::TYPE_REVIEW_REPLY:
                $review = $this->data;
                $salon = $this->extra['salon'] ?? null;
                $reply = $this->extra['reply'] ?? null;
                $salonName = $salon->name ?? 'the salon';
                $replyText = $reply->reply_text ?? $reply->message ?? 'Thank you for your feedback!';

                return [
                    'review_id' => $review->id,
                    'salon_id' => $salon->id ?? null,
                    'title' => '⭐ Salon Owner Replied to Your Review',
                    'message' => "The owner of {$salonName} replied to your review: \"{$replyText}\"",
                    'salon_name' => $salonName,
                    'reply_text' => $replyText,
                    'icon' => 'fa-star',
                    'color' => '#f59e0b',
                    'action_url' => url('/client/reviews/' . $review->id),
                ];

            // ============================================================ //
            // 8. COMPLAINT STATUS
            // ============================================================ //
            case self::TYPE_COMPLAINT_STATUS:
                $complaint = $this->data;
                $oldStatus = $this->extra['old_status'] ?? 'pending';
                $newStatus = $this->extra['new_status'] ?? 'in_review';
                $statusLabels = ['pending' => 'Pending', 'in_review' => 'Under Review', 'resolved' => 'Resolved', 'closed' => 'Closed'];
                $oldLabel = $statusLabels[$oldStatus] ?? ucfirst($oldStatus);
                $newLabel = $statusLabels[$newStatus] ?? ucfirst($newStatus);
                $salonName = $complaint->salon->name ?? 'the salon';

                $icon = 'fa-clipboard-list';
                $color = '#8b5cf6';

                if ($newStatus === 'resolved') {
                    $icon = 'fa-check-circle';
                    $color = '#22c55e';
                } elseif ($newStatus === 'closed') {
                    $icon = 'fa-times-circle';
                    $color = '#6b7280';
                } elseif ($newStatus === 'in_review') {
                    $icon = 'fa-spinner';
                    $color = '#f59e0b';
                }

                return [
                    'complaint_id' => $complaint->id,
                    'salon_name' => $salonName,
                    'title' => '📢 Complaint Status Updated',
                    'message' => "Your complaint \"{$complaint->subject}\" status changed from {$oldLabel} to {$newLabel}.",
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'old_status_label' => $oldLabel,
                    'new_status_label' => $newLabel,
                    'subject' => $complaint->subject,
                    'icon' => $icon,
                    'color' => $color,
                    'action_url' => url('/client/complaints/' . $complaint->id),
                ];

            // ============================================================ //
            // 9. WAITLIST JOINED
            // ============================================================ //
            case self::TYPE_WAITLIST_JOINED:
                $waitlist = $this->data;
                $salonName = $waitlist->salon->name ?? 'the salon';
                $serviceName = $waitlist->service->name ?? 'service';
                $position = $this->extra['position'] ?? $waitlist->position ?? 'N/A';

                return [
                    'waitlist_id' => $waitlist->id,
                    'title' => '⏳ Waitlist Joined Successfully',
                    'message' => "You joined the waitlist for {$serviceName} at {$salonName}. Your position is #{$position}.",
                    'salon_name' => $salonName,
                    'service_name' => $serviceName,
                    'position' => $position,
                    'icon' => 'fa-clock',
                    'color' => '#8b5cf6',
                    'action_url' => url('/client/waitlist'),
                ];

            // ============================================================ //
            // 10. WAITLIST SLOT AVAILABLE
            // ============================================================ //
            case self::TYPE_WAITLIST_SLOT_AVAILABLE:
                $waitlist = $this->data;
                $salonName = $waitlist->salon->name ?? 'the salon';
                $serviceName = $waitlist->service->name ?? 'service';

                return [
                    'waitlist_id' => $waitlist->id,
                    'title' => '🎉 Slot Available!',
                    'message' => "A slot opened at {$salonName} for {$serviceName}. You have 10 minutes to confirm!",
                    'salon_name' => $salonName,
                    'service_name' => $serviceName,
                    'icon' => 'fa-bell',
                    'color' => '#22c55e',
                    'action_url' => url('/client/waitlist/confirm/' . $waitlist->id),
                ];

            // ============================================================ //
            // 11. WAITLIST CANCELLED
            // ============================================================ //
            case self::TYPE_WAITLIST_CANCELLED:
                $waitlist = $this->data;
                $salonName = $waitlist->salon->name ?? 'the salon';
                $serviceName = $waitlist->service->name ?? 'service';
                $reason = $this->extra['reason'] ?? 'Your waitlist request was cancelled.';

                return [
                    'waitlist_id' => $waitlist->id,
                    'title' => '❌ Waitlist Cancelled/Expired',
                    'message' => "Your waitlist for {$serviceName} at {$salonName} has been cancelled/expired. Reason: {$reason}",
                    'salon_name' => $salonName,
                    'service_name' => $serviceName,
                    'reason' => $reason,
                    'icon' => 'fa-times-circle',
                    'color' => '#ef4444',
                    'action_url' => url('/client/waitlist'),
                ];

            // ============================================================ //
            // DEFAULT
            // ============================================================ //
            default:
                return [
                    'title' => 'Notification',
                    'message' => 'You have a new notification.',
                    'icon' => 'fa-bell',
                    'color' => '#E91E8C',
                ];
        }
    }
}