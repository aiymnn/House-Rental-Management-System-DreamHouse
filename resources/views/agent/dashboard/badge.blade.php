<style>
    .badges-container {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 15px; /* Reduced gap */
    }

    .badge {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 10px;
        padding: 10px; /* Reduced padding */
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
        width: 160px; /* Reduced width */
        position: relative; /* Added for positioning the notification badge */
    }

    .badge:hover {
        transform: translateY(-3px);
    }

    .badge i {
        font-size: 30px; /* Reduced icon size */
        margin-bottom: 10px;
        color: white;
    }

    .badge-info {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .badge-name {
        font-weight: bold;
        font-size: 18px; /* Reduced font size */
        color: #333;
        margin-bottom: 5px;
    }

    .badge-count {
        color: white;
        font-size: 14px; /* Reduced font size */
    }

    .blue {
        background-color: #4e89ae;
    }

    .red {
        background-color: #e15258;
    }

    .yellow {
        background-color: #f5d547;
    }

    .green {
        background-color: #82b74b;
    }

    .orange {
        background-color: #f4845f;
    }

    .purple {
        background-color: #8e44ad;
    }

    .badge-notification {
        position: absolute;
        top: 5px;
        right: 5px;
        background-color: red;
        color: white;
        border-radius: 50%;
        padding: 5px 10px;
        font-size: 12px;
        font-weight: bold;
    }

    /* Media query for smaller screens */
    @media screen and (max-width: 576px) {
        .badge {
            width: calc(50% - 15px); /* Adjusted for smaller screens */
        }
    }


    </style>

<div class="badges-container">
    <a href="{{ url('agent/property') }}" class="badge red">
        <i class="fa-solid fa-building"></i>
        <div class="badge-info">
            <span class="badge-name">Property</span>
            <span class="badge-count">{{ $totalProperties }}</span>
        </div>
    </a>
    <a href="{{ url('agent/contract') }}" class="badge orange">
        <i class="fa-solid fa-file-contract"></i>
        <div class="badge-info">
            <span class="badge-name">Contract</span>
            <span class="badge-count">{{ $totalContracts }}</span>
        </div>
    </a>
    <a href="{{ url('agent/tenant') }}" class="badge green">
        <i class="fa-solid fa-people-roof"></i>
        <div class="badge-info">
            <span class="badge-name">Tenant</span>
            <span class="badge-count">{{ $totalTenants }}</span>
        </div>
    </a>

    <a href="{{ url('agent/report') }}" class="badge blue">
        <i class="fa fa-bullhorn" aria-hidden="true"></i>
        <div class="badge-info">
            <span class="badge-name">Complaint</span>
            <span class="badge-count">{{ $totalReports }}</span>
            @if ($reportsStatus2)
            <span class="badge-notification">{{ $reportsStatus2 }}</span>
            @endif
        </div>
    </a>
</div>

