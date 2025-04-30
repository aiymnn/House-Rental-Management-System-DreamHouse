<style>
    .search-form {
        display: flex;
    }

    .search-input {
        flex: 1;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 10px 0 0 10px;
        font-size: 16px;
        width: 400px;
    }

    .search-input:focus {
        outline: none;
    }

    .search-button {
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 0 10px 10px 0;
        cursor: pointer;
        font-size: 16px;
    }

    .dropdown {
        position: relative;
        margin: 0 10px;
    }

    /* Dropdown toggle button */
    .dropdown-toggle {
        background: none;
        border: none;
        padding: 10px;
        cursor: pointer;
        color: white;
        font-weight: 400px;
    }

    button:focus {
        outline: none; /* Removes the default focus outline */
        /* Add any additional styling you want for the focused state */
    }

    /* Dropdown menu */
    .dropdown-menu {
        display: none;
        width: 180px;
        position: absolute;
        top: calc(100% + 5px);
        left: 0;
        background-color: #fff;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
        padding: 10px;
        max-height: 200px; /* Default max-height */
        overflow-y: auto;
    }

    /* Dropdown menu labels */
    .dropdown-menu label {
        display: block;
        margin-bottom: 5px;
    }

    /* Show dropdown menu when toggle button is clicked */
    .dropdown.show .dropdown-menu {
        display: block;
    }

    /* Additional style for Type dropdown menu */
    .dropdown.type .dropdown-menu {
        max-height: 150px; /* Adjust the max-height as needed */
    }

    /* Additional style for Price dropdown menu */
    .dropdown.price .dropdown-menu {
        width: 700px; /* Set the width to 700px */
        max-width: 500px; /* Set a wider max-width if you want to make it wider */
    }

    .price-inputs {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .price-inputs input {
        flex: 1;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
        width: 180px; /* Adjust the width as needed */
    }

    .price-inputs input:focus {
        outline: none;
    }

    .readonly-button {
        padding: 5px 10px;
        background-color: #f0f0f0; /* Adjust the color as needed */
        border: none;
        border-right: 1px solid #ccc; /* Add a border between button and input */
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
        font-size: 14px;
        color: #333; /* Adjust the color as needed */
        cursor: default;
    }

    .readonly-button:hover {
        background-color: #e0e0e0; /* Adjust the hover color as needed */
    }

    .price-inputs input::-webkit-inner-spin-button,
    .price-inputs input::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .price-menu {
        width: 500px; /* Set the width of the dropdown menu */
    }

    .filter-field {
    position: absolute;
    top: 70%;
    left: 50%; /* Center the container horizontally */
    transform: translateX(-50%);
    justify-content: center;
    display: flex;
    margin: 10px auto; /* Adjust margin for better centering */
    padding: 50px;
    border-radius: 20px;
    background-color: rgba(26, 164, 206, 0.582);
}

@media screen and (max-width: 768px) {
    .filter-field {
        width: 80%; /* Adjust the width for smaller screens */
    }
}

@media screen and (max-width: 576px) {
    .filter-field {
        width: 90%; /* Adjust the width for even smaller screens */
    }
}

/* Adjustments for smaller screens */
@media screen and (max-width: 1400px) {
        .masthead-video-section {
            margin-top: 125px;
        }

        .masthead-video {
            position: relative;
        }

        .filter-field {
            position: absolute;
            top: 45px;
            left: 0;
            width: 100%; /* Full width */
            height: 120px; /* Full width */
            max-width: none; /* Remove max-width */
            transform: none; /* Remove transform */
            padding: 20px; /* Adjust padding */
            border-radius: 0; /* Remove border radius */
            background-color: rgb(42, 117, 164);
        }

        .filter-field form {
            justify-content: flex-start; /* Align form items to the start */
        }

        .filter-field .search-form {
            flex-direction: column; /* Stack search input and dropdowns */
            align-items: center; /* Center items vertically */
        }

        .filter-field .search-form > div {
            margin-bottom: 10px; /* Add spacing between elements */
        }
    }


</style>

<div class="filter-field">
<div class="mt-2" style="display: flex; justify-content: center;">
    <form action="/property" method="GET" class="search-form">
        <div style="display: flex; flex-direction: column; align-items: center;"> <!-- Added container for inputs and dropdowns -->
            <div style="display: flex; align-items: center;"> <!-- Container for search input and button -->
                <input type="text" name="search" placeholder="Search..." class="search-input">
                <button type="submit" class="search-button">Search</button>
            </div>

            <div style="display: flex;"> <!-- Container for dropdowns -->
                <div class="dropdown type">
                    <button type="button" class="dropdown-toggle">Type</button>
                    <div class="dropdown-menu">
                        <label><input type="checkbox" name="type" value="1"> Bungalow/Villa</label>
                        <label><input type="checkbox" name="type" value="2"> Semi-D</label>
                        <label><input type="checkbox" name="type" value="3"> Terrace</label>
                        <label><input type="checkbox" name="type" value="4"> Townhouse</label>
                        <label><input type="checkbox" name="type" value="5"> Flat/Apartment</label>
                        <label><input type="checkbox" name="type" value="6"> Condominium</label>
                        <label><input type="checkbox" name="type" value="7"> Penthouse</label>
                        <label><input type="checkbox" name="type" value="8"> Shop House</label>
                    </div>
                </div>

                <div class="dropdown price">
                    <button type="button" class="dropdown-toggle">Price</button>
                    <div class="dropdown-menu price-menu">
                        <div class="price-inputs">
                            <div class="price-input-container">
                                <label for="min_price">Min:</label>
                                <div class="input-group">
                                    <button class="readonly-button">RM</button>
                                    <input type="number" id="min_price" name="min_price" placeholder="Min" class="price-min">
                                </div>
                            </div>

                            <div class="price-input-container">
                                <label for="max_price">Max:</label>
                                <div class="input-group">
                                    <button class="readonly-button">RM</button>
                                    <input type="number" id="max_price" name="max_price" placeholder="Max" class="price-max">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dropdown">
                    <button type="button" class="dropdown-toggle">Bedroom</button>
                    <div class="dropdown-menu">
                        <label><input type="checkbox" name="bedroom" value="1"> 1 Bedroom</label>
                        <label><input type="checkbox" name="bedroom" value="2"> 2 Bedroom</label>
                        <label><input type="checkbox" name="bedroom" value="3"> 3 Bedroom</label>
                        <label><input type="checkbox" name="bedroom" value="4"> 4 Bedroom</label>
                        <label><input type="checkbox" name="bedroom" value="5"> 5+ Bedroom</label>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

        dropdownToggles.forEach(function(dropdownToggle) {
            dropdownToggle.addEventListener('click', function() {
                const parentDropdown = this.parentNode;
                parentDropdown.classList.toggle('show');

                const allDropdowns = document.querySelectorAll('.dropdown');
                allDropdowns.forEach(function(dropdown) {
                    if (dropdown !== parentDropdown) {
                        dropdown.classList.remove('show');
                    }
                });
            });
        });

        const dropdownItems = document.querySelectorAll('.dropdown-menu label');

        dropdownItems.forEach(function(item) {
            item.addEventListener('click', function(e) {
                e.stopPropagation();
                const checkbox = this.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
            });
        });

        document.addEventListener("click", function(e) {
            if (!e.target.matches('.dropdown-toggle') && !e.target.closest('.dropdown-menu')) {
                const dropdownMenus = document.querySelectorAll('.dropdown-menu');
                dropdownMenus.forEach(function(dropdownMenu) {
                    dropdownMenu.parentNode.classList.remove('show');
                });
            }
        });
    });
</script>
