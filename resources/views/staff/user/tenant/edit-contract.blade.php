@extends('layouts.staff-layout')

@section('title', 'Tenant • Contract')

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

<div class="notice" style="overflow: hidden;">
    @include('landlord.property.success-message')
</div>

<div class="details-container">
    <a href="#" onclick="event.preventDefault(); window.history.back();" class="btn float-end" style="background-color: #17395e; color: white;">Back</a>
    <div class="details-header" id="paymentDetailHeader">
        <h4>Update Contract
        </h4>
    </div>
    <div class="card-body" id="paymentDetailBody">
        <form action="{{ url('staff/user/tenant/contract/edit/'.$contracts->id.'') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="p" class="form-label">Property Address</label>
                <select class="form-select" name="propertyid">
                    <option selected value="{{ $contracts->property->id }}">{{ $contracts->property->address }}</option>
                    @foreach($properties as $property)
                        <option value="{{ $property->id }}" {{ old('property') == $property->id ? 'selected' : '' }}>
                            {{ $property->address }}
                        </option>
                    @endforeach
                </select>
                @error('propertyid') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="mb-3">
                <label for="t" class="form-label">Email of Tenant</label>
                <select class="form-select" name="tenantid">
                    <option selected value="{{ $contracts->tenant->id }}">{{ $contracts->tenant->email }}</option>
                    @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}" {{ old('tenant') == $tenant->id ? 'selected' : '' }}>
                            {{ $tenant->email }}
                        </option>
                    @endforeach
                </select>
                @error('tenantid') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="mb-3">
                <label for="period" class="form-label">Period <span style="color: #c4c4c4">(month)</span></label>
                <input type="number" class="form-control" name="period" value="{{ $contracts->period }}">
                @error('period') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="mb-3">
                <label for="ic" class="form-label">Passport/IC Number of Tenant</label>
                <input type="text" class="form-control" name="icnumber" value="{{ $contracts->tenant->number_ic }}">
                @error('icnumber') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="mb-3">
                <label for="start" class="form-label">Start of Contract</label>
                <input type="date" class="form-control" name="start" value="{{ $contracts->start_date }}">
                @error('start') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="mb-3">
                <label for="t" class="form-label">Status</label>
                <select class="form-select" name="stat">
                    <option selected value="{{ $contracts->status }}">@if ($contracts->status == 1)
                        Active
                    @else
                        Pending
                    @endif
                    </option>
                    <option value="3" {{ old('status') == 3 ? 'selected' : '' }}>Terminated</option>
                    <option value="3" {{ old('status') == 4 ? 'selected' : '' }}>Voided</option>
                </select>
                @error('stat') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <br>
            <div class="form-section submit-button">
                <button type="submit" class="btn" style="background-color: #17395e; color: white;">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection
