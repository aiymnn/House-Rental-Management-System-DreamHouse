@extends('layouts.tenant-layout')

@section('title', 'Payment • Complaint')

@section('content')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0" />

<style>
    .details-container {
        color: #333;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        padding: 15px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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


<div class="notice" style="margin-top: 75px; overflow: hidden;">
    @include('landlord.property.success-message')
</div>

<div class="details-container">
    <a href="{{ url('tenant/report/create') }}" class="btn float-end" style="background-color: #023d90; color: white">New Complaint</a>
    <div class="details-header" id="paymentDetailHeader">
        <h4>Your Complaint
        </h4>
    </div>
    <div class="card-body" id="paymentDetailBody">
        @if($reports->isEmpty())
            <div class="text-center p-3">
                <h3 class="text-secondary">You don't have any complaint history.</h3>
            </div>
        @else
        <table class="details-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Remark</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $report)
                <tr>
                    <td>
                        {{ $report->title == 1 ? 'Damage to the house' : 'Others' }}
                    </td>
                    <td>{{ $report->description }}</td>
                    <td>
                        {{ $report->remark ?: 'No remark yet' }}
                    </td>
                    <td>
                        @if ($report->status == 1)
                            Accepted
                        @elseif ($report->status == 2)
                            Pending
                        @else
                            Rejected
                        @endif
                    </td>
                    <td>
                        @if($report->status == 1)
                            <span style="color:gray; cursor: default;" title="No actions available">
                                <i class="fa fa-ban" aria-hidden="true"></i>
                            </span>
                        @else
                            <a href="{{ url('tenant/report/edit/'.$report->id) }}" style="color:blue; margin-right: 10px;" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="{{ url('tenant/report/delete/'.$report->id) }}" style="color:red; margin-right: 10px;" title="Delete" onclick="return confirm('Are you sure?');">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>




@endsection
