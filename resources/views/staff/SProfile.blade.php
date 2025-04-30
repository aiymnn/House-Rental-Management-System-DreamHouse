@extends('layouts.staff-layout')

@section('title', 'DreamHouse • Profile')

@section('content')
<style>
    /* Custom CSS to center and adjust the size of the circular image */
    .circle-image {
        width: 200px; /* Adjust the width as needed */
        height: 200px; /* Adjust the height as needed */
        object-fit: cover; /* Maintain aspect ratio */
        display: block; /* Ensure block-level display */
        margin: 0 auto; /* Center horizontally */
    }

    .avatar-container {
        position: relative;
    }

    .edit-icon {
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        background-color: white;
        border-radius: 50%;
        padding: 5px;
        cursor: pointer;
        transition: transform 0.3s, color 0.3s; /* Add transition effects */
    }

    .edit-icon:hover {
        transform: translateX(-50%) scale(1.1); /* Enlarge the edit icon on hover */
        color: red; /* Change the color on hover */
    }

    .details-container {
        color: #333;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        padding: 15px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        /* margin-top: 15px; */
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
    <div class="details-header" id="paymentDetailHeader">
        <h4>Profile Configuration
        </h4>
    </div>
    <div class="card-body" id="paymentDetailBody">
        <div class="container text-center mb-3">
            <form id="avatar-form" action="{{ url('staff/profile/avatar/'.Auth::guard('staff')->user()->id.'') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <h1 class="mb-3">{{ Auth::guard('staff')->user()->name }}</h1>
                @if(Auth::guard('staff')->check())
                <div class="avatar-container position-relative">
                    <label for="avatar-upload" class="edit-icon"><i class="fas fa-pencil-alt"></i></label>
                    <img src="{{ asset(Auth::guard('staff')->user()->avatar) }}" alt="Avatar" class="rounded-circle circle-image mb-3" id="avatar-image">
                    <input type="file" id="avatar-upload" name="avatar" style="display: none;" onchange="displaySelectedImage(event)">
                </div>
                <div>
                    @error('avatar') <span class="text-danger">{{ $message }}</span>@enderror
                </div>
                @endif
                <button type="submit" class="btn" style="background-color: #023d90; color: white;">Update Avatar</button>
            </form>
          </div>
          <div class="container mb-3">
            <br>
            <h4>Your Profile</h4>
            <hr>
            <form action="{{ url('staff/profile/bio/'.Auth::guard('staff')->user()->id.'')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" aria-describedby="emailHelp" name="name" value="{{ Auth::guard('staff')->user()->name }}">
                    @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="number" class="form-control" aria-describedby="emailHelp" name="phone" value="{{ Auth::guard('staff')->user()->phone }}">
                    @error('phone') <span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">I/C Number</label>
                    <input type="number" class="form-control" aria-describedby="emailHelp" name="icnumber" value="{{ Auth::guard('staff')->user()->number_ic }}">
                    @error('icnumber') <span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" aria-describedby="emailHelp" name="email" value="{{ Auth::guard('staff')->user()->email }}">
                    @error('email') <span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn" style="background-color: #023d90; color: white;">Update</button>
                </div>
              </form>
          </div>
          <br>
          <div class="container mb-3">
            <br>
            <h4>Update Password</h4>
            <hr>
            <form action="{{ url('staff/profile/password/'.Auth::guard('staff')->user()->id.'')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" class="form-control" name="newpassword">
                    @error('newpassword') <span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirmation Password</label>
                    <input type="password" class="form-control" name="newpassword_confirmation">
                    @error('newpassword') <span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn" style="background-color: #17395e; color: white;">Update</button>
                </div>
          </div>
        </div>
    </div>

    <script>
        function displaySelectedImage(event) {
            var selectedFile = event.target.files[0];
            var imageTag = document.getElementById('avatar-image');
            imageTag.src = URL.createObjectURL(selectedFile);
        }
    </script>
@endsection
