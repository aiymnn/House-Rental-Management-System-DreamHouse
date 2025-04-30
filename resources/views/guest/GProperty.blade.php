@extends('layouts.guest-layout')

@section('title', 'DreamHouse • Property')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if($errors->has('date'))
    <div class="alert alert-danger">
        {{ $errors->first('date') }}
    </div>
@endif

<style>
    .container {
        max-width: 600px;
        margin: 20px auto;
        border: 1px solid #ccc;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }

    .property{
        max-width: 600px;
        margin: 20px auto;
        border: 1px solid #ccc;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        height: 665px;
    }

    .carousel {
    width: 100%;
    height: 350px;
    overflow: hidden;
    border-bottom: 1px solid #ccc;
    margin: 0; /* Remove any default margin */
    padding: 0; /* Remove any default padding */
}

.carousel-content {
    width: 100%; /* Ensure the content fills the entire width of the carousel */
    height: 100%; /* Ensure the content fills the entire height of the carousel */
}

.carousel-content img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Ensure the image covers the entire container */
    margin: 0; /* Remove any default margins */
    padding: 0; /* Remove any default padding */
}

    .property-details {
        width: 100%;
        height: 300px;
        padding: 20px;
        box-sizing: border-box;
    }

    /* Style your carousel and property details content as needed */

    .carousel-content {
        /* Styles for carousel content */
    }

    .details-content {
        font-size: 13px;
    }


    .loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(128, 128, 128, 0.7); /* Semi-transparent grey background */
    z-index: 9999; /* Ensure it's on top of everything */
    display: flex;
    justify-content: center;
    align-items: center;
}

.loading-spinner {
    border: 5px solid #f3f3f3; /* Light grey */
    border-top: 5px solid #808080; /* Dark grey */
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 2s linear infinite; /* Spin animation */
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}


.info-prop .headline {
    width: 40%;
}

