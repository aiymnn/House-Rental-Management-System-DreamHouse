<!-- resources/views/pdf/contract.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Agreement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
        }
        h1, h2 {
            text-align: center;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            margin-bottom: 10px;
        }
        .terms-list {
            list-style-type: none;
            padding: 0;
        }
        .terms-list li {
            margin-bottom: 10px;
        }
        .signature-section {
            margin-top: 50px;
        }
        .signature {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .signature div {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Rental Agreement</h1>
    <p>This Rental Agreement (the "Agreement") is made and entered into on {{ \Carbon\Carbon::now()->format('d M Y') }} between:</p>

    <div class="section">
        <h2>Agent Details</h2>
        <p><strong>Name:</strong> {{ Auth::guard('staff')->user()->name }}</p>
        <p><strong>Email:</strong> {{ Auth::guard('staff')->user()->email }}</p>
        <p><strong>Phone:</strong> {{ Auth::guard('staff')->user()->phone }}</p>
    </div>

    <div class="section">
        <h2>Tenant Details</h2>
        <p><strong>Name:</strong> {{ $tenant->name }}</p>
        <p><strong>Email:</strong> {{ $tenant->email }}</p>
        <p><strong>IC/Passport Number:</strong> {{ $tenant->number_ic }}</p>
    </div>

    <div class="section">
        <h2>Property Details</h2>
        <p><strong>Property Address:</strong> {{ $property->address }}</p>
    </div>

    <div class="section">
        <h2>Agreement Details</h2>
        <ul class="terms-list">
            <li><strong>Rental Period:</strong> {{ $period }} months ({{ $startDate }} to {{ $endDate }})</li>
            <li><strong>Monthly Rent:</strong> RM {{ number_format($monthly, 2) }}</li>
            <li><strong>Total Rent:</strong> RM {{ number_format($balance, 2) }}</li>
            <li><strong>Payment Due Date:</strong> First day of each month</li>
            <li><strong>Security Deposit:</strong> RM {{ number_format($deposit, 2) }} (Refundable)</li>
            <li><strong>Late Payment Fee:</strong> RM {{ number_format($lateFee, 2) }} per day after the due date</li>
        </ul>
    </div>

    <div class="section">
        <h2>Terms and Conditions</h2>
        <p>The Tenant agrees to the following terms and conditions:</p>
        <ul class="terms-list">
            <li>The Tenant shall pay the rent on or before the due date specified above.</li>
            <li>The Tenant shall maintain the property in a clean and sanitary condition.</li>
            <li>The Tenant shall not make any alterations to the property without the written consent of the Agent.</li>
            <li>The Tenant shall notify the Agent of any damage or issues with the property.</li>
            <li>The Tenant agrees to comply with all local laws and regulations.</li>
        </ul>
    </div>

    <div class="signature-section">
        <h2>Signatures</h2>
        <div class="signature">
            <div>
                <p>_________________________</p>
                <p>Agent Signature</p>
            </div>
            <div>
                <p>_________________________</p>
                <p>Tenant Signature</p>
            </div>
        </div>
    </div>
</body>
</html>
