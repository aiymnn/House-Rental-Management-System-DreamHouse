@extends('layouts.agent-layout')

@section('title', 'Contract • View')

@section('content')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0" />


<style type="text/css">
    .contract-detail-container {
        color: #333; /* Darker text for better readability */
        background: #fff; /* Light background for a professional appearance */
        border-radius: 8px; /* Rounded corners */
        box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Subtle shadow for depth */
        padding: 20px; /* Padding around the content */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Professional font */
        margin-top: 75px;
        margin-bottom: 15px; /* Space below the header */
    }

    .contract-detail-header {
        padding-bottom: 15px;
        border-bottom: 2px solid #dee2e6; /* Light grey border for separation */
        margin-bottom: 20px; /* Space below the header */
    }

    .contract-detail-row {
        display: flex;
        justify-content: space-between; /* Space between the label and value */
        margin-bottom: 10px; /* Space between rows */
    }

    .contract-detail-label {
        font-weight: 600; /* Bold label for emphasis */
        color: #022d6a; /* Dark blue for a touch of color */
        flex-basis: 30%; /* Width of the label */
    }

    .contract-detail-value {
        background: #f8f9fa; /* Very light background for the value */
        padding: 8px 12px; /* Padding inside the value box */
        border-radius: 5px; /* Rounded corners for the value box */
        flex-basis: 65%; /* Width of the value area */
        /* text-align: right; */
    }

    .contract-action-alert {
        background-color: #ffc107; /* Yellow background for attention */
        color: #333; /* Dark text for visibility */
        padding: 15px;
        margin: 20px 0;
        border-radius: 5px; /* Rounded corners */
        display: flex;
        justify-content: space-between; /* Space between text and button */
        align-items: center;
    }

    .contract-action-button {
        background-color: #0056b3; /* Dark blue background */
        color: #fff; /* White text */
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
    }
    .agent-details-container {
        color: #333;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        padding: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;

    }

    .agent-details-header {
        cursor: pointer;
        padding-bottom: 15px;
        border-bottom: 2px solid #dee2e6;
        margin-bottom: 20px;
    }

    .agent-details-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .agent-details-label {
        font-weight: 600;
        color: #022d6a;
        flex-basis: 30%;
    }

    .agent-details-value {
        background: #f8f9fa;
        padding: 8px 12px;
        border-radius: 5px;
        flex-basis: 65%;
    }

    .avatar-container {
        position: relative; /* Allows precise positioning */
        width: 180px; /* Fixed width */
        height: 180px; /* Fixed height */
        margin-right: 20px; /* Right margin for spacing */
    }

    .avatar-container img {
        width: 100%; /* Full width of the container */
        height: 100%; /* Full height of the container */
        border-radius: 50%; /* Circle shape */
        object-fit: cover; /* Ensures the image covers the container */
    }

    .agent-contact-button {
        padding: 8px 12px;
        border-radius: 5px;
        font-size: 16px; /* Adjust if necessary to match your design */
        margin-right: 10px;
        cursor: pointer;
        text-decoration: none;
        color: white;
        background-color: #007BFF; /* Default for all buttons */
        display: inline-flex;
        align-items: center; /* Center the icon vertically */
        justify-content: center; /* Center the icon horizontally */
    }

    .whatsapp-button {
        background-color: #25D366; /* WhatsApp green */
    }

    .email-button {
        background-color: #007BFF; /* Email button blue */
    }

    /* Adjust icon sizes if necessary */
    .agent-contact-button i {
        font-size: 20px; /* Larger icons */
    }

    /* Example of responsive settings for the main containers */
    .container {
        max-width: 100%;
        padding:10px; /* Adjust padding as needed */
        box-sizing: border-box; /* Includes padding in width calculations */
    }

    .details-container {
        color: #333;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        padding: 15px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin-top: 10px;
        overflow: hidden;
    }

    .details-header {
        cursor: pointer;
        padding-bottom: 10px;
        border-bottom: 1px solid #dee2e6;
        margin-bottom: 15px;
    }

    .details-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 5px;
    }

    .details-table th, .details-table td {
        border: 1px solid #dee2e6;
        padding: 6px;
        text-align: left;
    }

    .details-table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }


