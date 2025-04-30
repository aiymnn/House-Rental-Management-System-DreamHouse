@extends('layouts.guest-layout')

@section('title', 'DreamHouse • Contact')

@section('content')

<style type="text/css">
    body {
        background: linear-gradient(to left, #01315c, #010316); /* Dark blue gradient */
        color: white; /* Ensure text is readable on dark background */
    }

    .container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 80vh;
        padding: 20px;
    }

    .contact-us-container {
        display: flex;
        flex-wrap: wrap;
        padding: 20px;
        max-width: 1200px;
        width: 100%;
    }

    .contact-form-column, .contact-details-column {
        flex: 1;
        margin: 10px;
        min-width: 300px; /* Ensures columns don't get too narrow */
    }

    .contact-form {
        display: flex;
        flex-direction: column;
    }

    .form-label {
        margin-bottom: 5px;
    }

    .form-input, .form-textarea {
        margin-bottom: 20px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        width: 100%;
        box-sizing: border-box; /* Ensures padding doesn't add to width */
        background-color: #f8f8f8; /* Light background for input fields */
    }

    .inline-inputs {
        display: flex;
        flex-direction: column;
    }

    .inline-input-item {
        flex: 1;
        margin-bottom: 20px; /* Added gap */
        box-sizing: border-box; /* Ensures padding doesn't add to width */
    }

    .inline-input-item:last-child {
        margin-bottom: 0;
    }

    .form-textarea {
        height: 100px;
        resize: vertical;
    }

    .form-submit {
        align-self: flex-start;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        background-color: #007BFF;
        color: #fff;
        cursor: pointer;
    }

    .details-title {
        margin-top: 5px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .details-description {
        font-size: 20px;
        margin-bottom: 50px;
    }

    .contact-details-column {
        padding-left: 40px; /* Added padding to move the details more to the right */
    }

    .contact-detail {
        display: flex;
        align-items: center;
        font-size: 20px;
        margin-bottom: 20px;
    }

    .contact-detail i {
        font-size: 24px;
        margin-right: 15px;
        width: 50px; /* Fixed width for icons */
        height: 50px; /* Fixed height for icons */
        text-align: center; /* Center icons within the fixed width */
        background-color: #007BFF; /* Blue background */
        border-radius: 50%; /* Circle shape */
        color: #fff; /* Icon color */
        padding: 12px; /* Padding to fit the icon */
    }

    .detail-info {
        margin-left: 10px;
        flex: 1; /* Ensure detail-info takes up the remaining space */
    }

    .blue-text {
        color: #007BFF; /* Blue color for "Us" */
    }

    /* Responsive Styles */
    @media (min-width: 600px) {
        .inline-inputs {
            flex-direction: row;
            justify-content: space-between;
        }

        .inline-input-item {
            margin-right: 20px;
        }

        .inline-input-item:last-child {
            margin-right: 0;
        }
    }

    @media (max-width: 599px) {
        .contact-us-container {
            flex-direction: column;
        }

        .contact-details-column {
            padding-left: 0;
        }

        .contact-detail {
            font-size: 18px;
        }

        .contact-detail i {
            font-size: 20px;
            width: 40px;
            height: 40px;
            padding: 10px;
        }
    }
</style>

<div class="container">
    <div class="contact-us-container">
        <div class="contact-form-column">
            <form class="contact-form">
                <label for="name" class="form-label">Name</label>
                <input type="text" id="name" class="form-input" name="name">

                <div class="inline-inputs">
                    <div class="inline-input-item">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" class="form-input" name="email">
                    </div>
                    <div class="inline-input-item">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" id="phone" class="form-input" name="phone">
                    </div>
                </div>

                <label for="message" class="form-label">Message</label>
                <textarea id="message" class="form-textarea" name="message"></textarea>

                <button type="submit" class="form-submit">Submit</button>
            </form>
        </div>

        <div class="contact-details-column">
            <h1 class="details-title">Contact <span class="blue-text">Us</span></h1>
            <p class="details-description">For questions, technical assistance, or collaboration opportunities via the contact information provided.</p>
            <div class="contact-detail">
                <i class="fas fa-phone-alt"></i>
                <span class="detail-info">123-456-7890</span>
            </div>
            <div class="contact-detail">
                <i class="fas fa-envelope"></i>
                <span class="detail-info">contact@example.com</span>
            </div>
            <div class="contact-detail">
                <i class="fas fa-map-marker-alt"></i>
                <span class="detail-info">123 Example Street, City, Country</span>
            </div>
        </div>
    </div>
</div>

@endsection
