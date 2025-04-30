@extends('layouts.agent-layout')

@section('title', 'Contract • Update')

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
    <a href="{{ url('agent/appointment') }}" class="btn float-end" style="background-color: #023d90; color: white;">Back</a>
    <div class="details-header" id="paymentDetailHeader">
        <h4>Update Appointment
        </h4>
    </div>
    <div class="card-body" id="paymentDetailBody">
        <form action="{{ url('agent/appointment/update/'.$appointments->id.'') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="p" class="form-label">Property Address</label>
                <select class="form-select" name="propertyid">
                    <option selected value="{{ $appointments->property->id }}">{{ $appointments->property->address }}</option>
                    @foreach($properties as $property)
                        @if($property->id != $appointments->property->id) <!-- Filter out the already selected property -->
                            <option value="{{ $property->id }}" {{ old('property') == $property->id ? 'selected' : '' }}>
                                {{ $property->address }}
                            </option>
                        @endif
                    @endforeach
                </select>
                @error('propertyid') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="mb-3">
                <label for="t" class="form-label">Guest of Tenant</label>
                <select class="form-select" name="tenantid">
                    <option selected value="{{ $appointments->tenant->id }}">{{ $appointments->tenant->email }}</option>
                    @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}" {{ old('tenant') == $tenant->id ? 'selected' : '' }}>
                            {{ $tenant->email }}
                        </option>
                    @endforeach
                </select>
                @error('tenantid') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date of Appointment</label>
                <input type="date" class="form-control" name="date" value="{{ $appointments->date }}">
                @error('date') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="mb-3">
                <label for="time" class="form-label">Time of Appointment</label>
                <?php
                // Assuming $appointments->time contains a time value in "H:i" format (e.g., "14:00")
                $time = date("H:i", strtotime($appointments->time)); // Convert to "H:i" format
                ?>
                <input type="time" class="form-control" name="time" value="{{ $time }}">
                @error('time') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <br>
            <div class="form-section submit-button">
                <button type="submit" class="btn" style="background-color: #023d90; color: white;">Submit</button>
            </div>
        </form>
    </div>
</div>

@endsection
