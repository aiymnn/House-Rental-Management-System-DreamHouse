<style>
    .agent-details-container {
        color: #333;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        padding: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;

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
</style>

<div class="container agent-details-container">
    <div class="agent-details-header mt-3" id="agentDetailHeader">
        <h4>Agent Detail</h4>
    </div>
    <div class="card-body" id="agentDetailBody">
        <div class="d-flex justify-content-start">
            <div class="avatar-container">
                <img src="{{ asset($agent->avatar) }}" alt="Avatar">
            </div>
            <div class="container mt-3">
                <div class="agent-details-row">
                    <div class="agent-details-label">Name</div>
                    <div class="agent-details-value">{{ $agent->name ?? 'N/A' }}</div>
                </div>
                <div class="agent-details-row">
                    <div class="agent-details-label">Email</div>
                    <div class="agent-details-value">{{ $agent->email ?? 'N/A' }}</div>
                </div>
                <div class="agent-details-row">
                    <div class="agent-details-label">Phone</div>
                    <div class="agent-details-value">{{ substr($agent->phone, 0, 3) }}-{{ substr($agent->phone, 3) }}</div>
                </div>
                <div class="agent-contact-buttons">
                    @php
                            // Assuming $phoneNumber contains the phone number fetched from the database
                            $phoneNumber = $agent->phone; // Example phone number

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
                    <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $agent->email }}" class="btn agent-contact-button email-button" target="_blank">
                        <i class="fa-solid fa-envelope"></i> <!-- Email Icon -->
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>



