@extends('layouts.guest-layout')


@section('title', 'DreamHouse • Welcome')

@section('content')


<section class="masthead-video-section" style="background-color: #eff1f5; margin-bottom: 0; padding-bottom: 0;">
    <div class="masthead-video d-flex align-items-center justify-content-center">
        <a href="#" class="actionable-link external-link" target="_blank" rel="noopener"></a>
        <div class="masthead-video-container" style="margin-left:10px;">
            <div class="container">
                <div class="row">
                    <div class="video-container w-100" style="width: 100%; max-width: 100%; overflow: hidden;">
                        <video autoplay muted loop playsinline poster="" class="img-fluid" style="width: 100%; height: auto;">
                            <source src="{{ asset('uploads/ads/ads1.mp4') }}" type="video/mp4">
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




<style>
    .image-container {
        position: relative;
        display: inline-block;
    }
    .row img {
        height: 100%;
        object-fit: cover;
    }
    .btn-bottom-right {
        position: absolute;
        bottom: 10px;
        right: 10px;
    }
    /* Override Bootstrap's row padding */
    .container>.row {
        padding-right: 0 !important;
    }
    .container>.row>[class^="col-"] {
        padding-right: 0 !important;
    }
</style>

<section>
    <div class="container text-center mt-3">
        <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-6">
                <div class="col-12 mb-2 image-container">
                    <img src="{{ asset('uploads/ads/img1.png') }}" class="img-fluid" alt="Image 1">
                    <button class="btn btn-bottom-right" style="background-color: #211e1f; color: white;">Contact</button>
                </div>
                <div class="col-12 mb-2 image-container">
                    <img src="{{ asset('uploads/ads/img2.png') }}" class="img-fluid" alt="Image 2">
                    <button class="btn btn-bottom-right" style="background-color: #eab92a; color: white;">Explore</button>
                </div>
            </div>
            <div class="col-sm-6 col-md-5 offset-md-2 col-lg-6 offset-lg-0 mb-2 image-container">
                <img src="{{ asset('uploads/ads/img3.png') }}" class="img-fluid" alt="Image 3">
                <button class="btn btn-primary btn-bottom-right">Register</button>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</section>




@include('guest.filter')

@endsection
