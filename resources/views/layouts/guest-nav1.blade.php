<style>
    nav {
        font-family: "Nunito", sans-serif;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #007bff; /* Change background color to blue */
        padding: 6px 20px;
    }

    nav a {
        color: #fff;
        text-decoration: none;
        position: relative;
        transition: color 0.3s, background-color 0.3s, border-radius 0.3s; /* Smooth transitions */
        border-radius: 20px; /* Rounded corners */
        padding: 8px 18px; /* Same padding for all buttons */
    }

    nav a:hover:not(.logo a), /* Apply hover effect to all links except logo */
    nav a.active:not(.logo a) { /* Apply active effect to all links except logo */
        background-color: #fff;
        color: #007bff;
        border-radius: 20px; /* Rounded corners */
    }

    .logo a {
        font-size: 24px;
        padding: 0; /* Remove padding for logo */
    }

    .logo a:hover,
    .logo a.active {
        background-color: transparent;
        color: #fff;
        border-radius: 0; /* Default styles for logo */
    }

    ul {
        display: flex;
        flex-direction: row; /* Display buttons horizontally by default */
        list-style: none;
        margin-top: 0;
        padding-right: 80px;
    }

    ul li {
        position: relative;
    }

    ul li a {
        padding: 10px 20px; /* Same padding for all buttons */
    }

    /* Add margin between buttons */
    ul li:not(:last-child) {
        margin-right: 10px; /* Adjust the value as needed */
    }

    /* Toggle button */
    .toggle-button {
        display: none; /* Initially hide the toggle button */
    }

    .vertical-menu{
        font-family: "Nunito", sans-serif;
    }

    /* Media query for small screens */
    @media screen and (max-width: 990px) {
        ul {
            display: none; /* Hide the list of buttons for small screens */
        }

        .login {
            display: none; /* Hide the login button for small screens */
        }

        .toggle-button {
            display: block; /* Display toggle button for small screens */
        }

        .vertical-menu {
            display: none; /* Initially hide the vertical menu */
            flex-direction: column; /* Display buttons vertically */
            background-color: #1964b5; /* Match background color with nav */
            padding: 10px 10px; /* Add padding */
        }

        .vertical-menu a {
            padding: 10px 20px; /* Same padding for all buttons */
            color: #fff; /* Text color */
            text-decoration: none; /* Remove underline */
            transition: background-color 0.3s, border-radius 0.3s; /* Smooth transitions */
            border-radius: 20px; /* Rounded corners */
            margin-bottom: 10px; /* Add margin between links */
        }

        .vertical-menu a:hover,
        .vertical-menu a.active {
            background-color: #fff; /* Hover background color */
            color: #007bff; /* Hover text color */
            border-radius: 20px; /* Rounded corners */
        }
    }
</style>

<nav>
    <div class="logo">
        <a href="#">PropertyGuru</a>
    </div>
    <ul class="horizontal-menu" style="margin-top: 15px;">
        <li><a href="{{ route('welcome') }}" class="@if (Request::segment(1) =='home') active @endif">Home</a></li>
        <li><a href="{{ url('property') }}" class="@if (Request::segment(1) =='property') active @endif">Property</a></li>
        <li><a href="#" class=" @if(Request::segment(1) =='About') active @endif">About</a></li>
        <li><a href="#" class=" @if(Request::segment(1) =='About') active @endif">Contact Us</a></li>
    </ul>
    <div class="login">
        <a href="#">Login</a>
    </div>
    <div class="toggle-button">
        <a href="#" onclick="toggleMenu()"><i class="fa fa-bars" aria-hidden="true"></i></a>
    </div>
</nav>

<!-- Vertical menu for small screens -->
<ul class="vertical-menu">
    <br>
    <li><a href="#" class="@if (Request::segment(1) =='home') active @endif">Home</a></li>
    <br>
    <li><a href="#" class="@if (Request::segment(1) =='property') active @endif">Property</a></li>
    <br>
    <li><a href="{{ route('tenant_login') }}" class="@if (Request::segment(1) =='about') active @endif">About</a></li>
    <br>
    <li><a href="#" class="@if (Request::segment(1) =='contact') active @endif">Contact Us</a></li>
    <br>
    <li><a href="{{ route('tenant_login') }}" class="@if (Request::segment(1) =='login') active @endif">Login</a></li>
    <br>
</ul>

<script>
    // Function to toggle vertical menu visibility
    function toggleMenu() {
        var verticalMenu = document.querySelector('.vertical-menu');
        if (verticalMenu.style.display === "block") {
            verticalMenu.style.display = "none";
        } else {
            verticalMenu.style.display = "block";
        }
    }

    // Function to hide vertical menu when screen width exceeds 880px
    function hideMenuOnLargeScreens() {
        var verticalMenu = document.querySelector('.vertical-menu');
        if (window.innerWidth > 990) {
            verticalMenu.style.display = "none";
        }
    }

    // Call the function on page load and window resize
    window.onload = hideMenuOnLargeScreens;
    window.onresize = hideMenuOnLargeScreens;
</script>
