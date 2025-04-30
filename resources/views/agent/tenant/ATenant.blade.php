@extends('layouts.agent-layout')

@section('title', 'DreamHouse • Tenant')

@section('content')


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
    {{-- <a href="{{ route('landlord-create-property') }}" class="btn float-end" style="background-color: #023d90; color: white;">New Property</a> --}}
    <div class="details-header" id="paymentDetailHeader">
        <h4>Tenant List
        </h4>
    </div>
    <div class="card-body" id="paymentDetailBody">
        @if($tenants->isEmpty())
            <div class="text-center p-3">
                <h3 class="text-secondary">You don't have any tennats yet.</h3>
            </div>
        @else
        <table class="details-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>IC Number</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Contract</th>
                    <th>Status</th>
                    <th>Registered</th>
                    <th>Action</th>
                    {{-- <th style="width: 150px;">Action</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($tenants as $tenant)
                <tr>
                    <td style="text-align: center">{{$tenant->name}}</td>
                    <td style="text-align: center">{{ substr($tenant->number_ic, 0, 6) }}-{{ substr($tenant->number_ic, 6, 2) }}-{{ substr($tenant->number_ic, 8) }}</td>
                    <td style="text-align: center">{{ substr($tenant->phone, 0, 3) }}-{{ substr($tenant->phone, 3) }}</td>
                    <td style="text-align: center">{{$tenant->email}}</td>
                    <td style="text-align: center">
                        @foreach ($contracts[$tenant->id] as $contract)
                            @if ($contract->status == 1)
                                Active
                            @elseif ($contract->status == 2)
                                Pending
                            @else
                                Terminated
                            @endif
                        @endforeach
                    </td>

                    <!-- Display tenant status -->
                    <td style="text-align: center">
                        @if ($tenant->status == 1)
                            <a href="#" class="btn btn-success">Online</a>
                        @else
                            <a href="#" class="btn btn-danger">Offline</a>
                        @endif
                    </td>

                    <td style="text-align: center">{{ \Carbon\Carbon::parse($tenant->created_at)->format('d-m-Y') }}</td>
                    <td style="text-align: center">
                        <a href="{{ url('agent/tenant/view/'.$tenant->id.'') }}" class="col" style="color:green; margin-right: 10px;" title="View"><span><i class="fa-solid fa-eye"></i></span></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>


@endsection
