<style>
.custom-appointment-link {
    text-decoration: none; /* Remove underline from link */
    color: inherit; /* Inherit text color */
    display: block; /* Make the anchor block level to contain the card */
}

.custom-appointment-card {
    width: 100%;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease;
}

.custom-appointment-card:hover {
    transform: scale(1.02); /* Slightly enlarge the card on hover */
}

.custom-appointment-header {
    background-color: #023d90;
    color: white;
    padding: 10px;
    text-align: center;
}

.custom-appointment-body {
    padding: 20px;
}

.custom-appointment-info {
    display: table;
    width: 100%;
    border-collapse: collapse;
}

.custom-appointment-row {
    display: table-row;
    width: 100%;
}

.custom-appointment-row.custom-header {
    background-color: #f4f4f4;
    font-weight: bold;
    text-align: left;
}

.custom-cell {
    display: table-cell;
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

.custom-appointment-row.custom-header .custom-cell {
    border-bottom: 2px solid #ddd;
    font-weight: bold;
    text-align: left;
    background-color: #f4f4f4;
}

.custom-cell[colspan="5"] {
    text-align: center;
}


</style>

<div class="container mt-3">
    <a href="{{ url('agent/appointment') }}" class="custom-appointment-link">
        <div class="custom-appointment-card">
            <div class="custom-appointment-header">
                <h2>Appointment</h2>
            </div>
            <div class="custom-appointment-body">
                <div class="custom-appointment-info">
                    @if($appointments1->isEmpty())
                        <div class="custom-appointment-row">
                            <span class="custom-cell" colspan="5" style="text-align: center;">No Appointments Pending</span>
                        </div>
                    @else
                    <div class="custom-appointment-row custom-header">
                        <span class="custom-cell">Guest</span>
                        <span class="custom-cell">Location</span>
                        <span class="custom-cell">Date</span>
                        <span class="custom-cell">Time</span>
                        <span class="custom-cell">Status</span>
                    </div>
                        @foreach($appointments1 as $appointment1)
                            <div class="custom-appointment-row">
                                <span class="custom-cell">{{ $appointment1->tenant->name }}</span>
                                <span class="custom-cell">{{ $appointment1->property->address }}</span>
                                <span class="custom-cell">{{ date('d-m-Y', strtotime($appointment1->date)) }}</span>
                                <span class="custom-cell">{{ $appointment1->time }}</span>
                                <span class="custom-cell">
                                    @if ($appointment1->status == 1)
                                        Proceed
                                    @elseif ($appointment1->status == 2)
                                        Pending
                                    @else
                                        Cancelled
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </a>
</div>






