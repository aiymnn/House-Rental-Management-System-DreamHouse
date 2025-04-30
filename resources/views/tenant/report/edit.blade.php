@extends('layouts.tenant-layout')

@section('title', 'Complaint • Update')

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
    <a href="{{ route('tenant_report') }}" class="btn float-end" style="background-color: #023d90; color: white;" >Back</a>
    <div class="details-header" id="paymentDetailHeader">
        <h4>Update Complaint
        </h4>
    </div>
    <div class="card-body" id="paymentDetailBody">
        <form action="{{ url('tenant/report/edit/'.$report->id.'') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Title</label>
                <select class="form-select" aria-describedby="emailHelp" aria-label="Default select example" name="title">
                    <option selected hidden value="{{ $vtitle }}">{{ $title}}</option>
                    <option value="1" {{ old('title') == '1' ? 'selected' : '' }}>Damage to the house</option>
                    <option value="2" {{ old('title') == '2' ? 'selected' : '' }}>Others</option>
                </select>
                @error('title') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" rows="2" aria-describedby="emailHelp" placeholder="Describe your problem here." name="description">{{ $description }}</textarea>
                @error('description') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="mb-3">
                <img src="{{ asset($image) }}" alt="" style="width: 200px; height: 200px;">
            </div>
            <div class="mb-3">
                <input type="file" name="image">
            </div>
            <br>
            <div class="form-section submit-button">
                <button type="submit" class="btn" style="background-color: #022d6a; color: white;">Submit</button>
            </div>
        </form>
    </div>
</div>


@endsection
