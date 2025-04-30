@extends('layouts.staff-layout')

@section('title', 'Landlord • Detail')

@section('content')

<style type="text/css">
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
        width: 250px; /* Fixed width */
        height: 230px; /* Fixed height */
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


    .details-container {
        color: #333;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        padding: 15px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        overflow: hidden;
        margin-top: 10px;
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

    .container {
        max-width: 100%;
        padding:10px; /* Adjust padding as needed */
        box-sizing: border-box; /* Includes padding in width calculations */
    }
</style>

<div class="container agent-details-container">
    <a href="#" id="backButton" onclick="event.preventDefault(); window.history.back();" class="btn float-end" style="background-color: #17395e; color: white;">Back</a>

    <script>
    window.onload = function() {
        // Check if the history length is greater than 1
        if (window.history.length <= 1) {
        // If not, hide the back button
        document.getElementById('backButton').style.display = 'none';
        }
    };
    </script>
    {{-- <a href="{{ url('staff/user/landlord')}}" class="btn float-end" style="background-color: #17395e; color: white;">Back</a> --}}
    <div class="agent-details-header" id="agentDetailHeader">
        <h4>Landlord Detail</h4>
    </div>
    <div class="card-body" id="agentDetailBody">
        <div class="d-flex justify-content-start">
            <div class="avatar-container">
                <img src="{{ asset($landlord->avatar) }}" alt="Avatar">
            </div>
            <div class="container mt-3">
                <div class="agent-details-row">
                    <div class="agent-details-label">Name</div>
                    <div class="agent-details-value">{{ $landlord->name }}</div>
                </div>
                <div class="agent-details-row">
                    <div class="agent-details-label">I/C Number</div>
                    <div class="agent-details-value">{{ substr($landlord->number_ic, 0, 6) }}-{{ substr($landlord->number_ic, 6, 2) }}-{{ substr($landlord->number_ic, 8) }}</div>
                </div>
                <div class="agent-details-row">
                    <div class="agent-details-label">Email</div>
                    <div class="agent-details-value">{{ $landlord->email }}</div>
                </div>
                <div class="agent-details-row">
                    <div class="agent-details-label">Phone</div>
                    <div class="agent-details-value">{{ substr($landlord->phone, 0, 3) }}-{{ substr($landlord->phone, 3) }}</div>
                </div>
                <div class="agent-contact-buttons">
                    @php
                        // Assuming $phoneNumber contains the phone number fetched from the database
                        $phoneNumber = $landlord->phone; // Example phone number

                        $countryCode = '+6'; // Country code, e.g., +6 for Malaysia

                        // Prepend the country code to the phone number if it doesn't already have it
                        if (substr($phoneNumber, 0, 1) !== '+') {
                            $phoneNumber = $countryCode . $phoneNumber;
                        }

                        // Remove any non-numeric characters from the phone number
                        $cleanPhoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

                        // Construct the WhatsApp link
                        $whatsappLink = 'https://wa.me/' . $cleanPhoneNumber;
                    @endphp
                    <a href="{{ $whatsappLink }}" class="btn agent-contact-button whatsapp-button" target="_blank">
                        <i class="fa-brands fa-square-whatsapp"></i> <!-- WhatsApp Icon -->
                    </a>
                    <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $landlord->email }}" class="btn agent-contact-button email-button" target="_blank">
                        <i class="fa-solid fa-envelope"></i> <!-- Email Icon -->
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="details-container">
    {{-- <a href="{{ route('landlord-create-property') }}" class="btn float-end" style="background-color: #023d90; color: white;">New Property</a> --}}
    <div class="details-header" id="paymentDetailHeader">
        <h4>Property List
        </h4>
    </div>
    <div class="card-body" id="paymentDetailBody">
        <div class="details-table">
            @if($properties->isEmpty())
            <div class="text-center p-3">
                <h3 class="text-secondary">This agent don't have any properties assigned yet.</h3>
            </div>
        @else
        <table class="details-table">
            <thead>
                <tr>
                    <th>Address</th>
                    <th>Type</th>
                    <th>Deposit (RM)</th>
                    {{-- <th>Monthly (RM)</th> --}}
                    <th>Description</th>
                    <th>Available</th>
                    <th>Status</th>
                    <th>Action</th>
                    {{-- <th style="width: 150px;">Action</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($properties as $property)
                <tr>
                    <td>{{$property->address}}</td>
                    <td style="text-align: center">
                    @if ($property->type == 1)
                    Bungalow/Villa
                    @elseif ($property->type == 2)
                    Semid-D
                    @elseif ($property->type == 3)
                    Terrace
                    @elseif ($property->type == 4)
                    Townhouse
                    @elseif ($property->type == 5)
                    Flat/Apartment
                    @elseif ($property->type == 6)
                    Condominium
                    @elseif ($property->type == 7)
                    Penthouse
                    @else
                    Shop House
                    @endif
                    </td>
                    <td style="text-align: center">{{$property->deposit}}</td>
                    {{-- <td style="text-align: center">{{$property->monthly}}</td> --}}
                    <td>{{$property->description}}</td>
                    <td style="text-align: center">
                    @if ($property->available == 1)
                    <a href="#">&#9989</a>
                    @else
                    <a href="#">&#10060</a>
                    @endif
                    {{-- <td style="text-align: center">{{$property->available}}</td> --}}
                    <td style="text-align: center;">
                    @if ($property->status == 3)
                    Approval
                    @elseif ($property->status == 1)
                    Active
                    @elseif ($property->status == 2)
                    Inactive
                    @else
                    Incomplete
                    @endif
                    {{-- <td>{{$property->status}}</td> --}}
                    <td style="text-align: center">
                        <a href="{{ url('staff/property/view/'.$property->id.'') }}" class="col" style="color:green; margin-right: 10px;" title="View"><span><i class="fa-solid fa-eye"></i></span></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        </div>
    </div>
</div>


@endsection