.extra p{
        font-size: 12px;
        display: inline-block; /* Display the div as an inline block */
        margin-right: 12px; /* Add margin to create a gap between elements */
        color: #338c31;
        border: 1px solid #338c31;
        border-radius: 10px;
        padding: 5px;
    }

    .modal-body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .modal-body .btn {
            margin: 5px 0; /* Adjust margin as needed */
            width: 400px; /* Set the width of the buttons */
            height: 50px; /* Set the height of the buttons */
            border: 2px solid #0d6efd; /* Add border with desired color */
            border-radius: 30px; /* Add rounded corners */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon {
    font-size: 1.5em; /* Adjust the size as needed */
    margin-right: 10px; /* Adjust the gap as needed */
}

.close{
    color: white;
}


.dh-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.dh-modal-header .dh-close {
    border: none; /* Remove border if it exists */
    background: none; /* Remove any background */
    font-size: 1.5rem; /* Adjust the size of the close button */
    padding: 0 1rem; /* Padding to increase touch area */
    color: #000; /* Adjust color based on your design */
    cursor: pointer;
    outline: none; /* Ensures no outline is shown when the button is focused */
    transition: color 0.3s ease, background-color 0.3s ease; /* Smooth transition for hover effects */
}

.dh-modal-header .dh-close:hover,
.dh-modal-header .dh-close:focus { /* Added focus for accessibility */
    color: #f00; /* Change text color on hover */
    background-color: #f8f8f8; /* Slight background color change on hover */
}

.dh-view-route-btn {
    background-color: #28a745; /* Bootstrap's .btn-success background color */
    color: white; /* White text color */
    border: none; /* No border */
    padding: 10px 20px; /* Padding for better touch area */
    border-radius: 5px; /* Rounded corners */
    cursor: pointer; /* Cursor indicates button */
    transition: background-color 0.3s; /* Smooth transition for hover effect */
}

.dh-view-route-btn:hover {
    background-color: #218838; /* Slightly darker on hover */
}


</style>

<div style="display: flex; justify-content: center; box-shadow: 0 2px 4px rgba(0, 0, 0, .108); padding: 15px; position: sticky; top: 0; background-color: white; z-index: 100;">
    @include('guest.FilterProperty')
</div>


<section>
    <div class="">
        <!-- Loading animation -->
        <div class="loading-overlay" style="display: none;">
            <div class="loading-spinner"></div>
        </div>
        @php
            $n = 1;
            @endphp
            @foreach ($properties as $property)
            @php
            $description = $property->description;

            // Adjust the regular expression to handle singular/plural variations and extra spaces
            preg_match('/^(.+?),\s*(\d+)\s*rooms?,\s*(\d+)\s*toilets?,\s*(\d+)sqr?ft,/', $description, $matches);

            // Extract the name, rooms, toilet, and area if available
            $name = isset($matches[1]) ? $matches[1] : '';
            $rooms = isset($matches[2]) ? $matches[2] : '';
            $toilet = isset($matches[3]) ? $matches[3] : '';
            $area = isset($matches[4]) ? $matches[4] : '';

            // Remove the extracted parts from the description
            $description = preg_replace('/^(.+?),\s*(\d+)\s*rooms?,\s*(\d+)\s*toilets?,\s*(\d+)sqr?ft,/', '', $description);

            // Separate the remaining description by commas
            $descriptionParts = array_map('trim', explode(',', $description));

            // Assigning type based on property type
            $type = match($property->type) {
                1 => "Bungalow/Villa",
                2 => "Semi-D",
                3 => "Terrace",
                4 => "Townhouse",
                5 => "Flat/Apartment",
                6 => "Condominium",
                7 => "Penthouse",
                default => "Shop House",
            };
        @endphp
                <div class="property" data-address="{{ $property->address }}" data-type="{{ $property->type }}" data-price="{{ $property->price }}" data-bedrooms="{{ $rooms }}" data-monthly="{{ $property->monthly }}">
                    <div class="carousel">
                        <div class="carousel-content">
                            <div id="carouselExample{{ $n }}" class="carousel slide">
                                <div class="carousel-inner">
                                    @foreach($property->images as $index => $image)
                                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                            <img src="{{ $image->image }}" class="d-block w-100" alt="...">
                                        </div>
                                    @endforeach
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample{{ $n }}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample{{ $n }}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="property-details">
                        <div class="details-content">
                            <!-- Details about the property -->
                            <h4 style="margin-right: 20px; display: inline-block;">{{ $name }}</h4>
                            <button style="float: right;" type="button" class="btn btn-primary viewMapBtn" data-latitude="{{ $property->locationProperty->latitude }}" data-longitude="{{ $property->locationProperty->longitude }}" data-address="{{ $property->address }}">
                                View Map
                            </button>

                            <p>{{ $property->address }}</p>
                            <p>RM {{ $property->monthly }} /monthly</p>
                            <p>
                                {{ $rooms }}
                                <span style="margin-right: 5px;">
                                    <i class="fa-solid fa-bed"></i>
                                </span>
                                {{ $toilet }}
                                <span style="margin-right: 5px;">
                                    <i class="fa fa-bath"></i>
                                </span>
                                <span style="margin-right: 5px;">&#8226;</span>
                                {{ $area }} sqft
                            </p>
                            <div class="extra">
                                <p>{{ $type }}</p>
                                @foreach ($descriptionParts as $part)
                                    <p>{{ $part }}</p>
                                @endforeach
                            </div>
                            {{-- Button view map will be here --}}


        <!-- Property details here -->
        {{-- <button type="button" class="btn btn-primary viewMapBtn" data-latitude="{{ $property->locationProperty->latitude }}" data-longitude="{{ $property->locationProperty->longitude }}" data-address="{{ $property->address }}">
            View Map
        </button> --}}


<!-- Map Modal -->
<div class="modal" id="dh-mapModal" style="margin-top: 140px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="dh-modal-header">
                <h5 class="modal-title">Property Location</h5>
                <button type="button" class="dh-close float-right" id="dh-closeModal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body">
                <div id="dh-map" style="height: 400px; width: 100%;"></div>
                <button id="dh-getRouteBtn" class="btn dh-view-route-btn">Get Route</button> <!-- Get Route Button -->
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapModal = document.getElementById('dh-mapModal');
    const closeModal = document.getElementById('dh-closeModal');
    const getRouteBtn = document.getElementById('dh-getRouteBtn');
    let map;

    document.querySelectorAll('.viewMapBtn').forEach(button => {
        button.addEventListener('click', function() {
            const latitude = this.dataset.latitude;
            const longitude = this.dataset.longitude;
            const address = this.dataset.address;

            mapModal.style.display = 'block';
            setTimeout(() => {
                if (!map) {
                    map = L.map('dh-map').setView([latitude, longitude], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 19}).addTo(map);
                    L.marker([latitude, longitude]).addTo(map)
                        .bindPopup(address)
                        .openPopup();
                } else {
                    map.setView([latitude, longitude], 13);
                    map.eachLayer(function (layer) {
                        map.removeLayer(layer);
                    });
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 19}).addTo(map);
                    L.marker([latitude, longitude]).addTo(map)
                        .bindPopup(address)
                        .openPopup();
                }
            }, 200);

            // Update the data-latitude and data-longitude for the getRouteBtn
            getRouteBtn.setAttribute('data-latitude', latitude);
            getRouteBtn.setAttribute('data-longitude', longitude);
        });
    });

    closeModal.addEventListener('click', function() {
        mapModal.style.display = 'none';
    });

    getRouteBtn.addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const origin = position.coords.latitude + ',' + position.coords.longitude;
                const destination = getRouteBtn.getAttribute('data-latitude') + ',' + getRouteBtn.getAttribute('data-longitude');
                window.open(`https://www.google.com/maps/dir/?api=1&origin=${origin}&destination=${destination}&travelmode=driving`, '_blank');
            }, function() {
                alert('Location access denied. Cannot retrieve route.');
            });
        } else {
            alert('Geolocation is not supported by your browser.');
        }
    });

    window.addEventListener('click', function(event) {
        if (event.target === mapModal) {
            mapModal.style.display = 'none';
        }
    });
});


