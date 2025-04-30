<!-- resources/views/emails/contract_email.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Rental Contract</title>
</head>
<body>
    <h1>Dear {{ $tenant->name }},</h1>

    <p>Attached is your rental contract for the property located at {{ $property->address }}.</p>
    <p>Please review the contract and sign it at your earliest convenience.</p>

    <p>If you have any questions, feel free to reach out to us.</p>

    <p>Best regards,</p>
    <p>{{ Auth::guard('staff')->user()->name }}</p>
</body>
</html>
