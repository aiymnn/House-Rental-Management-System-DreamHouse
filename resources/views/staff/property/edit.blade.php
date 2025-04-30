@extends('layouts.staff-layout')

@section('title', 'Property • Update')

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
    /* Style for the image container */
    .image-container {
        position: relative;
        display: inline-block;
    }

    .delete-button {
        position: absolute;
        top: 5px;
        right: 5px;
        color: #17395e;
        background-color: rgba(255, 255, 255, 0.7);
        border-radius: 50%;
        padding: 5px;
        transition: background-color 0.3s ease-in-out;
    }

    .delete-button:hover {
        background-color: rgba(255, 0, 0, 0.7);
        color: white;
    }

    .image {
        width: 100px;
        height: 100px;
    }

    #imageContainer {
      display: flex;
      flex-wrap: wrap;
      gap: 5px;
    }

    .imagePreview {
      width: calc(14.2857143% - 5px);
      position: relative;
      border: 1px solid #ccc;
      border-radius: 5px;
      overflow: hidden;
    }

    .imagePreview img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.2s ease-in-out;
    }

    .imagePreview:hover img {
      transform: scale(1.1);
    }

    .deleteImage {
        position: absolute;
        top: 5px; /* Adjusted position from -10px to 5px */
        right: 5px; /* Adjusted position from -10px to 5px */
        background-color: rgba(255, 255, 255, 0.8);
        border: none;
        border-radius: 50%;
        padding: 5px;
        cursor: pointer;
        color: #ff0000;
        font-weight: bold;
        font-size: 1.2rem;
        transition: transform 0.2s ease-in-out;
        z-index: 1; /* Ensures the button appears above the image */
        }

    .deleteImage:hover {
        transform: scale(1.2);
    }
</style>

<div class="notice" style="margin-top: 5px; overflow: hidden;">
    @include('landlord.property.success-message')
</div>

