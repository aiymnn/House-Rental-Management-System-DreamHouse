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

    /* Media query for smaller screens */
    @media screen and (max-width: 576px) {
        .badge {
            width: calc(50% - 15px); /* Adjusted for smaller screens */
        }
    }

    </style>

    <div class="badges-container">
        <div class="badge blue">
            <i class="fa-solid fa-building-columns"></i>
            <div class="badge-info">
                <span class="badge-name">Income</span>
                <span class="badge-count">{{ $totalPayments }}</span>
            </div>
        </div>
        <div class="badge red">
            <i class="fa-solid fa-building"></i>
            <div class="badge-info">
                <span class="badge-name">Property</span>
                <span class="badge-count">{{ $totalProperties }}</span>
            </div>
        </div>
        <div class="badge orange">
            <i class="fa-solid fa-file-contract"></i>
            <div class="badge-info">
                <span class="badge-name">Contract</span>
                <span class="badge-count">{{ $totalContracts }}</span>
            </div>
        </div>
        <div class="badge purple">
            <i class="fa-solid fa-user-tie"></i>
            <div class="badge-info">
                <span class="badge-name">Staff</span>
                <span class="badge-count">{{ $totalStaffs }}</span>
            </div>
        </div>
        <div class="badge yellow">
            <i class="fa-solid fa-person-chalkboard"></i>
            <div class="badge-info">
                <span class="badge-name">Landlord</span>
                <span class="badge-count">{{ $totalLandlords }}</span>
            </div>
        </div>
        <div class="badge green">
            <i class="fa-solid fa-people-roof"></i>
            <div class="badge-info">
                <span class="badge-name">Tenant</span>
                <span class="badge-count">{{ $totalTenants }}</span>
            </div>
        </div>
    </div>
