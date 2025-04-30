@extends('layouts.staff-layout')

@section('title', 'DreamHouse • Contract')

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

    .d-none { display: none; }
</style>

<div class="notice" style="overflow: hidden;">
    @include('landlord.property.success-message')
</div>

<div class="details-container">
    <div class="details-header" id="paymentDetailHeader">
        <h4>Contract List</h4>
    </div>
    <div class="d-flex flex-wrap justify-content-end align-items-center mb-3">
        <input type="text" id="searchInput" class="form-control me-2 mb-2" placeholder="Search by tenant/agent/address" style="max-width: 280px;">
        <div class="form-check form-check-inline mb-2">
            <input class="form-check-input" type="checkbox" id="activeCheckbox">
            <label class="form-check-label ml-2" for="activeCheckbox">Active</label>
        </div>
        <div class="form-check form-check-inline mb-2">
            <input class="form-check-input" type="checkbox" id="pendingCheckbox">
            <label class="form-check-label ml-2" for="pendingCheckbox">Pending</label>
        </div>
        <div class="form-check form-check-inline mb-2">
            <input class="form-check-input" type="checkbox" id="terminatedCheckbox">
            <label class="form-check-label ml-2" for="terminatedCheckbox">Terminated</label>
        </div>
        <div class="form-check form-check-inline mb-2">
            <input class="form-check-input" type="checkbox" id="voidedCheckbox">
            <label class="form-check-label ml-2" for="voidedCheckbox">Voided</label>
        </div>
    </div>
    <div class="card-body" id="paymentDetailBody">
        @if($contracts->isEmpty())
            <div class="text-center p-3">
                <h3 class="text-secondary">You don't have any contracts yet.</h3>
            </div>
        @else
        <table class="details-table">
            <thead>
                <tr>
                    <th>Tenant</th>
                    {{-- <th>IC Number</th> --}}
                    <th>Agent</th>
                    <th>Address</th>
                    <th>Period</th>
                    <th>Balance (RM)</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contracts as $contract)
                <tr>
                    <td style="text-align: center;">
                        <a href="{{ url('staff/user/tenant/view/'.$contract->tenant->id.'') }}" target="__blank">{{ $contract->tenant->name }}</a>
                    </td>
                    <td style="text-align: center;">
                        <a href="{{ url('staff/user/agent/view/'.$contract->agent->id.'') }}" target="__blank">{{ $contract->agent->name }}</a>
                    </td>
                    {{-- <td style="text-align: center;">{{ substr($contract->tenant->number_ic, 0, 6) }}-{{ substr($contract->tenant->number_ic, 6, 2) }}-{{ substr($contract->tenant->number_ic, 8) }}</td> --}}
                    <td>
                        <a href="{{ url('staff/property/view/'.$contract->property->id.'') }}" target="__blank">{{ $contract->property->address }}</a>
                    </td>
                    <td style="text-align: center;">{{ $contract->period}}</td>
                    <td style="text-align: center;">{{ $contract->balance}}</td>
                    <td style="text-align: center;">{{ date('d-m-Y', strtotime($contract->start_date)) }}</td>
                    <td style="text-align: center;">{{ date('d-m-Y', strtotime($contract->end_date)) }}</td>
                    <td style="text-align: center;" data-status="{{ $contract->status }}">
                        @if ($contract->status == 1)
                        Active
                        @elseif ($contract->status == 2)
                        Pending
                        @elseif ($contract->status == 3)
                        Terminated
                        @else
                        Voided
                        @endif
                    </td>
                    <td style="text-align: center">
                        <a href="{{url('staff/contract/view/'.$contract->id.'') }}" class="col" style="color:green; margin-right: 10px;" title="View"><span><i class="fa-solid fa-eye"></i></span></i></a>
                        @if($contract->status != 3 && $contract->status != 4)
                            <a href="{{ url('staff/contract/edit/'.$contract->id) }}" class="col" style="color: blue; margin-right: 10px;" title="Edit">
                                <span><i class="fa-solid fa-pen-to-square"></i></span>
                            </a>
                        @else
                            <span class="col" style="color: grey; margin-right: 10px;" title="Edit Unavailable">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </span>
                        @endif

                        <a href="{{ url('staff/contract/delete/'.$contract->id.'') }}" class="col" style="color:red; margin-right: 10px;" title="Delete" onclick="return confirm('Are you sure?');">
                            <span><i class="fa fa-trash" aria-hidden="true"></span></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div id="notFoundMessage" class="text-center d-none">
            <h3 class="text-secondary">No contracts found.</h3>
        </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const activeCheckbox = document.getElementById('activeCheckbox');
        const pendingCheckbox = document.getElementById('pendingCheckbox');
        const terminatedCheckbox = document.getElementById('terminatedCheckbox');
        const voidedCheckbox = document.getElementById('voidedCheckbox');
        const tbody = document.querySelector('tbody');
        const notFoundMessage = document.getElementById('notFoundMessage');

        function filterContracts() {
            if (!tbody || !notFoundMessage) {
                console.error("Critical elements are missing in the DOM");
                return; // Exit if essential elements are missing
            }

            const text = searchInput ? searchInput.value.toLowerCase() : '';
            let foundContracts = 0; // To count visible rows

            Array.from(tbody.children).forEach(row => {
                const tenantName = row.children[0] ? row.children[0].innerText.toLowerCase() : '';
                const agentName = row.children[1] ? row.children[1].innerText.toLowerCase() : '';
                const propertyAddress = row.children[2] ? row.children[2].innerText.toLowerCase() : '';
                const status = row.children[7] ? parseInt(row.children[7].getAttribute('data-status'), 10) : null;

                const matchesText = tenantName.includes(text) || agentName.includes(text) || propertyAddress.includes(text);
                let matchesStatus = false;

                if (!activeCheckbox.checked && !pendingCheckbox.checked && !terminatedCheckbox.checked && !voidedCheckbox.checked) {
                    matchesStatus = true; // Show all if no checkboxes are checked
                } else {
                    if (activeCheckbox.checked && status === 1) matchesStatus = true;
                    if (pendingCheckbox.checked && status === 2) matchesStatus = true;
                    if (terminatedCheckbox.checked && status === 3) matchesStatus = true;
                    if (voidedCheckbox.checked && status === 4) matchesStatus = true;
                }

                row.style.display = (matchesText && matchesStatus) ? '' : 'none';
                if (matchesText && matchesStatus) foundContracts++;
            });

            notFoundMessage.classList.toggle('d-none', foundContracts > 0);
        }

        // Ensure elements are present before attaching event listeners
        if (searchInput) searchInput.addEventListener('input', filterContracts);
        if (activeCheckbox) activeCheckbox.addEventListener('change', filterContracts);
        if (pendingCheckbox) pendingCheckbox.addEventListener('change', filterContracts);
        if (terminatedCheckbox) terminatedCheckbox.addEventListener('change', filterContracts);
        if (voidedCheckbox) voidedCheckbox.addEventListener('change', filterContracts);

        if (tbody) filterContracts(); // Apply initial filter
    });
    </script>





@endsection
