@extends('layouts.agent-layout')

@section('title', 'DreamHouse • Appointment')

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
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: nowrap;  /* Ensures everything stays in one line */
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

    .search-bar {
        display: flex;
        align-items: center;
        flex-grow: 1;
        justify-content: flex-end;
    }

    .search-input {
        width: 200px;  /* Adjusted width */
        padding: 5px;
        margin-right: 10px;
    }

    .checkbox-group {
        display: flex;
        align-items: center; /* Align checkboxes vertically */
    }

    .checkbox-group label {
        margin-right: 10px; /* Space between checkboxes */
        white-space: nowrap; /* Ensures text doesn't wrap */
    }


</style>

<div class="notice" style="margin-top: 75px; overflow: hidden;">
    @include('landlord.property.success-message')
</div>

<div class="details-container">
    <div class="details-header">
        <h4>Your Appointments</h4>
        <div class="search-bar">
            <input type="text" id="searchInput" class="search-input" placeholder="Search by tenant and address" style="margin-left: 10px;">

            <div class="checkbox-group">
                <label><input type="checkbox" id="pendingCheckbox"> Pending</label>
                <label><input type="checkbox" id="proceedCheckbox"> Proceed</label>
                <label><input type="checkbox" id="cancelledCheckbox"> Cancelled</label>
            </div>
        </div>
        {{-- <a href="{{ url('agent/appointment/create') }}" class="btn float-end" style="background-color: #023d90; color: white;">New Appointment</a> --}}
    </div>
    <div class="card-body" id="paymentDetailBody">
        @if($appointments->isEmpty())
            <div class="text-center p-3">
                <h3 class="text-secondary">You don't have any appointments yet.</h3>
            </div>
        @else
        <table class="details-table" style="width: 100%;">
            <thead>
                <tr>
                    <th>Guest</th>
                    <th>Location</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Remark</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($appointments as $appointment)
                <tr>
                    <td style="text-align: center;">{{ $appointment->tenant->name }}</td>
                    <td>{{ $appointment->property->address }}</td>
                    <td style="text-align: center;">{{ date('d-m-Y', strtotime($appointment->date)) }}</td>
                    <td style="text-align: center;">{{ $appointment->time }}</td>
                    <td>{{ $appointment->remark }}</td>
                    <td style="text-align: center;" class="status">{{ $appointment->status == 1 ? 'Proceed' : ($appointment->status == 2 ? 'Pending' : 'Cancelled') }}</td>
                    <td style="text-align: center; white-space: nowrap;"> <!-- Prevent line breaks -->
                                <a href="#" class="updateButton" data-appointment-id="{{ $appointment->id }}" style="color:rgb(0, 128, 23); margin-right: 10px;" title="Update">
                                    <span><i class="fa fa-reply" aria-hidden="true"></i></span>
                                </a>
                                @if($appointment->status == 2)
                                    {{-- <a href="{{ url('agent/appointment/update/'.$appointment->id.'') }}" class="col" style="color:blue; margin-right: 10px;" title="Edit"><span><i class="fa-solid fa-pen-to-square"></i></span></a> --}}
                                    <a href="{{ url('agent/appointment/delete/'.$appointment->id.'') }}" class="col" style="color:red; margin-right: 10px;" title="Delete"
                                        onclick="return confirm('Are you sure?')";>
                                        <span><i class="fa fa-trash" aria-hidden="true"></span></i>
                                    </a>
                                @else
                                    {{-- <span class="col" style="color:gray; margin-right: 10px;" title="Edit Disabled">
                                        <span><i class="fa-solid fa-pen-to-square"></i></span>
                                    </span> --}}
                                    <span class="col" style="color:gray; margin-right: 10px;" title="Delete Disabled">
                                        <span><i class="fa fa-trash" aria-hidden="true"></span></i>
                                    </span>
                                @endif
                            </td>
                            <!-- Modal -->
                            @foreach ($appointments as $appointment)
                            <div id="updateModal{{ $appointment->id }}" class="modal">
                                <div class="modal-content">
                                    <span class="close">&times;</span>
                                    <h2 style="text-align: center;">Update Appointment</h2>
                                    <form class="updateForm" action="{{ url('agent/appointment/reply')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                                        <div>
                                            <label for="status">Status:</label>
                                            <div style="text-align: center; margin-bottom: 10px;">
                                                <button type="button" class="status-btn btn btn-success" data-value="1">Proceed</button>
                                                <button type="button" class="status-btn btn btn-danger" data-value="3">Cancelled</button>
                                                <input type="hidden" name="status" class="status-input">
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label for="remark">Remark:</label>
                                            <textarea name="remark" rows="2" placeholder="Enter remarks..."></textarea>
                                        </div>
                                        <button type="submit" style="background-color: #023d90; color: white;">Update</button>
                                    </form>
                                </div>
                            </div>
                            @endforeach

                            <style>
                                /* CSS styles */

                                /* The Modal (background) */
                                .modal {
                                    display: none; /* Hidden by default */
                                    position: fixed; /* Stay in place */
                                    z-index: 1; /* Sit on top */
                                    left: 0;
                                    top: 0;
                                    width: 100%; /* Full width */
                                    height: 100%; /* Full height */
                                    overflow: auto; /* Enable scroll if needed */
                                    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
                                }

                                /* Modal Content/Box */
                                .modal-content {
                                    background-color: #fefefe;
                                    margin: 10% auto; /* 10% from the top and centered */
                                    padding: 20px;
                                    border-radius: 5px;
                                    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
                                    width: 60%; /* Set the width of the modal */
                                    max-width: 400px; /* Limit the max width */
                                }

                                /* The Close Button */
                                .close {
                                    color: #aaa;
                                    float: right;
                                    font-size: 28px;
                                    font-weight: bold;
                                }


                                form div {
                                    margin-bottom: 10px;
                                }

                                label {
                                    display: block;
                                    font-weight: bold;
                                    margin-bottom: 5px;
                                }

                                input[type="text"],
                                textarea {
                                    width: calc(100% - 20px);
                                    padding: 10px;
                                    border: 1px solid #ccc;
                                    border-radius: 4px;
                                    box-sizing: border-box;
                                    resize: vertical;
                                }

                                button[type="submit"] {
                                    padding: 10px 20px;
                                    border: none;
                                    border-radius: 4px;
                                    cursor: pointer;
                                    transition: background-color 0.3s;
                                    display: block;
                                    margin: 0 auto;
                                }

                                button[type="submit"]:hover {
                                    background-color: #45a049;
                                }
                            </style>

                            <script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    var modalTriggers = document.querySelectorAll('.updateButton');
                                    modalTriggers.forEach(function(trigger) {
                                        trigger.addEventListener('click', function(event) {
                                            event.preventDefault();
                                            var appointmentId = this.getAttribute('data-appointment-id');
                                            var modal = document.getElementById("updateModal" + appointmentId);
                                            if (modal) {
                                                modal.style.display = "block";
                                            }
                                        });
                                    });

                                    var modals = document.querySelectorAll('.modal');
                                    modals.forEach(function(modal) {
                                        var closeButton = modal.querySelector('.close');
                                        if (closeButton) {
                                            closeButton.addEventListener('click', function() {
                                                modal.style.display = "none";
                                            });
                                        }
                                    });

                                    var statusButtons = document.querySelectorAll('.status-btn');
                                    statusButtons.forEach(function(button) {
                                        button.addEventListener('click', function() {
                                            var statusInput = this.parentElement.querySelector('.status-input');
                                            if (statusInput) {
                                                statusInput.value = this.getAttribute('data-value');
                                            }
                                        });
                                    });
                                });
                                </script>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const pendingCheckbox = document.getElementById('pendingCheckbox');
    const proceedCheckbox = document.getElementById('proceedCheckbox');
    const cancelledCheckbox = document.getElementById('cancelledCheckbox');
    const tbody = document.querySelector('.details-table tbody');

    function filterAppointments() {
        const searchText = searchInput.value.toLowerCase();
        const isPendingChecked = pendingCheckbox.checked;
        const isProceedChecked = proceedCheckbox.checked;
        const isCancelledChecked = cancelledCheckbox.checked;
        const noCheckboxChecked = !isPendingChecked && !isProceedChecked && !isCancelledChecked;

        Array.from(tbody.children).forEach(row => {
            const textContent = row.textContent.toLowerCase();
            const status = row.querySelector('.status').textContent.toLowerCase();

            const textMatch = textContent.includes(searchText);
            const statusMatch = (isPendingChecked && status === 'pending') ||
                                (isProceedChecked && status === 'proceed') ||
                                (isCancelledChecked && status === 'cancelled');

            row.style.display = (textMatch && (noCheckboxChecked || statusMatch)) ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterAppointments);
    pendingCheckbox.addEventListener('change', filterAppointments);
    proceedCheckbox.addEventListener('change', filterAppointments);
    cancelledCheckbox.addEventListener('change', filterAppointments);
});
</script>

@endsection
