@extends('layouts.staff-layout')

@section('title', 'Property • View')

@section('content')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0" />


<style type="text/css">
    .contract-detail-container {
        color: #333; /* Darker text for better readability */
        background: #fff; /* Light background for a professional appearance */
        border-radius: 8px; /* Rounded corners */
        box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Subtle shadow for depth */
        padding: 20px; /* Padding around the content */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Professional font */
        margin-top: 10px;
        margin-bottom: 15px; /* Space below the header */
    }

    .contract-detail-header {
        padding-bottom: 15px;
        border-bottom: 2px solid #dee2e6; /* Light grey border for separation */
        margin-bottom: 20px; /* Space below the header */
    }

    .contract-detail-row {
        display: flex;
        justify-content: space-between; /* Space between the label and value */
        margin-bottom: 10px; /* Space between rows */
    }

    .contract-detail-label {
        font-weight: 600; /* Bold label for emphasis */
        color: #022d6a; /* Dark blue for a touch of color */
        flex-basis: 30%; /* Width of the label */
    }

    .contract-detail-value {
        background: #f8f9fa; /* Very light background for the value */
        padding: 8px 12px; /* Padding inside the value box */
        border-radius: 5px; /* Rounded corners for the value box */
        flex-basis: 65%; /* Width of the value area */
        /* text-align: right; */
    }

    .contract-action-alert {
        background-color: #ffc107; /* Yellow background for attention */
        color: #333; /* Dark text for visibility */
        padding: 15px;
        margin: 20px 0;
        border-radius: 5px; /* Rounded corners */
        display: flex;
        justify-content: space-between; /* Space between text and button */
        align-items: center;
    }

    .contract-action-button {
        background-color: #0056b3; /* Dark blue background */
        color: #fff; /* White text */
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
    }
    .agent-details-container {
        color: #333;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        padding: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin-top: 10px;

    }

    .agent-details-header {
        cursor: pointer;
        padding-bottom: 15px;
        border-bottom: 2px solid #dee2e6;
        margin-bottom: 20px;
    }

    .agent-details-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .agent-details-label {
        font-weight: 600;
        color: #022d6a;
        flex-basis: 30%;
    }

    .agent-details-value {
        background: #f8f9fa;
        padding: 8px 12px;
        border-radius: 5px;
        flex-basis: 65%;
    }

    .avatar-container {
        position: relative; /* Allows precise positioning */
        width: 180px; /* Fixed width */
        height: 180px; /* Fixed height */
        margin-right: 20px; /* Right margin for spacing */
    }

    .avatar-container img {
        width: 100%; /* Full width of the container */
        height: 100%; /* Full height of the container */
        border-radius: 50%; /* Circle shape */
        object-fit: cover; /* Ensures the image covers the container */
    }

    .agent-contact-button {
        padding: 8px 12px;
        border-radius: 5px;
        font-size: 16px; /* Adjust if necessary to match your design */
        margin-right: 10px;
        cursor: pointer;
        text-decoration: none;
        color: white;
        background-color: #007BFF; /* Default for all buttons */
        display: inline-flex;
        align-items: center; /* Center the icon vertically */
        justify-content: center; /* Center the icon horizontally */
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

    /* Example of responsive settings for the main containers */
    .container {
        max-width: 100%;
        padding:10px; /* Adjust padding as needed */
        box-sizing: border-box; /* Includes padding in width calculations */
    }


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

    /* image-properties */
    .slider-wrapper {
        position: relative;
    }

    .slider-wrapper .slide-button {
        position: absolute;
        top: 50%;
        outline: none;
        border: none;
        height: 50px;
        width: 50px;
        z-index: 5;
        color: #fff;
        display: flex;
        cursor: pointer;
        font-size: 2.2rem;
        background: #000;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transform: translateY(-50%);
    }

    .slider-wrapper .slide-button:hover {
        background: #404040;
    }

    .slider-wrapper .slide-button#prev-slide {
        left: -25px;
        display: none;
    }

    .slider-wrapper .slide-button#next-slide {
        right: -25px;
    }

    .slider-wrapper .image-list {
        display: flex; /* Using flexbox for a single row */
        overflow-x: auto; /* Enables horizontal scrolling */
        scrollbar-width: none; /* Hides the scrollbar in Firefox */
        -ms-overflow-style: none;  /* Hides the scrollbar in IE and Edge */
        gap: 18px; /* Maintain the gap between images */
        list-style: none;
        margin-bottom: 30px;
    }

    .slider-wrapper .image-list::-webkit-scrollbar {
        display: none; /* Hides the scrollbar in WebKit browsers */
    }

    .slider-wrapper .image-list .image-item {
        flex: 0 0 auto; /* Prevents flex items from growing or shrinking */
        width: 200px; /* Fixed width for each image */
        height: 300px; /* Fixed height for each image */
        object-fit: cover; /* Ensures images cover the area without being stretched */
    }


    .container .slider-scrollbar {
        height: 24px;
        width: 100%;
        display: flex;
        align-items: center;
    }

    .slider-scrollbar .scrollbar-track {
        background: #ccc;
        width: 100%;
        height: 2px;
        display: flex;
        align-items: center;
        border-radius: 4px;
        position: relative;
    }

    .slider-scrollbar:hover .scrollbar-track {
        height: 4px;
    }

    .slider-scrollbar .scrollbar-thumb {
        position: absolute;
        background: #000;
        top: 0;
        bottom: 0;
        width: 50%;
        height: 100%;
        cursor: grab;
        border-radius: inherit;
    }

    .slider-scrollbar .scrollbar-thumb:active {
        cursor: grabbing;
        height: 8px;
        top: -2px;
    }

    .slider-scrollbar .scrollbar-thumb::after {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        top: -10px;
        bottom: -10px;
    }

    /* Styles for mobile and tablets */
    @media only screen and (max-width: 1023px) {
        .slider-wrapper .slide-button {
            display: none !important;
        }

        .slider-wrapper .image-list .image-item {
            width: 180px;
            height: 270px;
        }

        .slider-wrapper .image-list .image-item {
            width: 280px;
            height: 380px;
        }

        .slider-scrollbar .scrollbar-thumb {
            width: 20%;
        }
    }

    /* CSS for enlarged image */
    .enlarged-image {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .enlarged-image img {
        max-width: 70%;
        max-height: 70%;
        object-fit: contain;
    }

    .close-enlarged {
        position: absolute;
        top: 18%;
        right: 26%;
        transform: translate(50%, -50%);
        color: white;
        font-size: 30px;
        cursor: pointer;
        width: 40px; /* Set width and height to maintain circular shape */
        height: 40px; /* Set width and height to maintain circular shape */
        padding: 5px; /* Add padding for better visibility */
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .close-enlarged:hover {
        background-color: rgba(0, 0, 0, 0.5); /* Add a semi-transparent black background */
        border-radius: 50%; /* Make the background circular */
        position: absolute;
        top: 18%;
        right: 26%;
        transform: translate(50%, -50%);
    }

    @media only screen and (max-width: 1023px) {
        .enlarged-image img {
            max-width: 95%; /* Larger view on smaller screens */
            max-height: 80%; /* Adjust to prevent overflow */
        }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header .close {
        border: none; /* Remove border if it exists */
        background: none; /* Remove any background */
        font-size: 1.5rem; /* Adjust the size of the close button */
        padding: 0 1rem; /* Padding to increase touch area */
        color: #000; /* Adjust color based on your design */
        cursor: pointer;
        outline: none; /* Ensures no outline is shown when the button is focused */
        transition: color 0.3s ease, background-color 0.3s ease; /* Smooth transition for hover effects */
    }

    .modal-header .close:hover,
    .modal-header .close:focus { /* Added focus for accessibility */
        color: #f00; /* Change text color on hover */
        background-color: #f8f8f8; /* Slight background color change on hover */
    }
</style>


<div class="notice" style="overflow: hidden;">
    @include('landlord.property.success-message')
</div>

<div class="details-container">
    <a href="#" id="backButton" onclick="event.preventDefault(); window.history.back();" class="btn float-end" style="background-color: #17395e; color: white;">Back</a>

    <script>
    window.onload = function() {
        // Check if the history length is greater than 1
        if (window.history.length <= 1) {
        // If not, hide the back button
        document.getElementById('backButton').style.display = 'none';
        }
    };
    </script>

    <div class="details-header" id="paymentDetailHeader">
        <h4>Your Property
        </h4>
    </div>
    <div class="card-body" id="paymentDetailBody">
        @if ($images->isEmpty())
        <li class="alert alert-danger">Add some images, to proceed approval process</li>
        @else
        <div class="container">
            <div class="slider-wrapper">
                <button id="prev-slide" class="slide-button material-symbols-rounded">
                    chevron_left
                </button>
                <ul class="image-list">
                    @php $n = 1; @endphp
                    @foreach ($images as $image)
                    <li><img class="image-item" src="{{ asset($image->image) }}" alt="img-{{ $n }}"></li>
                    @php $n++; @endphp
                    @endforeach
                </ul>
                <button id="next-slide" class="slide-button material-symbols-rounded">
                    chevron_right
                </button>
            </div>
            <div class="slider-scrollbar">
                <div class="scrollbar-track">
                    <div class="scrollbar-thumb"></div>
                </div>
            </div>
        </div>
        @endif
        <div class="contract-detail-header">
            <h4>Property Details</h4>
        </div>

        <div class="contract-detail-row">
            <div class="contract-detail-label">Address</div>
            <div class="contract-detail-value">{{ $property->address }}</div>
        </div>
        <div class="contract-detail-row">
            <div class="contract-detail-label">Deposit (RM)</div>
            <div class="contract-detail-value">{{ $property->deposit }}</div>
        </div>
        <div class="contract-detail-row">
            <div class="contract-detail-label">Monthly (RM)</div>
            <div class="contract-detail-value">{{ $property->monthly }}</div>
        </div>
        <div class="contract-detail-row">
            <div class="contract-detail-label">Type</div>
            <div class="contract-detail-value">
                @if ($property->type == 1)
                Bungalow/Villa
                @elseif ($property->type == 2)
                Semi-D
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
            </div>
        </div>
        <div class="contract-detail-row">
            <div class="contract-detail-label">Description</div>
            <div class="contract-detail-value">{{ $property->description }}</div>
        </div>
        <div class="contract-detail-row">
            <div class="contract-detail-label">Available</div>
            <div class="contract-detail-value">
                @if ($property->available == 1)
                &#9989
                @else
                &#10060
                @endif
            </div>
        </div>
        <div class="contract-detail-row">
            <div class="contract-detail-label">Status</div>
            <div class="contract-detail-value">
                @if ($property->status == 3)
                Pending
                @elseif ($property->status == 1)
                Approved
                @elseif ($property->status == 4)
                Incomplete
                @else
                Inapprove
                @endif
            </div>
        </div>
        <button type="button" class="btn" style="background-color: #17395e; color: white;" id="viewMapBtn">
            View Map
        </button>
        <!-- Map Modal -->
        <div class="modal" id="mapModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Property Location</h5>
                        <button type="button" class="close float-right" id="closeModal" aria-label="Close">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div id="map" style="height: 400px; width: 100%;"></div>
                        <button id="getRouteBtn" class="btn btn-secondary mt-2">Get Route</button> <!-- Get Route Button -->
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

        <script>
           document.addEventListener('DOMContentLoaded', function() {
                const mapModal = document.getElementById('mapModal');
                const viewMapBtn = document.getElementById('viewMapBtn');
                const closeModal = document.getElementById('closeModal');
                const getRouteBtn = document.getElementById('getRouteBtn');

                let map;

                viewMapBtn.addEventListener('click', function() {
                    mapModal.style.display = 'block';
                    setTimeout(() => {
                        if (!map) { // Check if map is already initialized
                            map = L.map('map').setView([{{ $locationProperty->latitude }}, {{ $locationProperty->longitude }}], 13);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 19}).addTo(map);
                            L.marker([{{ $locationProperty->latitude }}, {{ $locationProperty->longitude }}]).addTo(map)
                                .bindPopup('{{ $property->address }}')
                                .openPopup();
                        }
                    }, 200);
                });

                closeModal.addEventListener('click', function() {
                    mapModal.style.display = 'none';
                });

                getRouteBtn.addEventListener('click', function() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            const origin = position.coords.latitude + ',' + position.coords.longitude;
                            const destination = '{{ $locationProperty->latitude }},{{ $locationProperty->longitude }}';
                            window.open(`https://www.google.com/maps/dir/?api=1&origin=${origin}&destination=${destination}&travelmode=driving`, '_blank');
                        }, function() {
                            alert('Location access denied. Cannot retrieve route.');
                        });
                    } else {
                        alert('Geolocation is not supported by your browser.');
                    }
                });

                window.addEventListener('click', function(event) {
                    if (event.target == mapModal) {
                        mapModal.style.display = 'none';
                    }
                });
            });
        </script>

    </div>
