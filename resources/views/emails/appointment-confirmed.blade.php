<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmed</title>
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; background-color: #f8f4f7; margin: 0; padding: 0;">

<table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8f4f7; padding: 40px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background: #ffffff; border-radius: 20px; box-shadow: 0 8px 30px rgba(0,0,0,0.08); padding: 40px; border: 1px solid #fce4ec;">

                <!-- Header -->
                <tr>
                    <td align="center" style="padding-bottom: 30px;">
                        <div style="width: 70px; height: 70px; border-radius: 50%; background: linear-gradient(135deg, #E91E8C, #c2185b); display: inline-flex; align-items: center; justify-content: center; font-size: 32px; color: #fff;">
                            ✅
                        </div>
                        <h1 style="font-family: 'Playfair Display', serif; color: #2d1f2c; font-size: 28px; margin: 16px 0 8px; font-weight: 700;">
                            Appointment Confirmed!
                        </h1>
                        <p style="color: #8a7a88; font-size: 16px; margin: 0;">
                            Your appointment has been confirmed by {{ $salon->name }}
                        </p>
                    </td>
                </tr>

                <!-- Divider -->
                <tr>
                    <td style="padding: 0 0 30px;">
                        <hr style="border: none; border-top: 2px solid #fce4ec;">
                    </td>
                </tr>

                <!-- Booking Details -->
                <tr>
                    <td>
                        <h3 style="color: #2d1f2c; font-size: 18px; margin: 0 0 16px; font-weight: 700;">
                            📋 Booking Details
                        </h3>

                        <table width="100%" cellpadding="0" cellspacing="0" style="background: #fcf9fc; border-radius: 12px; padding: 16px 20px; border: 1px solid #f0eef0;">
                            <tr>
                                <td style="padding: 8px 0; color: #8a7a88; font-size: 14px; width: 120px;">Booking Ref</td>
                                <td style="padding: 8px 0; color: #2d1f2c; font-weight: 600; font-size: 14px;">
                                    <span style="background: #fce4ec; padding: 4px 12px; border-radius: 6px; color: #E91E8C;">{{ $appointment->booking_ref }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; color: #8a7a88; font-size: 14px;">Service</td>
                                <td style="padding: 8px 0; color: #2d1f2c; font-weight: 600; font-size: 14px;">{{ $appointment->service->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; color: #8a7a88; font-size: 14px;">Stylist</td>
                                <td style="padding: 8px 0; color: #2d1f2c; font-weight: 600; font-size: 14px;">{{ $appointment->stylist->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; color: #8a7a88; font-size: 14px;">Date & Time</td>
                                <td style="padding: 8px 0; color: #2d1f2c; font-weight: 600; font-size: 14px;">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, F d, Y') }}<br>
                                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; color: #8a7a88; font-size: 14px;">Total Amount</td>
                                <td style="padding: 8px 0; color: #E91E8C; font-weight: 700; font-size: 16px;">
                                    PKR {{ number_format($appointment->total_amount) }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- Salon Info -->
                <tr>
                    <td style="padding-top: 30px;">
                        <h3 style="color: #2d1f2c; font-size: 18px; margin: 0 0 12px; font-weight: 700;">
                            📍 Salon Location
                        </h3>
                        <p style="color: #6b4f62; font-size: 15px; margin: 0;">
                            <strong>{{ $salon->name }}</strong><br>
                            {{ $salon->address ?? 'Address not available' }}<br>
                            📞 {{ $salon->phone ?? 'N/A' }}
                        </p>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="padding-top: 30px; text-align: center;">
                        <hr style="border: none; border-top: 2px solid #fce4ec; margin-bottom: 20px;">
                        <p style="color: #8a7a88; font-size: 13px; margin: 0;">
                            Need to reschedule or have questions? Contact us at <a href="mailto:{{ $salon->email }}" style="color: #E91E8C; text-decoration: none;">{{ $salon->email }}</a>
                        </p>
                        <p style="color: #b0a5ae; font-size: 12px; margin: 8px 0 0;">
                            &copy; {{ date('Y') }} {{ $salon->name }}. All rights reserved.
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>