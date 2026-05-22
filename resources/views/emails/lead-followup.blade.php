<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lead Follow-up</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f6f1ea; padding:24px; color:#111827;">
    <div style="max-width:640px; margin:auto; background:#ffffff; border-radius:18px; padding:26px; border:1px solid #eee;">
        <h2 style="margin:0 0 16px; font-size:22px;">
            {{ setting('company_name', 'LeadFlow CRM') }}
        </h2>

        <div style="font-size:15px; line-height:1.7; white-space:pre-line;">
            {{ $messageText }}
        </div>

        <hr style="border:none; border-top:1px solid #eee; margin:24px 0;">

        <p style="font-size:12px; color:#6b7280; margin:0;">
            This email was sent from {{ setting('crm_name', 'LeadFlow CRM') }}.
        </p>
    </div>
</body>
</html>