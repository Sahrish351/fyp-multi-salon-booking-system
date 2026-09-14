<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slot Available</title>
</head>
<body style="margin:0; padding:0; background-color:#f5f0f2; font-family: 'Segoe UI', Arial, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f5f0f2; padding:30px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="480" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,0.06);">
 
                    <tr>
                        <td style="background-color:#b5306f; padding:28px 24px; text-align:center;">
                            <div style="font-size:22px; font-weight:600; color:#ffffff; letter-spacing:0.3px;">
                                Beauty Blush Salons
                            </div>
                            <div style="font-size:12px; color:#f3d4e2; margin-top:4px;">
                                Appointment Notification
                            </div>
                        </td>
                    </tr>
 
                    <tr>
                        <td style="padding:32px 32px 8px 32px;">
                            <h2 style="color:#2d2d2d; margin:0 0 8px 0; font-size:19px;">
                                Your Slot is Ready
                            </h2>
                            <p style="color:#5c5c5c; margin:0; font-size:14px;">
                                Dear {{ $userName }},
                            </p>
                        </td>
                    </tr>
 
                    <tr>
                        <td style="padding:8px 32px 0 32px;">
                            <p style="color:#3d3d3d; font-size:14.5px; line-height:1.6; margin:0 0 20px 0;">
                                A spot has opened up for <strong>{{ $serviceName }}</strong> at
                                <strong>{{ $salonName }}</strong>@if($preferredDate) on <strong>{{ $preferredDate }}</strong>@endif.
                                This slot is currently reserved for you.
                            </p>
 
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#faf2f6; border-left:3px solid #b5306f; border-radius:6px; margin-bottom:22px;">
                                <tr>
                                    <td style="padding:14px 18px;">
                                        <div style="color:#b5306f; font-size:12.5px; font-weight:600; text-transform:uppercase; letter-spacing:0.4px; margin-bottom:4px;">
                                            Action Required
                                        </div>
                                        <div style="color:#3d3d3d; font-size:14px; line-height:1.5;">
                                            Please confirm within <strong>20 minutes</strong>
                                            @if($expiresAt)
                                                (by <strong>{{ $expiresAt }}</strong>)
                                            @endif
                                            or this slot will be offered to the next client on the waitlist.
                                        </div>
                                    </td>
                                </tr>
                            </table>
 
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding-bottom:26px;">
                                        <a href="{{ $acceptUrl }}"
                                           style="background-color:#b5306f; color:#ffffff; text-decoration:none; font-size:14.5px; font-weight:600; padding:13px 34px; border-radius:6px; display:inline-block;">
                                            View My Waitlist
                                        </a>
                                    </td>
                                </tr>
                            </table>
 
                            <p style="color:#8a8a8a; font-size:12.5px; text-align:center; margin:0 0 26px 0;">
                                Log in to your account and select "Accept Slot" to confirm your booking.
                            </p>
                        </td>
                    </tr>
 
                    <tr>
                        <td style="background-color:#faf2f6; padding:18px 32px; text-align:center;">
                            <p style="color:#9a9a9a; font-size:12px; margin:0;">
                                Thank you for choosing Beauty Blush Salons.
                            </p>
                            <p style="color:#b8b8b8; font-size:11px; margin:6px 0 0 0;">
                                If you were not expecting this email, you can safely ignore it.
                            </p>
                        </td>
                    </tr>
 
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
 