<div class="details-container">
    <a href="{{ route('staff_property')}}" class="btn float-end" style="background-color: #17395e; color: white;">Back</a>
    <div class="details-header" id="paymentDetailHeader">
        <h4>Update Property
        </h4>
    </div>
    <div class="card-body" id="paymentDetailBody">
        <form action="{{ url('staff/property/edit/'.$property->id.'') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Title <span style="color: #c4c4c4">(ads)</span></label>
                <textarea class="form-control" rows="1" aria-describedby="emailHelp" placeholder="Add title to your property" name="title">{{ $name }}</textarea>
                @error('title') <span class="text-danger">{{ $message }}</span>@enderror
                <small class="text-muted">Title should contain letters, spaces, and parentheses only.</small>
            </div>

            <div class="mb-3">
                <label class="form-label">State</label>
                <select class="form-control" id="stateDropdown" name="state">
                    <option value="">Select State</option>
                    <!-- Dynamically loaded states will go here -->
                </select>
                @error('state') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">City</label>
                <select class="form-control" id="cityDropdown" name="city" disabled>
                    <option value="">Select City</option>
                    <!-- Dynamically loaded cities will go here -->
                </select>
                @error('city') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Postcode</label>
                <select class="form-control" id="postcodeInput" name="postcode">
                    <option value="">Select Postcode</option>
                </select>
                @error('postcode') <span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <script>
            document.addEventListener("DOMContentLoaded", function() {
                const stateDropdown = document.getElementById('stateDropdown');
                const cityDropdown = document.getElementById('cityDropdown');
                const postcodeDropdown = document.getElementById('postcodeInput');
                const initialState = "{{ $state }}";
                const initialCity = "{{ $city }}";
                const initialPostcode = "{{ $postcode }}";

                // Load states on page load
                loadStates();

                function loadStates() {
                    const states = [
                            { name: "Johor", value: "johor" },
                            { name: "Penang", value: "penang" },
                            { name: "Kedah", value: "kedah" },
                            { name: "Kelantan", value: "kelantan" },
                            { name: "Kuala Lumpur", value: "kuala_lumpur" },
                            { name: "Labuan", value: "labuan" },
                            { name: "Melaka", value: "melaka" },
                            { name: "Negeri Sembilan", value: "negeri_sembilan" },
                            { name: "Pahang", value: "pahang" },
                            { name: "Pulau Pinang", value: "penang" },
                            { name: "Perak", value: "perak" },
                            { name: "Perlis", value: "perlis" },
                            { name: "Putrajaya", value: "putrajaya" },
                            { name: "Sabah", value: "sabah" },
                            { name: "Sarawak", value: "sarawak" },
                            { name: "Selangor", value: "selangor" },
                            { name: "Terengganu", value: "terengganu" }
                        ];

                    states.forEach(state => {
                        let option = new Option(state.name, state.value, state.value === initialState, state.value === initialState);
                        stateDropdown.add(option);
                    });
                    if(initialState) {
                        stateDropdown.value = initialState;
                        loadCities(initialState);
                    }
                }

                stateDropdown.addEventListener('change', function() {
                    loadCities(this.value);
                });

                function loadCities(stateValue) {
                    fetch(`/data/${stateValue}.json`)
                        .then(response => response.json())
                        .then(data => {
                            populateCities(data.city);
                            if(initialCity) {
                                cityDropdown.value = initialCity;
                                loadPostcodes(stateValue, initialCity);
                            }
                        })
                        .catch(error => {
                            console.error('Failed to load city data:', error);
                            cityDropdown.innerHTML = '<option value="">Failed to load</option>';
                            cityDropdown.disabled = true;
                        });
                }

                function populateCities(cities) {
                    cityDropdown.innerHTML = '<option value="">Select City</option>';
                    cityDropdown.disabled = false;
                    cities.forEach(city => {
                        let option = new Option(city.name, city.name, city.name === initialCity, city.name === initialCity);
                        cityDropdown.add(option);
                    });
                }

                cityDropdown.addEventListener('change', function() {
                    loadPostcodes(stateDropdown.value, this.value);
                });

                function loadPostcodes(stateValue, selectedCity) {
                    fetch(`/data/${stateValue}.json`)
                        .then(response => response.json())
                        .then(data => {
                            const cityInfo = data.city.find(city => city.name === selectedCity);
                            populatePostcodes(cityInfo.postcode);
                            if(initialPostcode) {
                                postcodeDropdown.value = initialPostcode;
                            }
                        })
                        .catch(error => {
                            console.error('Failed to load postcode data:', error);
                            postcodeDropdown.innerHTML = '<option value="">Failed to load</option>';
                        });
                }

                function populatePostcodes(postcodes) {
                    postcodeDropdown.innerHTML = '<option value="">Select Postcode</option>';
                    postcodes.forEach(postcode => {
                        let option = new Option(postcode, postcode, postcode === initialPostcode, postcode === initialPostcode);
                        postcodeDropdown.add(option);
                    });
                }
            });
            </script>
            
            <div class="mb-3">
                <textarea class="form-control" rows="3" aria-describedby="emailHelp" placeholder="House Number, Building, Street Name" name="address">{{ $address }}</textarea>
                @error('address') <span class="text-danger">{{ $message }}</span>@enderror
                <small class="text-muted">Address should contain letters, numbers, spaces, and full stops only.</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Deposit <span style="color: #c4c4c4">(RM)</span></label>
                <input type="number" class="form-control" aria-describedby="emailHelp" name="deposit" value="{{ $deposit }}">
                @error('deposit') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Monthly <span style="color: #c4c4c4">(RM)</span></label>
                <input type="number" class="form-control" aria-describedby="emailHelp" name="monthly" value="{{ $monthly }}">
                @error('monthly') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Area <span style="color: #c4c4c4">(sqft)</span></label>
                <input type="number" class="form-control" aria-describedby="emailHelp" name="area" value="{{ $area }}">
                @error('area') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Description <span style="color: #c4c4c4">(optional)</span></label>
                <textarea class="form-control" rows="3" aria-describedby="emailHelp" placeholder="Add more to describe about your property" name="description">{{ $description }}</textarea>
                @error('description') <span class="text-danger">{{ $message }}</span>@enderror
                <small class="text-muted">Description should contain letters, spaces, and commas only.</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Number of Room</label>
                <select class="form-select" aria-describedby="emailHelp" aria-label="Default select example" name="room">
                    <option selected hidden value="{{ $rooms }}">{{ $rooms}}</option>
                    <option value="1" {{ old('room') == '1' ? 'selected' : '' }}>1</option>
                    <option value="2" {{ old('room') == '2' ? 'selected' : '' }}>2</option>
                    <option value="3" {{ old('room') == '3' ? 'selected' : '' }}>3</option>
                    <option value="4" {{ old('room') == '4' ? 'selected' : '' }}>4</option>
                    <option value="5" {{ old('room') == '5' ? 'selected' : '' }}>5</option>
                    <option value="6" {{ old('room') == '6' ? 'selected' : '' }}>6</option>
                    <option value="7" {{ old('room') == '7' ? 'selected' : '' }}>7</option>
                    <option value="8" {{ old('room') == '8' ? 'selected' : '' }}>8</option>
                </select>
                @error('room') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Number of Toilet</label>
                <select class="form-select" aria-describedby="emailHelp" aria-label="Default select example" name="toilet">
                    <option selected hidden value="{{ $toilet }}">{{ $toilet}}</option>
                    <option value="1" {{ old('toilet') == '1' ? 'selected' : '' }}>1</option>
                    <option value="2" {{ old('toilet') == '2' ? 'selected' : '' }}>2</option>
                    <option value="3" {{ old('toilet') == '3' ? 'selected' : '' }}>3</option>
                    <option value="4" {{ old('toilet') == '4' ? 'selected' : '' }}>4</option>
                    <option value="5" {{ old('toilet') == '5' ? 'selected' : '' }}>5</option>
                    <option value="6" {{ old('toilet') == '6' ? 'selected' : '' }}>6</option>
                    <option value="7" {{ old('toilet') == '7' ? 'selected' : '' }}>7</option>
                    <option value="8" {{ old('toilet') == '8' ? 'selected' : '' }}>8</option>
                </select>
                @error('toilet') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Type of Property</label>
                <select class="form-select" aria-describedby="emailHelp" aria-label="Default select example" name="type">
                    <option selected hidden value="{{ $value }}">{{ $type }}</option>
                    <option value="1" {{ old('type') == '1' ? 'selected' : '' }}>Bungalow/Villa</option>
                    <option value="2" {{ old('type') == '2' ? 'selected' : '' }}>Semi-D</option>
                    <option value="3" {{ old('type') == '3' ? 'selected' : '' }}>Terrace</option>
                    <option value="4" {{ old('type') == '4' ? 'selected' : '' }}>Townhouse</option>
                    <option value="5" {{ old('type') == '5' ? 'selected' : '' }}>Flat/Apartment</option>
                    <option value="6" {{ old('type') == '6' ? 'selected' : '' }}>Condominium</option>
                    <option value="7" {{ old('type') == '7' ? 'selected' : '' }}>Penthouse</option>
                    <option value="8" {{ old('type') == '8' ? 'selected' : '' }}>Shop House</option>
                </select>
                @error('type') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">New Google Maps Link <span style="color: #c4c4c4">(include longitude and latitude)</span></label>
                <input type="text" class="form-control" aria-describedby="emailHelp" name="google_maps_link" placeholder="Enter Google Maps link" value="{{ old('google_maps_link') }}">
                @error('google_maps_link') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            {{-- Display Images from database --}}
            <div>
                <label class="form-label">Property Image</label><br>
                @if ($images->isEmpty())
                <li class="alert alert-danger">Please add some images to make the property available</li>
                @else
                @foreach ($images as $image )
                <div class="position-relative d-inline-block image-container">
                    <img src="{{ asset($image->image) }}" class="border p-2 m-3 image" alt="Img">
                    <a href="{{ url('landlord/property/image/delete/'.$image->id.'') }}" class="delete-button" onclick="deleteImage({{ $image->id }})">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>
                @endforeach
                @endif
            </div>
            <div class="mb-3">
                <div id="fileInputContainer" class="mb-3">
                    <input type="file" id="imageInput" name="images[]" multiple class="form-control" style="visibility: hidden;">
                    <button type="button" id="chooseImagesBtn" class="btn" style="background-color: #17395e; color: white;"><i class="fa-solid fa-camera"></i></button>
                    <span>Add Image</span>
                    <span style="color: grey;">.JPEG,.PNG,.JPG,.WEBP (Max:20 images only)</span>
                  </div>
                  @foreach($errors->get('images.*') as $error)
                        <span class="text-danger">{{ $error[0] }}</span> <!-- Displaying the first error of each image -->
                @endforeach
                  <!-- Container to display selected images -->
                  <div id="imageContainer" class="row g-2 mb-3"></div>
            </div>
            <button type="submit" class="btn" style="background-color: #17395e; color: white;">Submit</button>
        </form>
    </div>
</div>

@endsection
