{{-- ============================================================ --}}
{{-- FILE: resources/views/client/payments/receipt.blade.php --}}
{{-- ============================================================ --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Receipt #{{ $payment->id }}</title>
    <style>
        body { font-family: sans-serif; color: #333; padding: 30px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #E91E8C; font-size: 24px; margin: 0; }
        .header p { color: #aaa; font-size: 12px; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table td { padding: 10px 0; border-bottom: 1px dashed #eee; font-size: 13px; }
        table td.label { color: #999; width: 40%; }
        table td.value { color: #222; font-weight: bold; text-align: right; }
        .status { display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .status-paid { background: #dcfce7; color: #16a34a; }
        .status-pending { background: #fef9c3; color: #b45309; }
        .status-cancelled { background: #fee2e2; color: #dc2626; }
        .footer { text-align: center; margin-top: 40px; color: #aaa; font-size: 11px; }
    </style>
</head>
<body>
    @php
        $statusMap = [
            'approved' => ['label' => 'Paid',      'class' => 'status-paid'],
            'pending'  => ['label' => 'Pending',   'class' => 'status-pending'],
            'rejected' => ['label' => 'Cancelled', 'class' => 'status-cancelled'],
        ];
        $st = $statusMap[$payment->status] ?? ['label' => ucfirst($payment->status), 'class' => 'status-pending'];
    @endphp

    <div class="header">
        <h1>Glamora</h1>
        <p>Payment Receipt</p>
    </div>

    <table>
        <tr><td class="label">Payment ID</td><td class="value">#{{ $payment->id }}</td></tr>
        <tr><td class="label">Status</td><td class="value"><span class="status {{ $st['class'] }}">{{ $st['label'] }}</span></td></tr>
        <tr><td class="label">Salon</td><td class="value">{{ $payment->appointment->salon->name ?? '—' }}</td></tr>
        <tr><td class="label">Service</td><td class="value">{{ $payment->appointment->service->name ?? '—' }}</td></tr>
        <tr><td class="label">Amount Paid</td><td class="value">Rs. {{ number_format($payment->appointment->advance_amount) }}</td></tr>
        <tr><td class="label">Payment Method</td><td class="value">{{ ucfirst($payment->method ?? '—') }}</td></tr>
        <tr><td class="label">Payment Date</td><td class="value">{{ $payment->created_at->format('d M Y, h:i A') }}</td></tr>
    </table>

    <div class="footer">
        Thank you for choosing Glamora. This is a system-generated receipt.
    </div>
</body>
</html>