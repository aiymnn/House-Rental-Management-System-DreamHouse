@extends('layouts.agent-layout')

@section('title', 'DreamHouse • Property')

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
        justify-content: space-between; /* Ensures the title and filters are spaced out */
        align-items: center;
    }

    .filter-section {
        display: flex;
        align-items: center;
    }

    .filter-section > * {
        margin-right: 15px; /* Space between filters */
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const availableCheckbox = document.getElementById('availableCheckbox');
        const unavailableCheckbox = document.getElementById('unavailableCheckbox');
        const rows = document.querySelectorAll('.details-table tbody tr');

        function filterRows() {
            const searchText = searchInput.value.toLowerCase();
            const availableChecked = availableCheckbox.checked;
            const unavailableChecked = unavailableCheckbox.checked;

            rows.forEach(row => {
                const landlordText = row.querySelector('.landlord').textContent.toLowerCase();
                const addressText = row.querySelector('.address').textContent.toLowerCase();
                const available = row.querySelector('.available').textContent.includes('✔');

                const textMatch = landlordText.includes(searchText) || addressText.includes(searchText);
                let availableMatch = false;

                if (availableChecked && unavailableChecked) {
                    availableMatch = true;
                } else if (availableChecked) {
                    availableMatch = available;
                } else if (unavailableChecked) {
                    availableMatch = !available;
                } else {
                    availableMatch = true;
                }

                if (textMatch && availableMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        searchInput.addEventListener('input', filterRows);
        availableCheckbox.addEventListener('change', filterRows);
        unavailableCheckbox.addEventListener('change', filterRows);
    });
</script>

<div class="notice" style="margin-top: 75px; overflow: hidden;">
    @include('landlord.property.success-message')
</div>

<div class="details-container">
    <div class="details-header">
        <h4>Property List</h4>
        <div class="filter-section">
            <input type="text" id="searchInput" placeholder="Search by Landlord or Address">
            <label><input type="checkbox" id="availableCheckbox"> Available</label>
            <label><input type="checkbox" id="unavailableCheckbox"> Unavailable</label>
        </div>
    </div>
    <div class="card-body" id="paymentDetailBody">
        @if($properties->isEmpty())
            <div class="text-center p-3">
                <h3 class="text-secondary">You don't have any properties yet.</h3>
            </div>
        @else
        <table class="details-table">
            <thead>
                <tr>
                    <th>Landlord</th>
                    <th>Address</th>
                    <th>Type</th>
                    <th>Deposit (RM)</th>
                    <th>Monthly (RM)</th>
                    <th>Description</th>
                    <th>Available</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($properties as $property)
                <tr>
                    <td class="landlord" style="text-align: center">{{ $property->landlord->name }}</td>
                    <td class="address">{{$property->address}}</td>
                    <td  style="text-align: center">
                    @if ($property->type == 1)
                    Bungalow/Villa
                    @elseif ($property->type == 2)
                    Semid-D
                    @elseif ($property->type == 3)
                    Terrace
                    @elseif ($property->type == 4)
                    Townhouse
                    @elseif ($property->type == 5)
                    Flat/Apartment
                    @elseif ($property->type == 6)
                    Condominium
                    @elseif ($property->type == 7)
                    Penthouse
                    @else
                    Shop House
                    @endif
                    </td>
                    <td style="text-align: center">{{$property->deposit}}</td>
                    <td style="text-align: center">{{$property->monthly}}</td>
                    <td>{{$property->description}}</td>
                    <td class="available" style="text-align: center">
                        @if ($property->available == 1)
                        <span style="color: green;">✔</span>
                        @else
                        <span style="color: red;">✘</span>
                        @endif
                    </td>
                    <td>
                    @if ($property->status == 3)
                    Approval
                    @elseif ($property->status == 1)
                    Active
                    @elseif ($property->status == 2)
                    Inactive
                    @else
                    Incomplete
                    @endif
                    </td>
                    <td style="text-align: center">
                        <a href="{{ url('agent/property/view/'.$property->id.'') }}" class="col" style="color:green; margin-right: 10px;" title="View"><span><i class="fa-solid fa-eye"></i></span></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>

@endsection
