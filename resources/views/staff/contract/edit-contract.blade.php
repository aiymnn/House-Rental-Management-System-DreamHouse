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

    .disabled {
        pointer-events: none;
        opacity: 1.0;
    }
</style>

<div class="notice" style="overflow: hidden;">
    @include('landlord.property.success-message')
</div>

<div class="details-container">
    <a href="{{ url('staff/contract') }}" class="btn float-end" style="background-color: #17395e; color: white;">Back</a>
    <div class="details-header" id="paymentDetailHeader">
        <h4>Update Contract</h4>
    </div>
    <div class="card-body" id="paymentDetailBody">
        <form action="{{ url('staff/contract/edit/'.$contracts->id.'') }}" method="POST" enctype="multipart/form-data">
            @csrf

            @php
                // Check if the contract status is 1 (Active)
                $isActive = $contracts->status == 1;
            @endphp

            <style>
                .property-dropdown-container {
                    position: relative;
                    width: 100%;
                }

                .property-dropdown-button {
                    width: 100%;
                    padding: 0.375rem 2.25rem 0.375rem 0.75rem;
                    font-size: 1rem;
                    line-height: 1.5;
                    color: #495057;
                    background-color: #fff;
                    background-clip: padding-box;
                    border: 1px solid #ced4da;
                    border-radius: 0.25rem;
                    display: block;
                    position: relative;
                    text-align: left;
                    cursor: pointer;
                }

                .property-dropdown-content {
                    display: none;
                    position: absolute;
                    top: 100%;
                    left: 0;
                    width: 100%;
                    border: 1px solid #ced4da;
                    background-color: #fff;
                    max-height: 200px;
                    overflow-y: auto;
                    z-index: 1000;
                    border-radius: 0 0 0.25rem 0.25rem;
                }

                .property-dropdown-content input {
                    width: calc(100% - 0.75rem); /* Adjust width to align with other list items */
                    padding: 0.375rem;
                    margin: 0 0.375rem; /* Add horizontal margins */
                    border: none;
                    border-bottom: 1px solid #ced4da; /* Style bottom border to match items */
                }

                .property-dropdown-content a {
                    padding: 0.375rem;
                    display: block;
                    text-decoration: none;
                    color: #495057;
                    border-bottom: 1px solid #ced4da; /* Uniform border for consistency */
                    cursor: pointer;
                }

                .property-dropdown-content a:hover {
                    background-color: #f1f1f1;
                }

            </style>

            <div class="mb-3">
                <label for="p" class="form-label">Property Address</label>
                <div class="property-dropdown-container {{ $isActive ? 'disabled' : '' }}">
                    <div class="property-dropdown-button" onclick="{{ $isActive ? '' : 'togglePropertyDropdown()' }}">
                        {{ $selectedPropertyAddress ?? 'Select Property' }}
                    </div>
                    <div class="property-dropdown-content" id="propertyDropdownContent">
                        <input type="text" id="searchProperty" placeholder="Search property address..." onkeyup="filterPropertyOptions()">
                        <div id="propertyOptions">
                            @foreach($properties as $property)
                                <a href="#" onclick="selectProperty('{{ $property->id }}', '{{ $property->address }}')">
                                    {{ $property->address }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <input type="hidden" name="propertyid" id="selectedPropertyId" value="{{ $selectedPropertyId ?? '' }}" {{ $isActive ? 'disabled' : '' }}>
                @error('propertyid') <span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <script>
                function togglePropertyDropdown() {
                    let dropdown = document.getElementById('propertyDropdownContent');
                    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
                }

                function filterPropertyOptions() {
                    let input = document.getElementById('searchProperty').value.toLowerCase();
                    let options = document.querySelectorAll('#propertyOptions a');
                    options.forEach(option => {
                        let text = option.textContent.toLowerCase();
                        option.style.display = text.includes(input) ? 'block' : 'none';
                    });
                }

                function selectProperty(id, address) {
                    document.getElementById('selectedPropertyId').value = id;
                    document.querySelector('.property-dropdown-button').textContent = address;
                    document.getElementById('propertyDropdownContent').style.display = 'none';
                }

                window.onclick = function(event) {
                    if (!event.target.matches('.property-dropdown-button') && !event.target.matches('#searchProperty')) {
                        document.getElementById('propertyDropdownContent').style.display = 'none';
                    }
                }
            </script>

            <style>
                .custom-dropdown-container {
                    position: relative;
                    width: 100%;
                }
                .custom-dropdown-button {
                    width: 100%;
                    padding: 0.375rem 2.25rem 0.375rem 0.75rem;
                    font-size: 1rem;
                    line-height: 1.5;
                    color: #495057;
                    background-color: #fff;
                    background-clip: padding-box;
                    border: 1px solid #ced4da;
                    border-radius: 0.25rem;
                    display: block;
                    position: relative;
                    text-align: left;
                    cursor: pointer;
                }
                .custom-dropdown-content {
                    display: none;
                    position: absolute;
                    top: 100%;
                    left: 0;
                    width: 100%;
                    border: 1px solid #ced4da;
                    background-color: #fff;
                    max-height: 200px;
                    overflow-y: auto;
                    z-index: 1000;
                    border-radius: 0 0 0.25rem 0.25rem;
                }
                .custom-dropdown-content input {
                    width: 100%;
                    padding: 0.375rem;
                    border: none;
                    border-bottom: 1px solid #ced4da;
                    box-sizing: border-box;
                }
                .custom-dropdown-content a {
                    padding: 0.375rem;
                    display: block;
                    text-decoration: none;
                    color: #495057;
                    cursor: pointer;
                }
                .custom-dropdown-content a:hover {
                    background-color: #f1f1f1;
                }
            </style>

            <div class="mb-3">
                <label for="tenantEmail" class="form-label">Email of Tenant</label>
                <div class="custom-dropdown-container {{ $isActive ? 'disabled' : '' }}">
                    <div class="custom-dropdown-button" onclick="{{ $isActive ? '' : 'toggleTenantDropdown()' }}">
                        {{ $contracts->tenant->email ?? '-Select Tenant-' }}
                    </div>
                    <div class="custom-dropdown-content" id="tenantDropdownContent">
                        <input type="text" id="searchTenant" placeholder="Search tenant email..." onkeyup="filterTenantOptions()">
                        <div id="tenantOptions">
                            @foreach($tenants as $tenant)
                                <a href="#" onclick="selectTenant('{{ $tenant->id }}', '{{ $tenant->email }}')">
                                    {{ $tenant->email }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <input type="hidden" name="tenantid" id="selectedTenantId" value="{{ $contracts->tenant->id ?? '' }}" {{ $isActive ? 'disabled' : '' }}>
                @error('tenantid') <span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="mb-3">
                <label for="period" class="form-label">Period <span style="color: #c4c4c4">(month)</span></label>
                <input type="number" class="form-control" name="period" value="{{ $contracts->period }}" {{ $isActive ? 'disabled' : '' }}>
                @error('period') <span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="mb-3">
                <label for="ic" class="form-label">Passport/IC Number of Tenant</label>
                <input type="text" class="form-control" id="icnumber" name="icnumber" value="{{ $contracts->tenant->number_ic ?? '' }}" readonly {{ $isActive ? 'disabled' : '' }}>
                @error('icnumber') <span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="mb-3">
                <label for="start" class="form-label">Start of Contract</label>
                <input type="date" class="form-control" name="start" value="{{ $contracts->start_date }}" {{ $isActive ? 'disabled' : '' }}>
                @error('start') <span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <script>
                function toggleTenantDropdown() {
                    var dropdown = document.getElementById('tenantDropdownContent');
                    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
                }

                function filterTenantOptions() {
                    var input = document.getElementById('searchTenant').value.toLowerCase();
                    var items = document.querySelectorAll('#tenantOptions a');

                    items.forEach(function(item) {
                        var text = item.textContent.toLowerCase();
                        item.style.display = text.includes(input) ? "" : "none";
                    });
                }

                function selectTenant(id, email) {
                    document.getElementById('selectedTenantId').value = id;
                    document.querySelector('.custom-dropdown-button').textContent = email;

                    fetch(`/get-tenant-info/${id}`)
                        .then(response => response.json())
                        .then(data => {
                            var icnumberInput = document.getElementById('icnumber');
                            icnumberInput.value = data.icnumber ?? '';
                            icnumberInput.readOnly = !!data.icnumber;
                        })
                        .catch(error => console.error('Error fetching tenant info:', error));

                    document.getElementById('tenantDropdownContent').style.display = "none";
                }

                window.onclick = function(event) {
                    if (!event.target.matches('.custom-dropdown-button') && !event.target.matches('#searchTenant')) {
                        var dropdowns = document.getElementsByClassName("custom-dropdown-content");
                        for (var i = 0; i < dropdowns.length; i++) {
                            var openDropdown = dropdowns[i];
                            if (openDropdown.style.display === "block") {
                                openDropdown.style.display = "none";
                            }
                        }
                    }
                }
            </script>

            <div class="mb-3">
                <label for="t" class="form-label">Status</label>
                <select class="form-select" name="stat">
                    <option selected value="{{ $contracts->status }}">
                        @if ($contracts->status == 1)
                            Active
                        @elseif($contracts->status == 2)
                            Pending
                        @elseif($contracts->status == 3)
                            Terminated
                        @else
                            Voided
                        @endif
                    </option>
                    @if ($contracts->status == 1)
                        <option value="3" {{ old('status') == 3 ? 'selected' : '' }}>Terminated</option>
                        <option value="4" {{ old('status') == 4 ? 'selected' : '' }}>Voided</option>
                    @elseif($contracts->status == 2)
                        <option value="3" {{ old('status') == 3 ? 'selected' : '' }}>Terminated</option>
                        <option value="4" {{ old('status') == 4 ? 'selected' : '' }}>Voided</option>
                    @endif
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
