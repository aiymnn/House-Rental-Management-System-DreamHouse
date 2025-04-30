@extends('layouts.tenant-layout')

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
                    <th>Location</th>
                    <th>Agent</th>
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
                    <td>{{ $appointment->property->address }}</td>
                    <td>{{ $appointment->agent->name }}</td>
                    <td style="text-align: center;">{{ date('d-m-Y', strtotime($appointment->date)) }}</td>
                    <td style="text-align: center;">{{ $appointment->time }}</td>
                    <td>{{ $appointment->remark }}</td>
                    <td style="text-align: center;" class="status">{{ $appointment->status == 1 ? 'Proceed' : ($appointment->status == 2 ? 'Pending' : 'Cancelled') }}</td>
                    <td style="text-align: center; white-space: nowrap;"> <!-- Prevent line breaks -->
                        @if($appointment->status == 2)
                            <a href="{{ url('tenant/appointment/delete/'.$appointment->id.'') }}" class="col" style="color:red; margin-right: 10px;" title="Delete"
                                onclick="return confirm('Are you sure?')";>
                                <span><i class="fa fa-trash" aria-hidden="true"></span></i>
                            </a>
                        @else
                            <span class="col" style="color:gray; margin-right: 10px;" title="Delete Disabled">
                                <span><i class="fa fa-trash" aria-hidden="true"></span></i>
                            </span>
                        @endif
                    </td>
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