</style>

<div class="container contract-detail-container">
    <a href="#" id="backButton" onclick="event.preventDefault(); window.history.back();" class="btn float-end" style="background-color: #023d90; color: white;">Back</a>

    <script>
    window.onload = function() {
        // Check if the history length is greater than 1
        if (window.history.length <= 1) {
        // If not, hide the back button
        document.getElementById('backButton').style.display = 'none';
        }
    };
    </script>
    <div class="contract-detail-header">
        <h4>Contract Details</h4>
    </div>

    @if ($contract->status == 2)
    <li class="alert alert-warning"><span>Ask tenant to pay deposit to proceed the contract.</span></li>
    @endif

    <div class="contract-detail-row">
        <div class="contract-detail-label">Address<span style="color: #023d90"><a href="{{ url('agent/property/view/'.$property->id.'') }}" style="margin-left: 5px;" title="view property"><i class="fa fa-search" aria-hidden="true"></i></a></span></div>
        <div class="contract-detail-value">{{ $property->address }}</div>
    </div>
    <div class="contract-detail-row">
        <div class="contract-detail-label">Period (month)</div>
        <div class="contract-detail-value">{{ $contract->period }}</div>
    </div>
    <div class="contract-detail-row">
        <div class="contract-detail-label">Deposit (RM)</div>
        <div class="contract-detail-value">
            {{ number_format($contract->deposit, 2) }}
            @if ($contract->deposit < $property->deposit)
                @php
                    $b = $contract->deposit - $property->deposit;
                @endphp
                &nbsp;&nbsp;&nbsp;&nbsp;<span style="color: red"> {{ number_format($b, 2) }}</span>
            @endif
        </div>
    </div>
    <div class="contract-detail-row">
        <div class="contract-detail-label">Monthly (RM)</div>
        <div class="contract-detail-value">{{ number_format($property->monthly, 2) }}</div>
    </div>
    <div class="contract-detail-row">
        <div class="contract-detail-label">Total (RM)</div>
        <div class="contract-detail-value">{{ number_format($contract->total, 2) }}</div>
    </div>
    <div class="contract-detail-row">
        <div class="contract-detail-label">Balance (RM)</div>
        <div class="contract-detail-value">{{ number_format($contract->balance, 2) }}</div>
    </div>
    <div class="contract-detail-row">
        <div class="contract-detail-label">Start Date</div>
        <div class="contract-detail-value">{{ date('d-m-Y', strtotime($contract->start_date)) }}</div>
    </div>
    <div class="contract-detail-row">
        <div class="contract-detail-label">End Date</div>
        <div class="contract-detail-value">{{ date('d-m-Y', strtotime($contract->end_date)) }}</div>
    </div>
    <div class="contract-detail-row">
        <div class="contract-detail-label">Status</div>
        <div class="contract-detail-value">
            @if ($contract->status == 2)
            Pending
            @elseif ($contract->status == 1)
            Active
            @else
            Terminated
            @endif
        </div>
    </div>

    <!-- Add more rows as needed -->

</div>

<div class="details-container">
    {{-- <a href="{{ url('tenant/payment/new') }}" class="btn float-end" style="background-color: #023d90; color: white;">New Payment</a> --}}
    <div class="details-header" id="paymentDetailHeader">
        <h4>Payment History
        </h4>
    </div>
    <div class="card-body" style="display: none;" id="paymentDetailBody">
        @if ($payments->isEmpty())
        <h4 class="text-secondary" style="text-align: center;">No Payment History</h4>
        @else
        <table class="details-table">
            <thead>
                <tr>
                    <th>Payment Type</th>
                    <th>Amount Paid</th>
                    <th>Payment Date</th>
                    <th>Payment Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                <tr>
                    <td>{{ $payment->type == 1 ? 'Deposit' : 'Monthly Rent' }}</td>
                    <td>{{ number_format($payment->total, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('H:i:s') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $("#paymentDetailHeader").click(function(){
        $("#paymentDetailBody").slideToggle();
    });
});
</script>

@endsection
