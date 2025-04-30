@extends('layouts.staff-layout')

@section('title', 'DreamHouse • Tenant')

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
    {{-- <a href="{{ route('staff_register') }}" class="btn float-end" style="background-color: #17395e; color: white;">New Agent</a> --}}
    <div class="details-header" id="paymentDetailHeader">
        <h4>List of Tenant
        </h4>
    </div>
    <div class="card-body" id="paymentDetailBody">
        @if($tenants->isEmpty())
            <div class="text-center p-3">
                <h3 class="text-secondary">You don't have any tenants yet.</h3>
            </div>
        @else
        <div class="d-flex flex-wrap justify-content-end align-items-center mb-3">
            <input type="text" id="search" class="form-control me-2 mb-2" placeholder="Search by name or IC number" style="max-width: 280px;">
            <div class="form-check form-check-inline mb-2">
                <input class="form-check-input" type="checkbox" id="onlineCheckbox">
                <label class="form-check-label ml-2" for="onlineCheckbox">Online</label>
            </div>
            <div class="form-check form-check-inline mb-2">
                <input class="form-check-input" type="checkbox" id="offlineCheckbox">
                <label class="form-check-label ml-2" for="offlineCheckbox">Offline</label>
            </div>
        </div>
        <table class="details-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>IC Number</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Contract</th>
                    <th>Status</th>
                    <th>Registered</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="tenantList">
                <!-- Tenant list will be dynamically added here -->
            </tbody>
        </table>
        @endif
    </div>
</div>



<!-- JavaScript -->
<script>
    // Function to filter tenants based on search query and checkbox selection
    function filterTenants() {
        var searchInput = document.getElementById('search').value.toLowerCase();
        var onlineCheckbox = document.getElementById('onlineCheckbox').checked;
        var offlineCheckbox = document.getElementById('offlineCheckbox').checked;

        var tenantList = @json($tenants); // Convert PHP array to JavaScript array

        var filteredTenants = tenantList.filter(function (tenant) {
            var name = tenant.name.toLowerCase();
            var icNumber = tenant.number_ic;
            var status = tenant.status;

            // Filter based on search query
            if (name.includes(searchInput) || icNumber.includes(searchInput)) {
                // Filter based on checkbox selection
                if ((onlineCheckbox && status === 1) || (offlineCheckbox && status === 2)) {
                    return true;
                } else if (!onlineCheckbox && !offlineCheckbox) {
                    return true; // Show all if no checkbox is checked
                }
            }
            return false;
        });

        var tbody = document.getElementById('tenantList');
        tbody.innerHTML = ''; // Clear previous content

        if (filteredTenants.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="text-center">Not found</td></tr>';
        } else {
            filteredTenants.forEach(function (tenant) {
            var contractStatus;
            switch (tenant.contract_status) { // Ensure 'contract_status' is being correctly populated
                case 1:
                    contractStatus = 'Active';
                    break;
                case 2:
                    contractStatus = 'Pending';
                    break;
                case 3:
                    contractStatus = 'Terminated';
                    break;
                default:
                    contractStatus = 'Unknown'; // Handle unexpected cases
            }

            var row = `<tr>
                <td style="text-align: center">${tenant.id}</td>
                <td style="text-align: center">${tenant.name}</td>
                <td style="text-align: center">${tenant.number_ic}</td>
                <td style="text-align: center">${tenant.phone}</td>
                <td style="text-align: center">${tenant.email}</td>
                <td style="text-align: center">${contractStatus}</td>
                <td style="text-align: center">${
                    tenant.status === 1
                    ? `<a href="{{ url('staff/tenant/offline/') }}/${tenant.id}" class="btn btn-success">Online</a>`
                    : `<a href="{{ url('staff/tenant/online/') }}/${tenant.id}" class="btn btn-danger">Offline</a>`
                }</td>
                <td style="text-align: center">${new Date(tenant.created_at).toLocaleDateString()}</td>
                <td style="text-align: center">
                    <a href="{{ url('staff/user/tenant/view/') }}/${tenant.id}" class="col" style="color:green; margin-right: 10px;" title="View"><span><i class="fa-solid fa-eye"></i></span></i></a>
                    <a href="{{ url('staff/user/tenant/edit/') }}/${tenant.id}" class="col" style="color:blue; margin-right: 10px;" title="Edit"><span><i class="fa-solid fa-pen-to-square"></i></span></a>
                </td>
            </tr>`;

            tbody.innerHTML += row;
        });

        }
    }

    // Add event listeners for search input and checkboxes
    document.getElementById('search').addEventListener('input', filterTenants);
    document.getElementById('onlineCheckbox').addEventListener('change', filterTenants);
    document.getElementById('offlineCheckbox').addEventListener('change', filterTenants);

    // Initial filtering
    filterTenants();
</script>
@endsection