</div>

<div class="container agent-details-container">
    <div class="agent-details-header" id="agentDetailHeader">
        <h4>Agent Detail</h4>
    </div>
    <div class="card-body" style="display: none;" id="agentDetailBody">
        @if ($property->staff === null)
        <h3 class="text-center text-secondary p-3">No agent assigned for this property.</h3>
        @else
        <div class="d-flex justify-content-start">
            <div class="avatar-container">
                <img src="{{ asset($property->agent->avatar) }}" alt="Avatar">
            </div>
            <div class="container mt-3">
                <div class="agent-details-row">
                    <div class="agent-details-label">Name</div>
                    <div class="agent-details-value">{{ $property->agent->name }}</div>
                </div>
                <div class="agent-details-row">
                    <div class="agent-details-label">Email</div>
                    <div class="agent-details-value">{{ $property->agent->email }}</div>
                </div>
                <div class="agent-details-row">
                    <div class="agent-details-label">Phone</div>
                    <div class="agent-details-value">{{ substr($property->agent->phone, 0, 3) }}-{{ substr($property->agent->phone, 3) }}</div>
                </div>
                <div class="agent-contact-buttons">
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
                    <a href="{{ $whatsappLink }}" class="btn agent-contact-button whatsapp-button" target="_blank">
                        <i class="fa-brands fa-square-whatsapp"></i> <!-- WhatsApp Icon -->
                    </a>
                    <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $property->staff->email }}" class="btn agent-contact-button email-button" target="_blank">
                        <i class="fa-solid fa-envelope"></i> <!-- Email Icon -->
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        $("#agentDetailHeader").click(function(){
            $("#agentDetailBody").slideToggle();
        });
    });
