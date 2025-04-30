@extends('layouts.guest-layout')

@section('title', 'DreamHouse • About')

@section('content')

<style type="text/css">
    .dreamhouse-about {
        background: linear-gradient(to right, #01315c, #010316); /* Dark blue gradient */
        color: white; /* Ensure text is readable on dark background */
    }

    .dreamhouse-about .container {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        min-height: 91vh;
    }

    .dreamhouse-about .about-us-container {
        max-width: 1200px;
        width: 100%;
        padding: 20px;
    }

    .dreamhouse-about .about-title {
        font-size: 36px;
        font-weight: bold;
        margin-bottom: 20px;
        text-align: center;
    }

    .dreamhouse-about .about-description {
        font-size: 20px;
        line-height: 1.6;
        margin-bottom: 40px;
    }

    .dreamhouse-about .section-title {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .dreamhouse-about .section-content {
        margin-bottom: 30px;
    }

    @media (max-width: 768px) {
        .dreamhouse-about .about-title {
            font-size: 28px;
        }

        .dreamhouse-about .about-description, .dreamhouse-about .section-content {
            font-size: 18px;
        }

        .dreamhouse-about .section-title {
            font-size: 24px;
        }
    }
</style>

<div class="dreamhouse-about">
    <div class="container">
        <div class="about-us-container">
            <h1 class="about-title">About DreamHouse</h1>
            <p class="about-description">
                Welcome to DreamHouse, your trusted partner in rental management. We specialize in connecting homeowners with potential tenants, ensuring a seamless and efficient rental experience for all parties involved.
            </p>

            <div class="section">
                <h2 class="section-title">Our Mission</h2>
                <p class="section-content">
                    Our mission is to simplify the rental process by providing innovative solutions that benefit both property owners and renters. We aim to create a hassle-free environment where finding and managing rental properties is as easy as possible.
                </p>
            </div>

            <div class="section">
                <h2 class="section-title">Our Vision</h2>
                <p class="section-content">
                    We envision a world where renting a home is a seamless, transparent, and efficient process. Our goal is to become the leading platform for rental management by continually improving our services and embracing new technologies.
                </p>
            </div>

            <div class="section">
                <h2 class="section-title">Our Values</h2>
                <p class="section-content">
                    At DreamHouse, we uphold the values of integrity, innovation, and customer satisfaction. We believe in building long-term relationships based on trust and reliability. Our team is dedicated to providing exceptional service and support to both homeowners and tenants.
                </p>
            </div>
        </div>
    </div>
</div>

@endsection
