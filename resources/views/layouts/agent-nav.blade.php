<header class="header" id="header">
    <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle"></i> </div>
    <div class="row align-items-center">
        <div class="col-auto">
            <div class="header_img">

                @if(Auth::guard('staff')->check())
                    <img src="{{ asset(Auth::guard('staff')->user()->avatar) }}" alt="Avatar">
                @endif
            </div>
        </div>
        <div class="col" style="margin-left: -20px;"> <!-- Adjust the negative margin to your preference -->
            @if(Auth::guard('staff')->check())
            {{ Auth::guard('staff')->user()->name }}
        @endif
        </div>
    </div>
</header>
<div class="l-navbar" id="nav-bar">
    <nav class="nav">
        <div>
            <a href="{{ route('agent_dashboard') }}" class="nav_logo"> <i class='bx bx-layer nav_logo-icon'></i> <span
                    class="nav_logo-name">Agent</span> </a>
            <div class="nav_list">
                <a href="{{ route('agent_dashboard') }}" class="nav_link @if (Request::segment(2) =='dashboard') active @endif">
                    <i class='bx bx-grid-alt nav_icon'></i>
                    <span class="nav_name">Dashboard</span> </a>
                <a href="{{ url('agent/property')}}" class="nav_link @if (Request::segment(2) =='property') active @endif">
                    <i class='bx bxs-building-house' ></i>
                    <span class="nav_name">Property</span> </a>
                {{-- <a href="{{ url('agent/tenant')}}" class="nav_link @if (Request::segment(2) =='tenant') active @endif">
                    <i class='bx bxs-user-account' ></i>
                    <span class="nav_name">Tenant</span> </a> --}}
                <a href="{{ url('agent/contract') }}" class="nav_link @if (Request::segment(2) =='contract') active @endif">
                    <i class='bx bxs-receipt'></i>
                    <span class="nav_name">Contract</span> </a>
                <a href="{{ url('agent/appointment') }}" class="nav_link @if (Request::segment(2) =='appointment') active @endif">
                    <i class='bx bxs-calendar'></i>
                    <span class="nav_name">Appoitnment</span> </a>
                <a href="{{ url('agent/report') }}" class="nav_link @if (Request::segment(2) =='report') active @endif">
                    <i class='bx bxs-bell-ring'></i>
                    <span class="nav_name">Complaint</span>
                </a>
                <a href="{{ url('agent/profile') }}" class="nav_link @if (Request::segment(2) =='profile') active @endif"> <i class='bx bxs-user'></i> <span
                        class="nav_name">Profile</span> </a>
            </div>
        </div> <a href="{{ route('staff_logout') }}" class="nav_link"> <i class='bx bx-log-out nav_icon'></i> <span
                class="nav_name">SignOut</span> </a>
    </nav>
</div>
