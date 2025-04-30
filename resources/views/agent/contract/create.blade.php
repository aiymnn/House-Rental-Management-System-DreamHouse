@extends('layouts.agent-layout')

@section('title', 'Contract • Create')

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
    <a href="{{ url('agent/contract') }}" class="btn float-end" style="background-color: #023d90; color: white;">Back</a>
    <div class="details-header" id="paymentDetailHeader">
        <h4>New Contract
        </h4>
    </div>
    <div class="card-body" id="paymentDetailBody">
        <form action="{{ route('contract_store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <style>
                /* Custom styling for property dropdown */
                .property-dropdown-container {
                    position: relative;
                    width: 100%; /* Ensure the container takes up 100% of the width */
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
                    appearance: none;
                    display: block;
                    position: relative;
                    text-align: left;
                    cursor: pointer;
                }

                /* Styling for the dropdown content */
                .property-dropdown-content {
                    display: none;
                    position: absolute;
                    top: 100%;
                    left: 0;
                    width: 100%; /* Match the width of the button */
                    border: 1px solid #ced4da;
                    background-color: #fff;
                    max-height: 200px;
                    overflow-y: auto;
                    z-index: 1;
                    border-radius: 0 0 0.25rem 0.25rem;
                }

                /* Styling for the search input inside the dropdown */
                .property-dropdown-content input {
                    width: 100%;
                    padding: 0.375rem;
                    border: none;
                    border-bottom: 1px solid #ced4da;
                    box-sizing: border-box;
                }

                /* Styling for the individual options */
                .property-dropdown-content a {
                    padding: 0.375rem;
                    display: block;
                    text-decoration: none;
                    color: #495057;
                    border-bottom: 1px solid #ced4da;
                    cursor: pointer;
                }

                .property-dropdown-content a:hover {
                    background-color: #f1f1f1;
                }

                /* Add an arrow icon to mimic the select dropdown */
                .property-dropdown-button::after {
                    content: '';
                    position: absolute;
                    top: 50%;
                    right: 0.75rem;
                    width: 0;
                    height: 0;
                    border-left: 0.3rem solid transparent;
                    border-right: 0.3rem solid transparent;
                    border-top: 0.3rem solid #495057;
                    transform: translateY(-50%);
                }
            </style>

            <div class="mb-3">
                <label for="p" class="form-label">Property Address</label>

                <!-- Custom Dropdown for Property -->
                <div class="property-dropdown-container">
                    @php
                        // Get the old selected property ID if there's a validation error
                        $selectedPropertyId = old('propertyid');
                        $selectedPropertyAddress = $properties->where('id', $selectedPropertyId)->pluck('address')->first() ?? '-Property-';
                    @endphp

                    <div class="property-dropdown-button" onclick="togglePropertyDropdown()">
                        {{ $selectedPropertyAddress }}
                    </div>

                    <!-- Dropdown menu with search input and options -->
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

                <!-- Hidden input to store the selected property ID -->
                <input type="hidden" name="propertyid" id="selectedPropertyId" value="{{ $selectedPropertyId }}">

                @error('propertyid') <span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <script>
                function togglePropertyDropdown() {
                    // Toggle the visibility of the dropdown content
                    var dropdown = document.getElementById('propertyDropdownContent');
                    if (dropdown.style.display === "none" || dropdown.style.display === "") {
                        dropdown.style.display = "block";
                    } else {
                        dropdown.style.display = "none";
                    }
                }

                function filterPropertyOptions() {
                    // Get the search input value
                    var input = document.getElementById('searchProperty').value.toLowerCase();

                    // Get all dropdown items (property addresses)
                    var items = document.querySelectorAll('#propertyOptions a');

                    // Loop through the items and show/hide based on the search input
                    items.forEach(function(item) {
                        var text = item.textContent.toLowerCase();
                        if (text.indexOf(input) > -1) {
                            item.style.display = "";
                        } else {
                            item.style.display = "none";
                        }
                    });
                }

                function selectProperty(id, address) {
                    // Set the selected property ID in the hidden input
                    document.getElementById('selectedPropertyId').value = id;

                    // Update the dropdown button text to show the selected address
                    document.querySelector('.property-dropdown-button').textContent = address;

                    // Hide the dropdown content after selection
                    document.getElementById('propertyDropdownContent').style.display = "none";
                }

                // Close the dropdown if the user clicks outside of it, but keep it open if interacting with the dropdown itself
                window.onclick = function(event) {
                    if (!event.target.matches('.property-dropdown-button') && !event.target.matches('#searchProperty')) {
                        var dropdowns = document.getElementsByClassName("property-dropdown-content");
                        for (var i = 0; i < dropdowns.length; i++) {
                            var openDropdown = dropdowns[i];
                            if (openDropdown.style.display === "block") {
                                openDropdown.style.display = "none";
                            }
                        }
                    }
                }
            </script>

            <style>
                /* Styling for the dropdown button to match the select element */
                .custom-dropdown-container {
                    position: relative;
                    width: 100%; /* Ensure the container takes up 100% of the width */
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
                    appearance: none;
                    display: block;
                    position: relative;
                    text-align: left;
                    cursor: pointer;
                }

                /* Styling for the dropdown content */
                .custom-dropdown-content {
                    display: none;
                    position: absolute;
                    top: 100%;
                    left: 0;
                    width: 100%; /* Match the width of the button */
                    border: 1px solid #ced4da;
                    background-color: #fff;
                    max-height: 200px;
                    overflow-y: auto;
                    z-index: 1;
                    border-radius: 0 0 0.25rem 0.25rem;
                }

                /* Styling for the search input inside the dropdown */
                .custom-dropdown-content input {
                    width: 100%;
                    padding: 0.375rem;
                    border: none;
                    border-bottom: 1px solid #ced4da;
                    box-sizing: border-box;
                }

                /* Styling for the individual options */
                .custom-dropdown-content a {
                    padding: 0.375rem;
                    display: block;
                    text-decoration: none;
                    color: #495057;
                    border-bottom: 1px solid #ced4da;
                    cursor: pointer;
                }

                .custom-dropdown-content a:hover {
                    background-color: #f1f1f1;
                }

                /* Add an arrow icon to mimic the select dropdown */
                .custom-dropdown-button::after {
                    content: '';
                    position: absolute;
                    top: 50%;
                    right: 0.75rem;
                    width: 0;
                    height: 0;
                    border-left: 0.3rem solid transparent;
                    border-right: 0.3rem solid transparent;
                    border-top: 0.3rem solid #495057;
                    transform: translateY(-50%);
                }
            </style>

            <div class="mb-3">
                <label for="t" class="form-label">Email of Tenant</label>

                <!-- Custom Dropdown -->
                <div class="custom-dropdown-container">
                    <div class="custom-dropdown-button" onclick="toggleDropdown()">
                        <!-- Display the selected email or a placeholder -->
                        {{ old('tenantid') ? $tenants->firstWhere('id', old('tenantid'))->email : '-Tenant Email-' }}
                    </div>

                    <!-- Dropdown menu with search input and options -->
                    <div class="custom-dropdown-content" id="dropdownContent">
                        <input type="text" id="searchTenant" placeholder="Search tenant email..." onkeyup="filterOptions()">
                        <div id="tenantOptions">
                            @foreach($tenants as $tenant)
                                <a href="#" onclick="selectTenant('{{ $tenant->id }}', '{{ $tenant->email }}')">
                                    {{ $tenant->email }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Hidden input to store the selected tenant ID -->
                <input type="hidden" name="tenantid" id="selectedTenantId" value="{{ old('tenantid') }}">

                @error('tenantid') <span class="text-danger">{{ $message }}</span>@enderror
            </div>



            <div class="mb-3">
                <label for="period" class="form-label">Period <span style="color: #c4c4c4">(month)</span></label>
                <input type="number" class="form-control" name="period" value="{{ old('period') }}">
                @error('period') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="mb-3">
                <label for="ic" class="form-label">Passport/IC Number of Tenant</label>
                <input type="text" class="form-control" id="icnumber" name="icnumber" value="{{ old('icnumber') }}">
                @error('icnumber') <span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <script>
                function toggleDropdown() {
                    var dropdown = document.getElementById('dropdownContent');
                    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
                }

                function filterOptions() {
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

                    // Fetch tenant info via AJAX
                    fetch(`/get-tenant-info/${id}`)
                        .then(response => response.json())
                        .then(data => {
                            var icnumberInput = document.getElementById('icnumber');
                            if (data.icnumber) {
                                // Auto-fill and disable the IC number field
                                icnumberInput.value = data.icnumber;
                                icnumberInput.setAttribute('readonly', true);
                            } else {
                                // Enable the IC number field for input
                                icnumberInput.value = '';
                                icnumberInput.removeAttribute('readonly');
                            }
                        })
                        .catch(error => console.error('Error fetching tenant info:', error));

                    // Close the dropdown
                    document.getElementById('dropdownContent').style.display = "none";
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
                <label for="start" class="form-label">Start of Contract</label>
                <input type="date" class="form-control" name="start" value="{{ old('start') }}">
                @error('start') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <br>
            <div class="form-section submit-button">
                <button type="submit" class="btn" style="background-color: #023d90; color: white;">Submit</button>
            </div>
        </form>
    </div>
</div>

@endsection
