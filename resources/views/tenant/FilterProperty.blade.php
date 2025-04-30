<!-- guest/FilterProperty.blade.php -->
<style>
    /* Existing style code */
    .search-form {
        display: flex;
    }

    .search-input {
        flex: 1;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 10px 0 0 10px;
        font-size: 16px;
        width: 100%; /* Modified to adjust to different screen sizes */
        max-width: 500px; /* Added max-width for better responsiveness */
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
        max-width: 800px; /* Set a wider max-width if you want to make it wider */
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
        width: 100%; /* Modified to adjust to different screen sizes */
        max-width: 180px; /* Added max-width for better responsiveness */
        margin-right: 10px;
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
        width: 300px; /* Set the width of the dropdown menu */
    }

    /* Media queries for responsiveness */
    @media screen and (max-width: 768px) {
        .search-input {
            max-width: 400px; /* Adjusted max-width for smaller screens */
        }
    }

    @media screen and (max-width: 576px) {
        .search-input {
            max-width: 300px; /* Adjusted max-width for even smaller screens */
        }

        .dropdown-menu {
            max-width: 250px; /* Adjusted max-width for smaller screens */
        }

        .price-inputs input {
            max-width: 150px; /* Adjusted max-width for smaller screens */
        }
    }

    @media screen and (min-width: 768px) {
        .dropdown.show {
            width: auto !important; /* Override the width rule */
        }
    }

</style>

<div class="mt-2" style="display: flex; justify-content: center;">
    <form action="http://127.0.0.1:8000/property" method="GET" class="search-form">
        <input type="text" name="q" placeholder="Search..." class="search-input">
        <button type="submit" class="search-button">Search</button>
    </form>
</div>

<div class="mt-2" style="display: flex; justify-content: center;">
    <div class="dropdown type"> <!-- Added "type" class here -->
        <button class="dropdown-toggle">Type</button>
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

    <div class="dropdown">
        <button class="dropdown-toggle">Price</button>
        <div class="dropdown-menu price-menu">
            <div class="price-inputs">
                <div class="price-input-container">
                    <label for="min_price">Min:</label>
                    <div class="input-group"> <!-- Wrap the input and button in a container -->
                        <button class="readonly-button">RM</button>
                        <input type="number" id="min_price" name="min_price" placeholder="Min" class="price-min">
                    </div>
                </div>

                <div class="price-input-container">
                    <label for="max_price">Max:</label>
                    <div class="input-group"> <!-- Wrap the input and button in a container -->
                        <button class="readonly-button">RM</button>
                        <input type="number" id="max_price" name="max_price" placeholder="Max" class="price-max">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="dropdown">
        <button class="dropdown-toggle">Bedroom</button>
        <div class="dropdown-menu">
            <label><input type="checkbox" name="bedroom" value="1"> 1 Bedroom</label>
            <label><input type="checkbox" name="bedroom" value="2"> 2 Bedroom</label>
            <label><input type="checkbox" name="bedroom" value="3"> 3 Bedroom</label>
            <label><input type="checkbox" name="bedroom" value="4"> 4 Bedroom</label>
            <label><input type="checkbox" name="bedroom" value="5"> 5+ Bedroom</label>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

        dropdownToggles.forEach(function(dropdownToggle) {
            dropdownToggle.addEventListener('click', function() {
                const parentDropdown = this.parentNode;
                parentDropdown.classList.toggle('show');

                // Close other dropdowns and their selections
                const allDropdowns = document.querySelectorAll('.dropdown');
                allDropdowns.forEach(function(dropdown) {
                    if (dropdown !== parentDropdown) {
                        dropdown.classList.remove('show');
                    }
                });
            });
        });

        // Toggle checkboxes when clicking on dropdown menu items
        const dropdownItems = document.querySelectorAll('.dropdown-menu label');

        dropdownItems.forEach(function(item) {
            item.addEventListener('click', function(e) {
                e.stopPropagation(); // Prevent closing the dropdown when clicking on checkboxes
                const checkbox = this.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
            });
        });

        // Close dropdown menu when clicking outside
        document.addEventListener("click", function(e) {
            if (!e.target.matches('.dropdown-toggle') && !e.target.closest('.dropdown-menu')) {
                const dropdownMenus = document.querySelectorAll('.dropdown-menu');
                dropdownMenus.forEach(function(dropdownMenu) {
                    dropdownMenu.parentNode.classList.remove('show');
                });
            }
        });

        // Extract URL parameters and set form values
        const urlParams = new URLSearchParams(window.location.search);

        // Set search input value
        const searchInput = document.querySelector('.search-input');
        if (urlParams.has('q')) {
            searchInput.value = urlParams.get('q');
        }

        // Set checkboxes
        const checkboxTypes = urlParams.getAll('type');
        checkboxTypes.forEach(value => {
            const checkbox = document.querySelector(`input[name="type"][value="${value}"]`);
            if (checkbox) {
                checkbox.checked = true;
            }
        });

        const checkboxBedrooms = urlParams.getAll('bedroom');
        checkboxBedrooms.forEach(value => {
            const checkbox = document.querySelector(`input[name="bedroom"][value="${value}"]`);
            if (checkbox) {
                checkbox.checked = true;
            }
        });

        // Set price input values
        const minPriceInput = document.getElementById('min_price');
        if (urlParams.has('min_price')) {
            minPriceInput.value = urlParams.get('min_price');
        }

        const maxPriceInput = document.getElementById('max_price');
        if (urlParams.has('max_price')) {
            maxPriceInput.value = urlParams.get('max_price');
        }
    });
</script>
