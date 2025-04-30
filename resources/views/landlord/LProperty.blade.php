@extends('layouts.landlord-layout')
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
        justify-content: space-between;
        align-items: center;
        flex-wrap: nowrap;
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
        align-items: center;
    }

    .checkbox-group label {
        margin-right: 10px;
        white-space: nowrap;
    }
</style>

<div class="notice" style="margin-top: 75px; overflow: hidden;">
    @include('landlord.property.success-message')
</div>

<div class="details-container">
    <div class="details-header">
        <h4>Your Property</h4>
        <div class="search-bar">
            <input type="text" id="searchInput" class="search-input" placeholder="Search by address">
            <div class="checkbox-group">
                <label><input type="checkbox" id="availableCheckbox"> Available</label>
                <label><input type="checkbox" id="unavailableCheckbox"> Unavailable</label>
            </div>
        </div>
        <a href="{{ route('landlord-create-property') }}" class="btn float-end" style="background-color: #023d90; color: white;">New Property</a>
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
                    <th style="text-align: center;">Address</th>
                    <th style="text-align: center; width: 150px;">Type</th>
                    <th style="width: 150px; text-align: center;">Deposit (RM)</th>
                    <th style="width: 150px; text-align: center;">Monthly (RM)</th>
                    <th style="text-align: center;">Description</th>
                    <th style="text-align: center;">Available</th>
                    <th style="text-align: center;">Status</th>
                    <th style="width: 150px; text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody id="propertyTableBody">
                @foreach ($properties as $property)
                <tr>
                    <td>{{$property->address}}</td>
                    <td style="text-align: center;">
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
                    <td style="text-align: center;">{{$property->deposit}}</td>
                    <td style="text-align: center;">{{$property->monthly}}</td>
                    <td>{{$property->description}}</td>
                    <td style="text-align: center;">
                        @if ($property->available == 1)
                            <span class="available-icon" style="color: green;">✔</span>
                            @else
                            <span class="unavailable-icon" style="color: red;">✘</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                    @if ($property->status == 3)
                        Pending
                        @elseif ($property->status == 1)
                        Approved
                        @elseif ($property->status == 4)
                        Incomplete
                        @else
                        Inapprove
                    @endif
                    </td>
                    <td style="text-align: center; white-space: nowrap;"> <!-- Prevent line breaks -->
                        <a href="{{ url('landlord/property/view/'.$property->id.'') }}" class="col" style="color:green; margin-right: 10px;" title="View"><span><i class="fa-solid fa-eye"></i></span></i></a>
                        <a href="{{ url('landlord/property/edit/'.$property->id.'') }}" class="col" style="color:blue; margin-right: 10px;" title="Edit"><span><i class="fa-solid fa-pen-to-square"></i></span></a>
                        <a href="{{ url('landlord/property/delete/'.$property->id.'') }}" class="col" style="color:red; margin-right: 10px;" title="Delete"
                            onclick="return confirm('Are you sure?')";
                            >
                            <span><i class="fa fa-trash" aria-hidden="true"></span></i>
                        </a>
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
        const availableCheckbox = document.getElementById('availableCheckbox');
        const unavailableCheckbox = document.getElementById('unavailableCheckbox');
        const tbody = document.getElementById('propertyTableBody');

        function filterProperties() {
            const searchText = searchInput.value.toLowerCase();
            const isAvailableChecked = availableCheckbox.checked;
            const isUnavailableChecked = unavailableCheckbox.checked;
            const noCheckboxChecked = !isAvailableChecked && !isUnavailableChecked;

            Array.from(tbody.children).forEach(row => {
                const address = row.cells[0].textContent.toLowerCase();
                const isAvailable = row.querySelector('.available-icon') !== null;
                const isUnavailable = row.querySelector('.unavailable-icon') !== null;

                const textMatch = address.includes(searchText);
                const statusMatch = (isAvailableChecked && isAvailable) ||
                                    (isUnavailableChecked && isUnavailable);

                // Show all rows if no checkboxes are checked, or filter by availability if any are checked
                row.style.display = (textMatch && (noCheckboxChecked || statusMatch)) ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', filterProperties);
        availableCheckbox.addEventListener('change', filterProperties);
        unavailableCheckbox.addEventListener('change', filterProperties);
    });
</script>

@endsection
