@extends('layouts.tenant-layout')

@section('title', 'DreamHouse • Payment')

@section('content')
<style>
    .notice {
        margin-bottom: 20px;
    }

    .nav-tabs {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
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

    .contract-details-container {
        color: #333;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        padding: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .contract-details-header {
        cursor: pointer;
        padding-bottom: 15px;
        border-bottom: 2px solid #dee2e6;
        margin-bottom: 20px;
    }

    .contract-details-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .contract-details-label {
        font-weight: 600;
        color: #022d6a;
        flex-basis: 30%;
    }

    .contract-details-value {
        background: #f8f9fa;
        padding: 8px 12px;
        border-radius: 5px;
        flex-basis: 65%;
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

<div class="notice">
    @include('landlord.property.success-message')
</div>

<div class="nav-tabs">
    @foreach ($contracts as $index => $contract)
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
    @foreach ($contracts as $index => $contract)
        <div id="contract-pane-{{ $contract->id }}" class="tab-pane @if($index == 0) active @endif">
            <div class="contract-details-container">
                <div class="contract-details-header" id="contractDetailHeader">
                    <h4>Rental Information</h4>
                </div>
                <div class="card-body">
                    <div class="contract-details-row">
                        <div class="contract-details-label">Deposit</div>
                        <div class="contract-details-value">{{ number_format($contract->deposit, 2) }}</div>
                    </div>
                    <div class="contract-details-row">
                        <div class="contract-details-label">Monthly</div>
                        <div class="contract-details-value">{{ number_format($contract->property->monthly, 2) }}</div>
                    </div>
                    <div class="contract-details-row">
                        <div class="contract-details-label">Total</div>
                        <div class="contract-details-value">{{ number_format($contract->total, 2) }}</div>
                    </div>
                    <div class="contract-details-row">
                        <div class="contract-details-label">Balance</div>
                        <div class="contract-details-value">{{ number_format($contract->balance, 2) }}</div>
                    </div>
                </div>
            </div>

            <div class="details-container">
                @if ($contract->balance != 0)
                    <a href="{{ url('tenant/payment/new/'.$contract->id) }}" class="btn float-end" style="background-color: #023d90; color: white;">New Payment</a>
                @endif
                <div class="details-header" onclick="toggleDetails('payment-detail-{{ $contract->id }}')">
                    <h4>Payment History
                    </h4>
                </div>
                {{-- <div class="details-header" onclick="toggleDetails('payment-detail-{{ $contract->id }}')">Payment History</div> --}}
                <div id="payment-detail-{{ $contract->id }}" class="card-body" style="display: none;">
                    @if (empty($paymentsGroupedByContract[$contract->id]) || $paymentsGroupedByContract[$contract->id]->isEmpty())
                        <h4 style="color: #495057; text-align: center;">You don't have any transaction yet.</h4>
                    @else
                        <table class="details-table">
                            <thead>
                                <tr>
                                    <th>Payment Type</th>
                                    <th>Amount Paid</th>
                                    <th>Payment Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($paymentsGroupedByContract[$contract->id] as $payment)
                                    <tr>
                                        <td>{{ $payment->type == 1 ? 'Deposit' : 'Monthly Rent' }}</td>
                                        <td>{{ number_format($payment->total, 2) }}</td>
                                        <td>{{ $payment->created_at->format('d-m-Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

        </div>
    @endforeach
</div>

<script>
function changeTab(evt, paneId) {
    evt.preventDefault();
    var tabs = document.querySelectorAll('.tab-pane');
    var links = document.querySelectorAll('.nav-link');
    tabs.forEach(tab => tab.style.display = 'none');
    links.forEach(link => link.classList.remove('active'));
    document.getElementById(paneId).style.display = 'block';
    evt.currentTarget.classList.add('active');
}

function toggleDetails(detailId) {
    var element = document.getElementById(detailId);
    element.style.display = (element.style.display === 'none' ? 'block' : 'none');
}
</script>

@endsection