</script>

<div class="container agent-details-container">

    <div class="agent-details-header" id="contractDetailHeader">
        <h4>Contract Detail</h4>
    </div>
    <div class="card-body" style="display: none;" id="contractDetailBody">
        @if ($property->contracts->isEmpty())
            <h3 class="text-center text-secondary p-3">No contracts available for this property.</h3>
        @else
            @foreach($property->contracts as $contract)
                @if ($contract->status == 2)
                <li class="alert alert-warning"><span>Tenant need to pay deposit first to proceed the contract.</span></li>
                @endif
                <div class="contract-detail-row">
                    <div class="contract-detail-label">Address</div>
                    <div class="contract-detail-value">{{ $property->address }}</div>
                </div>
                <div class="contract-detail-row">
                    <div class="contract-detail-label">Period (month)</div>
                    <div class="contract-detail-value">{{ $contract->period }}</div>
                </div>
                <div class="contract-detail-row">
                    <div class="contract-detail-label">Deposit (RM)</div>
                    <div class="contract-detail-value">
                        {{ number_format($contract->deposit, 2) }}
                        @if ($contract->deposit < $property->deposit)
                            @php
                                $b = $contract->deposit - $property->deposit;
                            @endphp
                            &nbsp;&nbsp;&nbsp;&nbsp;<span style="color: red"> {{ number_format($b, 2) }}</span>
                        @endif
                    </div>
                </div>
                <div class="contract-detail-row">
                    <div class="contract-detail-label">Monthly (RM)</div>
                    <div class="contract-detail-value">{{ number_format($property->monthly, 2) }}</div>
                </div>
                <div class="contract-detail-row">
                    <div class="contract-detail-label">Total (RM)</div>
                    <div class="contract-detail-value">{{ number_format($contract->total, 2) }}</div>
                </div>
                <div class="contract-detail-row">
                    <div class="contract-detail-label">Balance (RM)</div>
                    <div class="contract-detail-value">{{ number_format($contract->balance, 2) }}</div>
                </div>
                <div class="contract-detail-row">
                    <div class="contract-detail-label">Start Date</div>
                    <div class="contract-detail-value">{{ date('d-m-Y', strtotime($contract->start_date)) }}</div>
                </div>
                <div class="contract-detail-row">
                    <div class="contract-detail-label">End Date</div>
                    <div class="contract-detail-value">{{ date('d-m-Y', strtotime($contract->end_date)) }}</div>
                </div>
                <div class="contract-detail-row">
                    <div class="contract-detail-label">Status</div>
                    <div class="contract-detail-value">
                        @if ($contract->status == 2)
                        Pending
                        @elseif ($contract->status == 1)
                        Active
                        @elseif ($contract->status == 3)
                        Terminated
                        @else
                        Voided
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        $("#contractDetailHeader").click(function(){
            $("#contractDetailBody").slideToggle();
        });
    });
