
<aside id="sidebar">
    <div class="d-flex">
        <button class="toggle-btn" type="button">
            <i class="lni lni-grid-alt"></i>
        </button>
        <div class="sidebar-logo">
            <a href="#">Staff</a>
        </div>
    </div>
    <ul class="sidebar-nav">
        <li class="sidebar-item">
            <a href="{{ route('staff_dashboard') }}" class="sidebar-link @if (Request::segment(2) =='dashboard') active @endif">
                <i class='bx bxs-tachometer' ></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('staff_property') }}" class="sidebar-link @if (Request::segment(2) =='property') active @endif">
                <i class='bx bxs-building-house' ></i>
                <span>Property</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="#" class="sidebar-link @if (Request::segment(2) =='user') active @endif collapsed has-dropdown" data-bs-toggle="collapse"
                data-bs-target="#auth" aria-expanded="false" aria-controls="auth">
                <i class='bx bxs-user' ></i>
                <span>Users</span>
            </a>
            <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                <li class="sidebar-item">
                    <a href="{{ url('staff/user/agent')}}" class="sidebar-link @if (Request::segment(3) =='agent') active @endif">Agent</a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ url('staff/user/landlord') }}" class="sidebar-link @if (Request::segment(3) =='landlord') active @endif">Landlord</a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ url('staff/user/tenant') }}" class="sidebar-link @if (Request::segment(3) =='tenant') active @endif">Tenant</a>
                </li>
            </ul>
        </li>
        <li class="sidebar-item">
            <a href="{{ url('staff/contract') }}" class="sidebar-link @if (Request::segment(2) =='contract') active @endif">
                <i class='bx bxs-receipt'></i>
                <span>Contract</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{ url('staff/report') }}" class="sidebar-link @if (Request::segment(2) =='report') active @endif">
                <i class="lni lni-popup"></i>
                <span>Complaint</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{ url('staff/profile') }}" class="sidebar-link @if (Request::segment(2) =='profile') active @endif">
                <i class='bx bxs-user-account' ></i>
                <span>Profile</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{ url('staff/setting') }}" class="sidebar-link @if (Request::segment(2) =='setting') active @endif">
                <i class="lni lni-cog"></i>
                <span>Setting</span>
            </a>
        </li>
    </ul>
    <div class="sidebar-footer">
        <a href="{{ route('staff_logout') }}" class="sidebar-link">
            <i class="lni lni-exit"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>
