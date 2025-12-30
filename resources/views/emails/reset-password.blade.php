<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - D4ily.1</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f4f4f5;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f5; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 40px 30px; text-align: center;">
                            <div style="width: 60px; height: 60px; background-color: #18181b; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                                <span style="font-size: 24px; font-weight: bold; color: #ffffff;">d1</span>
                            </div>
                            <h1 style="color: #ffffff; font-size: 24px; font-weight: 700; margin: 0;">d4ily.1</h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 40px 30px;">
                            <h2 style="color: #18181b; font-size: 22px; font-weight: 600; margin: 0 0 16px 0;">Reset Your Password</h2>
                            <p style="color: #71717a; font-size: 16px; line-height: 1.6; margin: 0 0 24px 0;">
                                Hello! You recently requested to reset your password for your d4ily.1 account. Click the button below to reset it.
                            </p>
                            <p style="color: #71717a; font-size: 16px; line-height: 1.6; margin: 0 0 24px 0;">
                                This password reset link will expire in 60 minutes. If you didn't request this, please ignore this email.
                            </p>

                            <!-- Reset Button -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding: 20px 0;">
                                        <a href="{{ $url }}" style="display: inline-block; background-color: #18181b; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-size: 16px; font-weight: 600;">
                                            Reset Password
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Alternative Link -->
                            <p style="color: #71717a; font-size: 14px; line-height: 1.6; margin: 24px 0 0 0;">
                                If the button doesn't work, you can copy and paste this link into your browser:
                            </p>
                            <p style="color: #3f3f46; font-size: 13px; line-height: 1.5; margin: 8px 0 0 0; word-break: break-all;">
                                {{ $url }}
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f4f4f5; padding: 24px 40px; text-align: center; border-top: 1px solid #e4e4e7;">
                            <p style="color: #71717a; font-size: 14px; margin: 0 0 8px 0;">
                                Sustainable fashion reimagined. Every piece has a story.
                            </p>
                            <p style="color: #a1a1aa; font-size: 12px; margin: 0;">
                                © {{ date('Y') }} d4ily.1. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>

                <p style="color: #71717a; font-size: 13px; margin: 24px 0 0 0;">
                    If you're having trouble clicking the reset button, copy and paste the URL above into your web browser.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
