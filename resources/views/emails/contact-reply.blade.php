<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reply from {{ config('app.name') }}</title>
</head>
<body style="margin:0; padding:0; background-color:#fff0f7; font-family: Arial, Helvetica, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#fff0f7; padding:30px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,0.05);">

                    {{-- Header --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #FF6B9D, #E85588); padding:24px 30px;">
                            <h2 style="margin:0; color:#ffffff; font-size:20px;">{{ config('app.name') }}</h2>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:30px;">
                            <p style="font-size:15px; color:#3d3d3d; line-height:1.7; margin:0; white-space:pre-wrap;">
                                {{ $contactMessage->reply }}
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:20px 30px; background-color:#fafafb; border-top:1px solid #f0f0f3;">
                            <p style="font-size:13px; color:#a5a5b3; margin:0;">
                                Thanks,<br>{{ config('app.name') }}
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>