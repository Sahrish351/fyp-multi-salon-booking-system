<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Support Ticket</title>
</head>
<body style="margin:0; padding:0; background-color:#fff0f7; font-family: Arial, Helvetica, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#fff0f7; padding:30px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,0.05);">

                    <tr>
                        <td style="background: linear-gradient(135deg, #FF6B9D, #E85588); padding:24px 30px;">
                            <h2 style="margin:0; color:#ffffff; font-size:20px;">New Support Ticket</h2>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px;">
                            <p style="font-size:14px; color:#888; margin:0 0 4px;">From</p>
                            <p style="font-size:15px; color:#232323; margin:0 0 16px;">
                                {{ $ticket->name }} ({{ $ticket->email }})
                            </p>

                            <p style="font-size:14px; color:#888; margin:0 0 4px;">Subject</p>
                            <p style="font-size:15px; color:#232323; margin:0 0 16px;">
                                {{ $ticket->subject }}
                            </p>

                            <p style="font-size:14px; color:#888; margin:0 0 4px;">Message</p>
                            <p style="font-size:15px; color:#3d3d3d; line-height:1.7; margin:0 0 24px; white-space:pre-wrap;">
                                {{ $ticket->message }}
                            </p>

                            <p style="text-align:center; margin:0;">
                                <a href="{{ route('admin.contact-messages.show', $ticket->id) }}"
                                   style="background: linear-gradient(135deg, #FF6B9D, #E85588); color:#ffffff; text-decoration:none; padding:12px 28px; border-radius:50px; font-weight:600; font-size:14px; display:inline-block;">
                                    View Ticket
                                </a>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:20px 30px; background-color:#fafafb; border-top:1px solid #f0f0f3;">
                            <p style="font-size:13px; color:#a5a5b3; margin:0;">
                                {{ config('app.name') }} — Admin Notification
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>