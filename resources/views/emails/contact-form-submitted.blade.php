<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Message</title>
</head>
<body style="margin:0; padding:24px; font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; color:#0f172a; background:#ffffff;">
    <div style="max-width:640px; margin:0 auto;">
        <h1 style="margin:0 0 16px; font-size:20px; font-weight:700;">New contact message</h1>
        <div style="margin:0 0 12px;">
            <div style="font-size:12px; color:#475569;">Name</div>
            <div style="font-size:14px; font-weight:600;">{{ $data['name'] }}</div>
        </div>
        <div style="margin:0 0 12px;">
            <div style="font-size:12px; color:#475569;">Email</div>
            <div style="font-size:14px; font-weight:600;">{{ $data['email'] }}</div>
        </div>
        <div style="margin:0 0 12px;">
            <div style="font-size:12px; color:#475569;">Message</div>
            <div style="font-size:14px; line-height:1.6; white-space:pre-wrap;">{{ $data['message'] }}</div>
        </div>
    </div>
</body>
</html>

