
<style>
    *{
      font-family: "Nunito", sans-serif;
    }
    .navbar-nav-center {
      display: flex;
      justify-content: center;
      flex: 1;
    }

    .navbar-nav-center .nav-link {
      position: relative;
      text-decoration: none;
      margin-right: 10px;
    }

    .navbar-nav-center .nav-link::before {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: -2px;
      left: 0;
      background-color: #007bff; /* Change color as needed */
      transition: width 0.3s ease;
      border-radius: 5px; /* For rounded underline */
    }

    .navbar-nav-center .nav-link:hover::before {
      width: 100%;
    }

    .navbar-nav-center .nav-link.active::before { /* Underline for active button */
      width: 100%;
      background-color: #007bff; /* Change color as needed */
    }
</style>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <!-- Logo -->
    <a class="navbar-brand" href="#">
        <img class="logo" src="{{ asset('uploads/ads/logo.png') }}" alt="" style="width: 50px; height: 40px;">
        <label style="font-weight: bold;">DreamHouse</label>
    </a>

      <!-- Navbar toggler button (hamburger menu) -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navbar links and login dropdown -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav navbar-nav-center mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link @if (Request::segment(1) =='home') active @endif" href="{{ route('welcome')}}">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link @if (Request::segment(1) =='property') active @endif" href="{{ route('property')}}">Property</a>
          </li>
          @if(Auth::guard('tenant')->check())
          <li class="nav-item">
            <a class="nav-link @if (Request::segment(1) =='appointment') active @endif" href="{{ route('appointment')}}">Appointment</a>
          </li>
          @endif
          <li class="nav-item">
            <a class="nav-link @if (Request::segment(1) =='about') active @endif" href="{{ route('about') }}">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link @if (Request::segment(1) =='contact') active @endif" href="{{ route('contact') }}">Contact Us</a>
          </li>
          {{-- <li class="nav-item">
            <a class="nav-link @if (Request::segment(1) =='agent') active @endif" href="#">Agent</a>
          </li> --}}
        </ul>

        <!-- Login dropdown at the right -->
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item dropdown">
            @if(Auth::guard('tenant')->check())
            <a class="nav-link dropdown-toggle" href="#" id="loginDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-weight: bold;">
                {{ Auth::guard('tenant')->user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="loginDropdown">
                {{-- <li><a class="dropdown-item" href="#">Profile</a></li> --}}
                <li><a class="dropdown-item" href="{{ route('tenant_logout') }}">Logout</a></li>
            </ul>
            @else
            <a class="nav-link dropdown-toggle" href="#" id="loginDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-weight: bold;">
                Login
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="loginDropdown">
                <li><a class="dropdown-item" href="{{ route('tenant_login') }}">Login / SignUp</a></li>
                <li><a class="dropdown-item" href="{{ route('landlord_login') }}">Landlord</a></li>
                <li><a class="dropdown-item" href="{{ route('staff_login') }}">Staff</a></li>
            </ul>
            @endif
          </li>
        </ul>
      </div>
    </div>
  </nav>