</script>

<script>
    const initSlider = () => {
        const imageList = document.querySelector(".slider-wrapper .image-list");
        const slideButtons = document.querySelectorAll(".slider-wrapper .slide-button");
        const sliderScrollbar = document.querySelector(".container .slider-scrollbar");
        const scrollbarThumb = sliderScrollbar.querySelector(".scrollbar-thumb");
        const maxScrollLeft = imageList.scrollWidth - imageList.clientWidth;

        // Handle scrollbar thumb drag
        scrollbarThumb.addEventListener("mousedown", (e) => {
            const startX = e.clientX;
            const thumbPosition = scrollbarThumb.offsetLeft;
            const maxThumbPosition = sliderScrollbar.getBoundingClientRect().width - scrollbarThumb.offsetWidth;

            // Update thumb position on mouse move
            const handleMouseMove = (e) => {
                const deltaX = e.clientX - startX;
                const newThumbPosition = thumbPosition + deltaX;

                // Ensure the scrollbar thumb stays within bounds
                const boundedPosition = Math.max(0, Math.min(maxThumbPosition, newThumbPosition));
                const scrollPosition = (boundedPosition / maxThumbPosition) * maxScrollLeft;

                scrollbarThumb.style.left = `${boundedPosition}px`;
                imageList.scrollLeft = scrollPosition;
            }

            // Remove event listeners on mouse up
            const handleMouseUp = () => {
                document.removeEventListener("mousemove", handleMouseMove);
                document.removeEventListener("mouseup", handleMouseUp);
            }

            // Add event listeners for drag interaction
            document.addEventListener("mousemove", handleMouseMove);
            document.addEventListener("mouseup", handleMouseUp);
        });

        // Slide images according to the slide button clicks
        slideButtons.forEach(button => {
            button.addEventListener("click", () => {
                const direction = button.id === "prev-slide" ? -1 : 1;
                const scrollAmount = imageList.clientWidth * direction;
                imageList.scrollBy({ left: scrollAmount, behavior: "smooth" });
            });
        });

        // Show or hide slide buttons based on scroll position
        const handleSlideButtons = () => {
            slideButtons[0].style.display = imageList.scrollLeft <= 0 ? "none" : "flex";
            slideButtons[1].style.display = imageList.scrollLeft >= maxScrollLeft ? "none" : "flex";
        }

        // Update scrollbar thumb position based on image scroll
        const updateScrollThumbPosition = () => {
            const scrollPosition = imageList.scrollLeft;
            const thumbPosition = (scrollPosition / maxScrollLeft) * (sliderScrollbar.clientWidth - scrollbarThumb.offsetWidth);
            scrollbarThumb.style.left = `${thumbPosition}px`;
        }

        // Call these two functions when image list scrolls
        imageList.addEventListener("scroll", () => {
            updateScrollThumbPosition();
            handleSlideButtons();
        });

        const enlargeImage = (src) => {
            const enlargedContainer = document.createElement('div');
            enlargedContainer.classList.add('enlarged-image');
            enlargedContainer.innerHTML = `
                <span class="close-enlarged">&times;</span>
                <img src="${src}" alt="Enlarged Image" style="max-width: 90%; max-height: 90%;">
            `;
            document.body.appendChild(enlargedContainer);

            // Close enlarged image when clicking on close button
            const closeButton = enlargedContainer.querySelector('.close-enlarged');
            closeButton.addEventListener('click', () => {
                enlargedContainer.remove();
            });
        };

        window.addEventListener("resize", () => {
            initSlider(); // Re-initialize slider to update layout and functionality
        });


        // Add click event listener to each image
        imageList.querySelectorAll('.image-item').forEach(image => {
                image.addEventListener('click', () => {
                    enlargeImage(image.src);
                });
            });
        }

        window.addEventListener("resize", initSlider);
        window.addEventListener("load", initSlider);
</script>
@endsection
