<header class="header" id="header">
    <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle"></i> </div>
    <div class="row align-items-center">
        <div class="col-auto">
            <div class="header_img">

                @if(Auth::guard('landlord')->check())
                    <img src="{{ asset(Auth::guard('landlord')->user()->avatar) }}" alt="Avatar">
                @endif
            </div>
        </div>
        <div class="col" style="margin-left: -20px;"> <!-- Adjust the negative margin to your preference -->
            @if(Auth::guard('landlord')->check())
            {{ Auth::guard('landlord')->user()->name }}
        @endif
        </div>
    </div>
</header>
<div class="l-navbar" id="nav-bar">
    <nav class="nav">
        <div>
            <a href="{{ route('landlord_dashboard') }}" class="nav_logo"> <i class='bx bx-layer nav_logo-icon'></i> <span
                    class="nav_logo-name">Landlord</span> </a>
            <div class="nav_list">
                <a href="{{ route('landlord_dashboard') }}" class="nav_link @if (Request::segment(2) =='dashboard') active @endif">
                    <i class='bx bx-grid-alt nav_icon'></i>
                    <span class="nav_name">Dashboard</span> </a>
                <a href="{{ route('landlord_property') }}" class="nav_link @if (Request::segment(2) =='property') active @endif">
                    <i class='bx bxs-building-house'></i>
                    <span class="nav_name">Property</span> </a>
                <a href="{{ route('landlord_profile') }}" class="nav_link @if (Request::segment(2) =='profile') active @endif"> <i class='bx bxs-user'></i> <span
                        class="nav_name">Profile</span> </a>
            </div>
        </div> <a href="{{ route('landlord_logout') }}" class="nav_link"> <i class='bx bx-log-out nav_icon'></i> <span
                class="nav_name">SignOut</span> </a>
    </nav>
</div>
