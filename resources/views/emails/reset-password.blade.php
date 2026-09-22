<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Your Password</title>
</head>
<body style="margin:0; padding:0; background-color:#fff0f7; font-family: Arial, Helvetica, sans-serif;">
 
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" bgcolor="#fff0f7" style="background-color:#fff0f7; padding:30px 0;">
        <tr>
            <td align="center">
 
                <table role="presentation" width="480" cellpadding="0" cellspacing="0" bgcolor="#ffffff"
                       style="background-color:#ffffff; border-radius:16px; overflow:hidden; border:1px solid #f3d4e2;">
 
                    <!-- Header -->
                    <tr>
                        <td align="center" bgcolor="#E85588" style="background-color:#E85588; padding:34px 20px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" align="center" style="margin:0 auto;">
                                <tr>
                                    <td align="center" valign="middle" bgcolor="#FF6B9D"
                                        style="background-color:#FF6B9D; width:56px; height:56px; min-width:56px; border-radius:14px;
                                               font-size:22px; line-height:56px; color:#ffffff; font-weight:bold; text-align:center;
                                               font-family: Arial, Helvetica, sans-serif;">
                                        BB
                                    </td>
                                </tr>
                            </table>
                            <h1 style="margin:14px 0 0; color:#ffffff; font-size:22px; font-family: Georgia, serif;">
                                Beauty Blush Salons
                            </h1>
                            <p style="margin:4px 0 0; color:#ffe4ef; font-size:13px;">
                                Your beauty, our priority
                            </p>
                        </td>
                    </tr>
 
                    <!-- Body -->
                    <tr>
                        <td style="padding:36px 34px;">
                            <h2 style="margin:0 0 14px; color:#1a1a2e; font-size:19px;">
                                Hello {{ $userName }},
                            </h2>
                            <p style="margin:0 0 22px; color:#6b6b7b; font-size:14.5px; line-height:1.6;">
                                We received a request to reset the password for your Beauty Blush Salons account.
                                Click the button below to choose a new password.
                            </p>
 
                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" style="padding: 6px 0 26px;">
                                        <table role="presentation" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td align="center" bgcolor="#E85588" style="background-color:#E85588; border-radius:10px;">
                                                    <a href="{{ $resetUrl }}"
                                                       style="display:inline-block; padding:13px 36px; color:#ffffff; text-decoration:none; font-weight:bold; font-size:14.5px;">
                                                        Reset Password
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
 
                            <p style="margin:0 0 10px; color:#a5a5b3; font-size:13px; line-height:1.6;">
                                This link will expire in <strong style="color:#6b6b7b;">60 minutes</strong>.
                            </p>
                            <p style="margin:0; color:#a5a5b3; font-size:13px; line-height:1.6;">
                                If you didn't request a password reset, no further action is required — your account is safe.
                            </p>
 
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:26px 0;">
                                <tr><td style="border-top:1px solid #ececef; font-size:0; line-height:0;">&nbsp;</td></tr>
                            </table>
 
                            <p style="margin:0; color:#a5a5b3; font-size:12px; line-height:1.6;">
                                Having trouble with the button? Copy and paste this link into your browser:<br>
                                <a href="{{ $resetUrl }}" style="color:#E85588; word-break:break-all;">{{ $resetUrl }}</a>
                            </p>
                        </td>
                    </tr>
 
                    <!-- Footer -->
                    <tr>
                        <td align="center" bgcolor="#fdf7fa" style="background-color:#fdf7fa; padding:20px; border-top:1px solid #ececef;">
                            <p style="margin:0; color:#a5a5b3; font-size:12px;">
                                Regards,<br>
                                <strong style="color:#6b6b7b;">Beauty Blush Salons Team</strong>
                            </p>
                        </td>
                    </tr>
 
                </table>
 
                <p style="margin:16px 0 0; color:#c2c2ca; font-size:11px;">
                    © {{ date('Y') }} Beauty Blush Salons. All rights reserved.
                </p>
 
            </td>
        </tr>
    </table>
 
</body>
</html>