</script>

                            <div class="col" style="border-top: 1px solid #ccc;">
                                <div class="row" style="display: flex; align-items: center; margin-top: 10px; position: relative;">
                                    <div class="info-prop" style="display: flex; align-items: center; flex: 1;">
                                        <div class="image-agent">
                                            <a href="#"><img src="{{ asset($property->agent->avatar) }}" alt="" style="width: 70px; height: 70px; border-radius: 50%; border: 2px solid #adadad;"></a>
                                        </div>
                                        <div class="headline" style="margin-left: 10px;">
                                            <div class="agent-name">
                                                Listed by <a href="#" class="name" style="text-decoration: none;">
                                                    {{ $property->agent->name }}
                                                </a>
                                                <div class="mt-2">
                                                    <div style="max-width: 200px; word-wrap: break-word;">{{ $property->agent->email }}</div>
                                                    <div style="max-width: 200px; word-wrap: break-word;">{{ $property->agent->phone }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <style>
                                            .agent-contact-button {
                                                padding: 8px 12px;
                                                border-radius: 5px;
                                                font-size: 16px; /* Adjust if necessary to match your design */
                                                margin-right: 10px;
                                                cursor: pointer;
                                                text-decoration: none;
                                                color: rgb(255, 255, 255);
                                                background-color: #007BFF; /* Default for all buttons */
                                                display: inline-flex;
                                                align-items: center; /* Center the icon vertically */
                                                justify-content: center; /* Center the icon horizontally */
                                                transition: background-color 0.3s ease, color 0.3s ease; /* Add smooth transitions */
                                            }

                                            .whatsapp-button {
                                                background-color: #25D366; /* WhatsApp green */
                                            }

                                            .email-button {
                                                background-color: #007BFF; /* Email button blue */
                                            }

                                            /* Adjust icon sizes if necessary */
                                            .agent-contact-button i {
                                                font-size: 20px; /* Larger icons */
                                            }

                                            /* Explicit hover states for each button */
                                            .agent-contact-button:hover {
                                                background-color: darken(#007BFF, 10%); /* Slightly darken the background on hover */
                                                color: white; /* Ensure text color stays white */
                                            }

                                            .whatsapp-button:hover {
                                                background-color: darken(#25D366, 10%); /* Slightly darken the background on hover */
                                                color: #25D366; /* Ensure text color stays white */
                                            }

                                            .email-button:hover {
                                                background-color: darken(#007BFF, 10%); /* Slightly darken the background on hover */
                                                color: #007BFF; /* Ensure text color stays white */
                                            }

                                            .appointment-button {
                                                background-color: #FF5733; /* You can adjust this color as needed */
                                            }

                                            .appointment-button:hover {
                                                background-color: darken(#007BFF, 10%); /* Slightly darken the background on hover */
                                                color: #eb1431; /* Ensure text color stays white */
                                            }

                                            .appointment-popup {
                                                    display: none;
                                                    position: fixed;
                                                    left: 50%;
                                                    top: 50%;
                                                    transform: translate(-50%, -50%);
                                                    background-color: white;
                                                    padding: 20px;
                                                    box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
                                                    border-radius: 10px;
                                                    z-index: 1000;
                                                }

                                                /* Dark background behind the popup */
                                                .popup-background {
                                                    display: none;
                                                    position: fixed;
                                                    top: 0;
                                                    left: 0;
                                                    width: 100%;
                                                    height: 100%;
                                                    background-color: rgba(0, 0, 0, 0.5);
                                                    z-index: 500;
                                                }

                                                .submit-appointment{
                                                    margin-top: 20px;
                                                    background-color: #ff4d4d;
                                                    color: white;
                                                    border: none;
                                                    padding: 8px 12px;
                                                    border-radius: 5px;
                                                    cursor: pointer;
                                                }

                                                /* Styling for close button */
                                                .close-popup {
                                                    margin-top: 20px;
                                                    background-color: #636363;
                                                    color: white;
                                                    border: none;
                                                    padding: 8px 12px;
                                                    border-radius: 5px;
                                                    cursor: pointer;
                                                }

                                                /* Input field styling */
                                                .appointment-input {
                                                    display: block;
                                                    margin: 10px 0;
                                                    padding: 8px;
                                                    width: 100%;
                                                    border: 1px solid #ccc;
                                                    border-radius: 5px;
                                                    font-size: 16px;
                                                }
                                        </style>
                                        @php
                                        // Assuming $phoneNumber contains the phone number fetched from the database
                                        $phoneNumber = $property->agent->phone; // Example phone number

                                        $countryCode = '+6'; // Country code, e.g., +6 for Malaysia

                                        // Prepend the country code to the phone number if it doesn't already have it
                                        if (substr($phoneNumber, 0, 1) !== '+') {
                                            $phoneNumber = $countryCode . $phoneNumber;
                                        }

                                        // Remove any non-numeric characters from the phone number
                                        $cleanPhoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

                                        // Construct the WhatsApp link
                                        $whatsappLink = 'https://wa.me/' . $cleanPhoneNumber;
                                        @endphp
                                        <div style="margin-left: auto;">
                                            @if (Auth::guard('tenant')->user())
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $property->agent->phone) }}" class="btn agent-contact-button whatsapp-button" target="_blank">
                                                    <i class="fa-brands fa-square-whatsapp"></i>
                                                </a>
                                                <a href="mailto:{{ $property->agent->email }}" class="btn agent-contact-button email-button" target="_blank">
                                                    <i class="fa-solid fa-envelope"></i>
                                                </a>
                                                <!-- Button to open the appointment popup -->
                                                <a href="#" class="btn agent-contact-button appointment-button" onclick="openAppointmentPopup(event, '{{ $property->id }}')">
                                                    <i class='bx bxs-send'></i>
                                                </a>
                                            @else

                                                <ul class="alert alert-warning">Login to enable agent interaction buttons</ul>
                                            @endif
                                        </div>
                                            <!-- Popup Overlay -->
                                            <!-- Popup Overlay -->
                                            <div class="popup-background" id="popupBackground{{ $property->id }}" style="display:none;"></div>

                                            <!-- Popup for Setting Appointment -->
                                            <div class="appointment-popup" id="appointmentPopup{{ $property->id }}" style="display:none;">
                                                <h3>Set Your Appointment</h3>
                                                <form action="{{ route('tcappointment') }}" method="POST">
                                                    @csrf
                                                    <label for="appointmentDate">Date:</label>
                                                    <input type="date" id="appointmentDate{{ $property->id }}" name="date" class="appointment-input" required>
                                                    <input type="hidden" name="propertyid" value="{{ $property->id }}">
                                                    @if (Auth::guard('tenant')->user())
                                                        <input type="hidden" name="tenantid" value="{{ Auth::guard('tenant')->user()->id }}">
                                                    @else
                                                        <input type="hidden" name="tenantid" value="">
                                                    @endif
                                                    <label for="appointmentTime">Time:</label>
                                                    <input type="time" id="appointmentTime{{ $property->id }}" name="time" class="appointment-input" required>

                                                    <button type="submit" class="submit-appointment">Submit</button>
                                                    <button type="button" class="close-popup" onclick="closeAppointmentPopup('{{ $property->id }}')">Close</button>
                                                </form>
                                            </div>


                                            <script>
                                                function openAppointmentPopup(event, propertyId) {
                                                    event.preventDefault(); // Prevent the default action (which can cause scrolling)
                                                    var popup = document.getElementById('appointmentPopup' + propertyId);
                                                    var background = document.getElementById('popupBackground' + propertyId);
                                                    popup.style.display = 'block';
                                                    background.style.display = 'block';
                                                    document.body.classList.add('no-scroll'); // Disable scrolling
                                                }

                                                function closeAppointmentPopup(propertyId) {
                                                    var popup = document.getElementById('appointmentPopup' + propertyId);
                                                    var background = document.getElementById('popupBackground' + propertyId);
                                                    popup.style.display = 'none';
                                                    background.style.display = 'none';
                                                    document.body.classList.remove('no-scroll'); // Enable scrolling
                                                }
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @php
                    $n++;
                    @endphp
            </div>
        @endforeach
    </div>
</section>

<script>
    // Display property filtered.
    const searchForm = document.querySelector('.search-form');
    const searchInput = document.querySelector('.search-input');
    const typeCheckboxes = document.querySelectorAll('input[name="type"]');
    const minPriceInput = document.getElementById('min_price');
    const maxPriceInput = document.getElementById('max_price');
    const bedroomCheckboxes = document.querySelectorAll('input[name="bedroom"]');
    const displaySection = document.querySelector('.display');
    const loadingOverlay = document.querySelector('.loading-overlay');

    function filterProperties() {
    const searchText = searchInput.value.trim().toLowerCase();
    const selectedTypes = Array.from(typeCheckboxes)
        .filter(checkbox => checkbox.checked)
        .map(checkbox => parseInt(checkbox.value));
    const minPrice = parseFloat(minPriceInput.value);
    const maxPrice = parseFloat(maxPriceInput.value);
    const selectedBedrooms = Array.from(bedroomCheckboxes)
        .filter(checkbox => checkbox.checked)
        .map(checkbox => parseInt(checkbox.value));

    // Show loading overlay
    loadingOverlay.style.display = 'flex';

    // Filter properties based on search criteria
    const properties = document.querySelectorAll('.property');

    properties.forEach(function(property) {
        const address = property.dataset.address.toLowerCase();
        const type = parseInt(property.dataset.type);
        const monthly = parseFloat(property.dataset.monthly);
        const bedrooms = parseInt(property.dataset.bedrooms);

        // Check if property matches search criteria
        const matchesSearch = address.includes(searchText);
        const matchesType = selectedTypes.length === 0 || selectedTypes.includes(type);
        const matchesMinPrice = isNaN(minPrice) || (isNaN(monthly) ? false : monthly >= minPrice);
        const matchesMaxPrice = isNaN(maxPrice) || (isNaN(monthly) ? false : monthly <= maxPrice);

        // Initialize matchesBedrooms based on whether the selected bedrooms array includes the property's bedroom count
        let matchesBedrooms = selectedBedrooms.length === 0 || selectedBedrooms.some(selBed => bedrooms === selBed);

        // Special handling for bedroom value of 5
        if (selectedBedrooms.includes(5)) {
            matchesBedrooms = matchesBedrooms || bedrooms >= 5;
        }

        // Show/hide property based on matches
        if (matchesSearch && matchesType && matchesMinPrice && matchesMaxPrice && matchesBedrooms) {
            property.style.display = 'block';
        } else {
            property.style.display = 'none';
        }
    });

    // Hide loading overlay after 3 seconds
    setTimeout(function() {
        loadingOverlay.style.display = 'none';
    }, 2000);
}


    function setFilterInputsFromUrl() {
        const urlParams = new URLSearchParams(window.location.search);

        searchInput.value = urlParams.get('search') || '';

        const types = urlParams.getAll('type');
        typeCheckboxes.forEach(checkbox => {
            checkbox.checked = types.includes(checkbox.value);
        });

        minPriceInput.value = urlParams.get('min_price') || '';
        maxPriceInput.value = urlParams.get('max_price') || '';

        const bedrooms = urlParams.getAll('bedroom');
        bedroomCheckboxes.forEach(checkbox => {
            checkbox.checked = bedrooms.includes(checkbox.value);
        });
    }

    searchForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent form submission
        filterProperties();
    });

    // Filter properties on page load
    window.addEventListener('load', function() {
        setFilterInputsFromUrl();
        if (window.location.search !== '') {
            filterProperties();
        }
    });
</script>


@endsection
