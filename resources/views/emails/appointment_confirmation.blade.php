<!DOCTYPE html>
<html>
<head>
    <title>Appointment Confirmation</title>
</head>
<body>
    <h1>Appointment Confirmation</h1>
    <p>Dear {{ $tenant->name }},</p>
    <p>Your appointment has been scheduled for:</p>
    <ul>
        <li>Date: {{ $date }}</li>
        <li>Time: {{ $time }}</li>
    </ul>
    <p>Thank you for booking with us!</p>
    <br>
    <p>Best regards,</p>
    <p>{{ $agent->name }}</p>
    <p>{{ $agent->phone }}</p>
    <p>{{ $agent->email }}</p>
</body>
</html>
