@extends('layouts.landlord-layout')

@section('title', 'DreamHouse • Dashboard')

@section('content')
<section>
    <style type="text/css">
        .agent-details-container {
            color: #333;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 15px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin-top: 75px;
            overflow: hidden;
        }

        .agent-details-header {
            cursor: pointer;
            padding-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 15px;
        }

        .agent-details-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .agent-details-label {
            font-weight: 600;
            color: #022d6a;
            flex-basis: 30%;
        }

        .agent-details-value {
            background: #f8f9fa;
            padding: 6px 10px;
            border-radius: 5px;
            flex-basis: 65%;
        }

        .avatar-container {
            position: relative;
            width: 180px;
            height: 180px;
            margin-right: 15px;
        }

        .avatar-container img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
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

    <div class="agent-details-container">
        <div class="agent-details-header" id="agentDetailHeader">
            <h4>Profile</h4>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-start">
                <div class="avatar-container">
                    <img src="{{ asset(Auth::guard('landlord')->user()->avatar) }}" alt="Avatar">
                </div>
                <div class="container mt-3">
                    <div class="agent-details-row">
                        <div class="agent-details-label">Name</div>
                        <div class="agent-details-value">{{ Auth::guard('landlord')->user()->name }}</div>
                    </div>
                    <div class="agent-details-row">
                        <div class="agent-details-label">Email</div>
                        <div class="agent-details-value">{{ Auth::guard('landlord')->user()->email }}</div>
                    </div>
                    <div class="agent-details-row">
                        <div class="agent-details-label">I/C Number</div>
                        <div class="agent-details-value">{{ substr(Auth::guard('landlord')->user()->number_ic, 0, 6) }}-{{ substr(Auth::guard('landlord')->user()->number_ic, 6, 2) }}-{{ substr(Auth::guard('landlord')->user()->number_ic, 8) }}</div>
                    </div>
                    <div class="agent-details-row">
                        <div class="agent-details-label">Phone</div>
                        <div class="agent-details-value">{{ substr(Auth::guard('landlord')->user()->phone, 0, 3) }}-{{ substr(Auth::guard('landlord')->user()->phone, 3) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- <section>
    <div class="details-container">
        <div class="details-header" id="contractDetailHeader">
            <h4>Rental Information</h4>
        </div>
        <div class="card-body">
            <table class="details-table">
                <thead>
                    <tr>
                        <th>Deposit</th>
                        <th>Monthly</th>
                        <th>Total</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $contract->deposit }}</td>
                        <td>{{ $monthly }}</td>
                        <td>{{ $contract->total }}</td>
                        <td>{{ $contract->balance }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="details-container">
        <div class="details-header" id="complaintDetailHeader">
            <h4>Complaint</h4>
        </div>
        <div class="card-body" style="display: none;" id="complaintDetailBody">
            <table class="details-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Remark</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                    <tr>
                        <td>{{ $report->title }}</td>
                        <td>{{ $report->description }}</td>
                        <td>{{ $report->remark }}</td>
                        <td>{{ $report->status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        $("#complaintDetailHeader").click(function(){
            $("#complaintDetailBody").slideToggle();
        });
    });
</script> --}}

</section>

@endsection

