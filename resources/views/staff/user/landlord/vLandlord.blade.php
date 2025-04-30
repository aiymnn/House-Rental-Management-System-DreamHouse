@extends('layouts.staff-layout')

@section('title', 'DreamHouse • Landlord')

@section('content')


<style>
    .details-container {
        color: #333;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        padding: 15px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        overflow: hidden;
    }

    .details-header {
        cursor: pointer;
        padding-bottom: 10px;
        border-bottom: 1px solid #dee2e6;
        margin-bottom: 15px;
    }

    .details-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 5px;
    }

    .details-table th, .details-table td {
        border: 1px solid #dee2e6;
        padding: 6px;
        text-align: left;
    }

    .details-table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
</style>


<div class="notice" style="overflow: hidden;">
    @include('landlord.property.success-message')
</div>

<div class="details-container">
    <a href="{{ route('landlord_register') }}" class="btn float-end" style="background-color: #17395e; color: white;">New Landlord</a>
    <div class="details-header" id="paymentDetailHeader">
        <h4>List of Landlords
        </h4>
    </div>
    <div class="card-body" id="paymentDetailBody">
        @if($landlords->isEmpty())
            <div class="text-center p-3">
                <h3 class="text-secondary">You don't have any landlords yet.</h3>
            </div>
        @else
        <table class="details-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>IC Number</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Total Property</th>
                    <th>Status</th>
                    <th>Registered</th>
                    <th>Action</th>
                </tr>
            </thead>
            <div class="d-flex flex-wrap justify-content-end align-items-center mb-3">
                <input type="text" id="searchInput" class="form-control me-2 mb-2" placeholder="Search by name or IC number" style="max-width: 280px;">
                <div class="form-check form-check-inline mb-2">
                    <input class="form-check-input" type="checkbox" id="onlineCheckbox">
                    <label class="form-check-label ml-2" for="onlineCheckbox">Online</label>
                </div>
                <div class="form-check form-check-inline mb-2">
                    <input class="form-check-input" type="checkbox" id="offlineCheckbox">
                    <label class="form-check-label ml-2" for="offlineCheckbox">Offline</label>
                </div>
            </div>
            <tbody id="landlordsTableBody">
                @foreach ($landlords as $landlord)
                <tr>
                    <td style="text-align: center">{{$landlord->id}}</td>
                    <td style="text-align: center">{{$landlord->name}}</td>
                    <td style="text-align: center">{{ substr($landlord->number_ic, 0, 6) }}-{{ substr($landlord->number_ic, 6, 2) }}-{{ substr($landlord->number_ic, 8) }}</td>
                    <td style="text-align: center">{{ substr($landlord->phone, 0, 3) }}-{{ substr($landlord->phone, 3) }}</td>
                    <td style="text-align: center">{{$landlord->email}}</td>
                    <td style="text-align: center">{{ $landlord->assigned_properties_count }}</td>
                    <td style="text-align: center">
                        @if ($landlord->status == 1)
                        <a href="{{ url('staff/landlord/offline/'.$landlord->id.'')}}" class="btn btn-success">Online</a>
                        {{-- <a href="{{ url('staff/property/approval/'.$property->id.'') }}" class="btn btn-success">Approval</a> --}}
                        @else
                        <a href="{{ url('staff/landlord/online/'.$landlord->id.'')}}" class="btn btn-danger">Offline</a>
                        @endif
                    </td>
                    <td style="text-align: center">{{ \Carbon\Carbon::parse($landlord->created_at)->format('d-m-Y') }}</td>
                    <td style="text-align: center">
                        <a href="{{ url('staff/user/landlord/view/'.$landlord->id.'') }}" class="col" style="color:green; margin-right: 10px;" title="View"><span><i class="fa-solid fa-eye"></i></span></i></a>
                        <a href="{{ url('staff/user/landlord/edit/'.$landlord->id.'') }}" class="col" style="color:blue; margin-right: 10px;" title="Edit"><span><i class="fa-solid fa-pen-to-square"></i></span></a>
                        {{-- <a href="{{ url('landlord/property/image/'.$property->id.'') }}" class="col" style="color: orange; margin-right: 10px;" title="Update Image"><span><i class="fa-solid fa-image"></i></span></a> --}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p id="notFoundMessage" class="text-center d-none" style="width: 100%">Not found</p>
        @endif
    </div>
</div>

<!-- JavaScript for filtering -->
<script>
    // Function to filter landlords based on search input and checkboxes
    function filterLandlords() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const onlineCheckbox = document.getElementById('onlineCheckbox').checked;
        const offlineCheckbox = document.getElementById('offlineCheckbox').checked;

        const landlordsTableBody = document.getElementById('landlordsTableBody');
        const notFoundMessage = document.getElementById('notFoundMessage');

        let foundLandlords = 0;

        Array.from(landlordsTableBody.children).forEach(row => {
            const name = row.children[1].innerText.toLowerCase();
            const icNumber = row.children[2].innerText.toLowerCase();
            const status = row.children[6].innerText.toLowerCase();

            const showRow = name.includes(searchInput) || icNumber.includes(searchInput);

            if (onlineCheckbox && offlineCheckbox) {
                // Both checkboxes are checked, show all rows
                row.style.display = showRow ? '' : 'none';
            } else if (onlineCheckbox) {
                // Only Online checkbox is checked
                row.style.display = (showRow && status.includes('online')) ? '' : 'none';
            } else if (offlineCheckbox) {
                // Only Offline checkbox is checked
                row.style.display = (showRow && status.includes('offline')) ? '' : 'none';
            } else {
                // No checkbox is checked
                row.style.display = showRow ? '' : 'none';
            }

            if (row.style.display !== 'none') {
                foundLandlords++;
            }
        });

        // Show "Not found" message if no landlords are found
        notFoundMessage.classList.toggle('d-none', foundLandlords > 0);
    }

    // Add event listeners to trigger filtering
    document.getElementById('searchInput').addEventListener('input', filterLandlords);
    document.getElementById('onlineCheckbox').addEventListener('change', filterLandlords);
    document.getElementById('offlineCheckbox').addEventListener('change', filterLandlords);

    // Initial filtering
    filterLandlords();
</script>

@endsection
