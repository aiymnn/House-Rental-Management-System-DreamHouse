<header class="header" id="header">
    <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle"></i> </div>
    <div class="row align-items-center">
        <div class="col-auto">
            <div class="header_img">

                @if(Auth::guard('tenant')->check())
                    @if(Auth::guard('tenant')->user()->avatar)
                        <img src="{{ asset(Auth::guard('tenant')->user()->avatar) }}" alt="Avatar">
                    @else
                        <img src="{{ asset('uploads/avatar/null.png') }}" alt="Avatar">
                    @endif
                @endif

            </div>
        </div>
        <div class="col" style="margin-left: -20px;"> <!-- Adjust the negative margin to your preference -->
            @if(Auth::guard('tenant')->check())
            {{ Auth::guard('tenant')->user()->name }}
        @endif
        </div>
    </div>
</header>
<div class="l-navbar" id="nav-bar">
    <nav class="nav">
        <div>
            <a href="{{ route('tenant_dashboard') }}" class="nav_logo"> <i class='bx bx-layer nav_logo-icon'></i> <span
                    class="nav_logo-name">Tenant</span> </a>
            <div class="nav_list">
                <a href="{{ route('tenant_dashboard') }}" class="nav_link @if (Request::segment(2) =='dashboard') active @endif">
                    <i class='bx bx-grid-alt nav_icon'></i>
                    <span class="nav_name">Dashboard</span>
                </a>
                <a href="{{ route('tenant_explore') }}" class="nav_link @if (Request::segment(2) =='explore') active @endif">
                    <i class='bx bx-search-alt-2'></i>
                    <span class="nav_name">Explore</span>
                </a>
                <a href="{{ route('tappointment') }}" class="nav_link @if (Request::segment(2) =='appointment') active @endif">
                    <i class='bx bxs-calendar'></i>
                    <span class="nav_name">Appointment</span>
                </a>
                <a href="{{ route('tenant_contract') }}" class="nav_link @if (Request::segment(2) =='contract') active @endif">
                    <i class='bx bxs-receipt'></i>
                    <span class="nav_name">Contract</span>
                </a>
                <a href="{{ route('tenant_payment') }}" class="nav_link @if (Request::segment(2) =='payment') active @endif">
                    <i class='bx bxs-wallet-alt'></i>
                    <span class="nav_name">Payment</span>
                </a>
                <a href="{{ route('tenant_report') }}" class="nav_link @if (Request::segment(2) =='report') active @endif">
                    <i class='bx bxs-notepad'></i>
                    <span class="nav_name">Report</span>
                </a>
                <a href="{{ route('tenant_profile') }}" class="nav_link @if (Request::segment(2) =='profile') active @endif">
                    <i class='bx bxs-user'></i>
                    <span class="nav_name">Profile</span>
                </a>
            </div>
        </div>
        <a href="{{ route('tenant_logout') }}" class="nav_link">
            <i class='bx bx-log-out nav_icon'></i>
            <span class="nav_name">SignOut</span>
        </a>
    </nav>
</div>
