<style>
.profile-link {
    text-decoration: none; /* Remove underline from link */
    color: inherit; /* Inherit text color */
    display: block; /* Make the anchor block level to contain the card */
}

.profile-card {
    width: 100%;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease;
}

.profile-card:hover {
    transform: scale(1.02); /* Slightly enlarge the card on hover */
}

.profile-header {
    background-color: #023d90;
    color: white;
    padding: 10px;
    text-align: center;
}

.profile-body {
    display: flex;
    align-items: center;
    padding: 20px;
}

.profile-image {
    flex-shrink: 0;
    margin-right: 20px;
}

.profile-image img {
    border-radius: 50%;
    width: 150px;
    height: 150px;
    object-fit: cover;
}

.profile-info {
    flex: 1;
}

.profile-row {
    display: flex;
    margin: 5px 0;
}

.profile-row .label {
    flex: 0 0 100px; /* Adjust the width of the label as needed */
    font-weight: bold;
}

.profile-row .value {
    flex: 1;
}

</style>

<div class="container mt-3">
    <a href="{{ url('agent/profile') }}" class="profile-link">
        <div class="profile-card">
            <div class="profile-header">
                <h2>Profile</h2>
            </div>
            <div class="profile-body">
                <div class="profile-image">
                    <img src="{{ asset(Auth::guard('staff')->user()->avatar) }}" alt="Profile Image">
                </div>
                <div class="profile-info">
                    <div class="profile-row">
                        <span class="label">Name:</span>
                        <span class="value">{{ Auth::guard('staff')->user()->name }}</span>
                    </div>
                    <div class="profile-row">
                        <span class="label">Email:</span>
                        <span class="value">{{ Auth::guard('staff')->user()->email }}</span>
                    </div>
                    <div class="profile-row">
                        <span class="label">Phone:</span>
                        <span class="value">{{ substr(Auth::guard('staff')->user()->phone, 0, 3) }}-{{ substr(Auth::guard('staff')->user()->phone, 3) }}</span>
                    </div>
                    <div class="profile-row">
                        <span class="label">IC Number:</span>
                        <span class="value">{{ substr(Auth::guard('staff')->user()->number_ic, 0, 6) }}-{{ substr(Auth::guard('staff')->user()->number_ic, 6, 2) }}-{{ substr(Auth::guard('staff')->user()->number_ic, 8) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>
