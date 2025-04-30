@extends('layouts.staff-layout')

@section('title', 'Tenant • Detail')

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
        position: relative;
        width: 250px;
        height: 230px;
        margin-right: 20px;
    }

    .avatar-container img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }

    .agent-contact-button {
        padding: 8px 12px;
        border-radius: 5px;
        font-size: 16px;
        margin-right: 10px;
        cursor: pointer;
        text-decoration: none;
        color: white;
        background-color: #007BFF;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .whatsapp-button {
        background-color: #25D366;
    }

    .email-button {
        background-color: #007BFF;
    }

    .contract-detail-container {
        color: #333;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        padding: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin-bottom: 15px;
    }

    .contract-detail-header {
        padding-bottom: 15px;
        border-bottom: 2px solid #dee2e6;
        margin-bottom: 20px;
    }

    .contract-detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .contract-detail-label {
        font-weight: 600;
        color: #022d6a;
        flex-basis: 30%;
    }

    .contract-detail-value {
        background: #f8f9fa;
        padding: 8px 12px;
        border-radius: 5px;
        flex-basis: 65%;
    }

    .contract-action-alert {
        background-color: #ffc107;
        color: #333;
        padding: 15px;
        margin: 20px 0;
        border-radius: 5px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .contract-action-button {
        background-color: #0056b3;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
    }

    .container {
        max-width: 100%;
        padding: 10px;
        box-sizing: border-box;
    }

    .nav-tabs {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        margin-top: 15px;
        background: #f8f9fa;
        border-radius: 5px;
    }

    .nav-item {
        flex-grow: 1;
        text-align: center;
    }

    .nav-link {
        display: block;
        padding: 10px 15px;
        text-decoration: none;
        color: #022d6a;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .nav-link.active {
        background-color: #fff;
        color: #006ad4;
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
    }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="container agent-details-container">
    <a href="#" id="backButton" onclick="event.preventDefault(); window.history.back();" class="btn float-end" style="background-color: #17395e; color: white;">Back</a>

    <script>
        window.onload = function() {
            if (window.history.length <= 1) {
                document.getElementById('backButton').style.display = 'none';
            }
        };
    </script>

    <div class="agent-details-header">
        <h4>Tenant Detail</h4>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-start">
            <div class="avatar-container">
                <img src="{{ asset($tenant->avatar) }}" alt="Avatar">
            </div>
            <div class="container mt-3">
                <div class="agent-details-row">
                    <div class="agent-details-label">Name</div>
                    <div class="agent-details-value">{{ $tenant->name }}</div>
                </div>
                <div class="agent-details-row">
                    <div class="agent-details-label">I/C Number</div>
                    <div class="agent-details-value">{{ substr($tenant->number_ic, 0, 6) }}-{{ substr($tenant->number_ic, 6, 2) }}-{{ substr($tenant->number_ic, 8) }}</div>
                </div>
                <div class="agent-details-row">
                    <div class="agent-details-label">Email</div>
                    <div class="agent-details-value">{{ $tenant->email }}</div>
                </div>
                <div class="agent-details-row">
                    <div class="agent-details-label">Phone</div>
                    <div class="agent-details-value">{{ substr($tenant->phone, 0, 3) }}-{{ substr($tenant->phone, 3) }}</div>
                </div>
                <div class="agent-contact-buttons">
                    @php
                        $phoneNumber = $tenant->phone;
                        $countryCode = '+6';
                        if (substr($phoneNumber, 0, 1) !== '+') {
                            $phoneNumber = $countryCode . $phoneNumber;
                        }
                        $cleanPhoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
                        $whatsappLink = 'https://wa.me/' . $cleanPhoneNumber;
                    @endphp
                    <a href="{{ $whatsappLink }}" class="btn agent-contact-button whatsapp-button" target="_blank">
                        <i class="fa-brands fa-square-whatsapp"></i>
                    </a>
                    <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $tenant->email }}" class="btn agent-contact-button email-button" target="_blank">
                        <i class="fa-solid fa-envelope"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@if($tenant->contracts->isNotEmpty())
    <div class="nav-tabs">
        @foreach ($tenant->contracts as $index => $contract)
            <div class="nav-item">
                <a href="#" class="nav-link @if($index == 0) active @endif" onclick="changeTab(event, 'contract-pane-{{ $contract->id }}')">
                    Contract {{ $contract->id }}
                    @if ($contract->status == '2')
                        <span style="color: yellow; font-size: 20px;">•</span>
                    @elseif ($contract->status == '1')
                        <span style="color: green; font-size: 20px;">•</span>
                    @else
                        <span style="color: red; font-size: 20px;">•</span>
                    @endif
                </a>
            </div>
        @endforeach
    </div>

    <div class="tab-content">
        @foreach ($tenant->contracts as $index => $contract)
            <div id="contract-pane-{{ $contract->id }}" class="contract-detail-container tab-pane @if($index == 0) active @endif">
                <a href="{{url('staff/contract/view/'.$contract->id.'') }}" class="btn float-end" style="background-color: #17395e; color: white;">View</a>
                <div class="contract-detail-header">
                    <h4>Contract Details</h4>
                </div>
                @if ($contract->status == 2)
                    <div class="contract-action-alert">
                        <span>Tenant needs to pay deposit first to proceed with the contract.</span>
                    </div>
                @endif
                <div class="contract-detail-row">
                    <div class="contract-detail-label">Address</div>
                    <div class="contract-detail-value">{{ $contract->property->address }}</div>
                </div>
                <div class="contract-detail-row">
                    <div class="contract-detail-label">Period (month)</div>
                    <div class="contract-detail-value">{{ $contract->period }}</div>
                </div>
                <div class="contract-detail-row">
                    <div class="contract-detail-label">Deposit (RM)</div>
                    <div class="contract-detail-value">
                        {{ number_format($contract->deposit, 2) }}
                        @if ($contract->deposit < $contract->property->deposit)
                            <span style="color: red;">{{ number_format($contract->property->deposit - $contract->deposit, 2) }}</span>
                        @endif
                    </div>
                </div>
                <div class="contract-detail-row">
                    <div class="contract-detail-label">Monthly (RM)</div>
                    <div class="contract-detail-value">{{ number_format($contract->property->monthly, 2) }}</div>
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
                        @elseif ($contract->status == 3)
                            Terminated
                        @else
                            Voided
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <h3 class="text-center text-secondary p-3 mt-5">This tenant has no active contracts.</h3>
@endif

<script>
function changeTab(evt, paneId) {
    evt.preventDefault();
    var tabs = document.querySelectorAll('.tab-pane');
    var links = document.querySelectorAll('.nav-link');

    // Hide all tab contents and remove the 'active' class from all links
    tabs.forEach(tab => {
        tab.style.display = 'none';
        tab.classList.remove('active');
    });

    links.forEach(link => {
        link.classList.remove('active');
    });

    // Show the selected tab content and add 'active' class to the selected link
    document.getElementById(paneId).style.display = 'block';
    document.getElementById(paneId).classList.add('active');
    evt.currentTarget.classList.add('active');
}
</script>

@endsection
