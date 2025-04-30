@extends('layouts.agent-layout')

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
        display: flex;
        justify-content: space-between; /* Ensures the title and filters are spaced out */
        align-items: center;
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

    .search-bar {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .search-input {
        width: 250px;
        padding: 5px;
        margin-right: 10px;
    }
</style>

<div class="notice" style="margin-top: 75px; overflow: hidden;">
    @include('landlord.property.success-message')
</div>

<div class="details-container">
    <div class="details-header">
        <h4>Contract List</h4>
        <div class="search-bar">
            <input type="text" id="searchInput" class="search-input" placeholder="Search by tenant, IC, address">
            <div class="mt-2">
                <label><input type="checkbox" id="activeCheckbox"> Active</label>
                <label><input type="checkbox" id="pendingCheckbox"> Pending</label>
                <label><input type="checkbox" id="terminatedCheckbox"> Terminated</label>
            </div>
            <div style="margin-left: 15px;">
                <a href="{{ url('agent/contract/create') }}" class="btn float-end" style="background-color: #023d90; color: white;">New Contract</a>
            </div>
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
                    <th>IC Number</th>
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
                    <td style="text-align: center;">{{ $contract->tenant->name }}</td>
                    <td style="text-align: center;">{{ substr($contract->tenant->number_ic, 0, 6) }}-{{ substr($contract->tenant->number_ic, 6, 2) }}-{{ substr($contract->tenant->number_ic, 8) }}</td>
                    <td>{{ $contract->property->address }}</td>
                    <td style="text-align: center;">{{ $contract->period }}</td>
                    <td style="text-align: center;">{{ $contract->balance }}</td>
                    <td style="text-align: center;">{{ date('d-m-Y', strtotime($contract->start_date)) }}</td>
                    <td style="text-align: center;">{{ date('d-m-Y', strtotime($contract->end_date)) }}</td>
                    <td style="text-align: center;" class="status">{{ $contract->status == 1 ? 'Active' : ($contract->status == 2 ? 'Pending' : 'Terminated') }}</td>
                    <td style="text-align: center">
                        <a href="{{url('agent/contract/view/'.$contract->id.'') }}" class="col" style="color:green; margin-right: 10px;" title="View"><span><i class="fa-solid fa-eye"></i></span></i></a>
                        @if($contract->status == 2)
                            <a href="{{ url('agent/contract/edit/'.$contract->id) }}" class="col" style="color:blue; margin-right: 10px;" title="Edit">
                                <span><i class="fa-solid fa-pen-to-square"></i></span>
                            </a>
                            <a href="{{ url('agent/contract/delete/'.$contract->id) }}" class="col" style="color:red; margin-right: 10px;" title="Delete" onclick="return confirm('Are you sure?');">
                                <span><i class="fa fa-trash" aria-hidden="true"></span></i>
                            </a>
                        @else
                            <span class="col" style="color:gray; margin-right: 10px;" title="Edit Disabled">
                                <span><i class="fa-solid fa-pen-to-square"></i></span>
                            </span>
                            <span class="col" style="color:gray; margin-right: 10px;" title="Delete Disabled">
                                <span><i class="fa fa-trash" aria-hidden="true"></span></i>
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const activeCheckbox = document.getElementById('activeCheckbox');
        const pendingCheckbox = document.getElementById('pendingCheckbox');
        const terminatedCheckbox = document.getElementById('terminatedCheckbox');
        const tbody = document.querySelector('.details-table tbody');

        function filterContracts() {
            const searchText = searchInput.value.toLowerCase();
            const isActiveChecked = activeCheckbox.checked;
            const isPendingChecked = pendingCheckbox.checked;
            const isTerminatedChecked = terminatedCheckbox.checked;
            const noCheckboxChecked = !isActiveChecked && !isPendingChecked && !isTerminatedChecked;

            Array.from(tbody.children).forEach(row => {
                const tenant = row.cells[0].textContent.toLowerCase();
                const ic = row.cells[1].textContent.replace(/-/g, '').toLowerCase();
                const address = row.cells[2].textContent.toLowerCase();
                const status = row.cells[7].textContent.toLowerCase();

                const textMatch = tenant.includes(searchText) || ic.includes(searchText) || address.includes(searchText);
                const statusMatch = (isActiveChecked && status === 'active') ||
                                    (isPendingChecked && status === 'pending') ||
                                    (isTerminatedChecked && status === 'terminated');

                // Display all contracts if no checkboxes are checked or filter by status if any are checked
                row.style.display = (textMatch && (noCheckboxChecked || statusMatch)) ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', filterContracts);
        activeCheckbox.addEventListener('change', filterContracts);
        pendingCheckbox.addEventListener('change', filterContracts);
        terminatedCheckbox.addEventListener('change', filterContracts);

        // Initially call filterContracts to apply the default behavior when the page loads
        filterContracts();
    });
    </script>

@endsection
