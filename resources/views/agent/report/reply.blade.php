@extends('layouts.agent-layout')

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
    <a href="{{ url('agent/report') }}" class="btn float-end" style="background-color: #023d90; color: white;" >Back</a>
    <div class="details-header" id="paymentDetailHeader">
        <h4>Reply Complaint
        </h4>
    </div>
    <div class="card-body" id="paymentDetailBody">
        <form action="{{ url('agent/report/reply/'.$reports->id.'') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Title</label>
                @php
                    $title = '';
                    if (isset($reports->title) && $reports->title == 1) {
                        $title = "Damage to the house";
                    } else {
                        $title = "Others";
                    }
                @endphp
                <input type="text" class="form-control" aria-describedby="emailHelp" name="email" value="{{ $title }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" rows="2" aria-describedby="emailHelp" placeholder="Describe your problem here." name="description" readonly>{{ $reports->description }}</textarea>
            </div>
            <div class="mb-3">
                <img src="{{ asset($reports->image) }}" alt="" style="width: 300px; height: 300px;">
            </div>
            <br>
            <div class="mb-3">
                <label class="form-label">Remark</label>
                <textarea class="form-control" rows="2" aria-describedby="emailHelp" placeholder="Remark this report to inform tenant." name="remark">{{ $reports->remark }}</textarea>
                @error('remark') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="form-section submit-button">
                <button type="submit" class="btn" style="background-color: #023d90; color: white;">Remark</button>
            </div>
        </form>
    </div>
</div>


@endsection
