@extends('layouts.agent-layout')

@section('title', 'DreamHouse • Complaint')

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
    display: flex;
    justify-content: space-between; /* Space between title and search bar */
    align-items: center; /* Align items vertically */
    padding-bottom: 10px;
    border-bottom: 1px solid #dee2e6;
    margin-bottom: 15px;
}

.search-bar {
    display: flex;
    align-items: center; /* Keep items inline */
    flex-grow: 1; /* Allow search bar area to take up available space */
    justify-content: flex-end; /* Align items to the right */
}

    .search-bar input[type="text"] {
        flex-grow: 1; /* Allows the search input to take up available space */
        max-width: 300px; /* Sets a maximum width for the input field */
        margin-right: 10px; /* Provides a right margin */

    }


    .checkbox-container {
        display: flex;
        align-items: center;
    }

    .checkbox-container label {
        margin-left: 10px; /* Space between checkboxes */
        white-space: nowrap; /* Prevents wrapping of text */
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

<div class="notice" style="margin-top: 75px; overflow: hidden;">
    @include('landlord.property.success-message')
</div>

<div class="details-container">
    <div class="details-header">
        <h4>Tenant Complaints</h4>
        <div class="search-bar">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by name..." style="margin-right: 10px;">
            <div class="checkbox-container">
                <label><input type="checkbox" id="remarkedCheckbox"> Remarked</label>
                <label><input type="checkbox" id="noRemarkCheckbox"> No Remarks</label>
            </div>
        </div>
    </div>

    <div class="card-body" id="paymentDetailBody">
        @if($reports->isEmpty())
            <div class="text-center p-3">
                <h3 class="text-secondary">You don't have any complaints from tenants yet.</h3>
            </div>
        @else
        <table class="details-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Remark</th>
                    <th>Created at</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="reportTable">
                @foreach ($reports as $report)
                <tr>
                    <td style="text-align: center;">{{ $report->tenant->name }}</td>
                    <td style="text-align: center;">
                        @if ($report->title == 1)
                        Damage to the house
                        @else
                        Others
                        @endif
                    </td>
                    <td>{{ $report->description }}</td>
                    <td style="text-align: center;" class="remark">
                        @php
                        $n = $report->remark;
                        echo empty($n) ? "No remark yet" : $n;
                        @endphp
                    </td>
                    <td style="text-align: center">{{ \Carbon\Carbon::parse($report->created_at)->format('d-m-Y') }}</td>
                    <td style="text-align: center">
                        <a href="{{ url('agent/report/reply/'.$report->id.'')}}" class="col" style="color:rgb(0, 128, 23); margin-right: 10px;" title="Reply"><span><i class="fa fa-reply" aria-hidden="true"></i></span></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const remarkedCheckbox = document.getElementById('remarkedCheckbox');
        const noRemarkCheckbox = document.getElementById('noRemarkCheckbox');
        const tbody = document.getElementById('reportTable');

        function filterReports() {
            const searchText = searchInput.value.toLowerCase();
            const isRemarkedChecked = remarkedCheckbox.checked;
            const isNoRemarkChecked = noRemarkCheckbox.checked;

            Array.from(tbody.children).forEach(row => {
                const nameText = row.cells[0].textContent.toLowerCase();
                const remarkText = row.cells[3].textContent.toLowerCase().trim(); // Ensure no extra whitespace
                const hasRemark = remarkText !== "no remark yet";

                console.log(`Name: ${nameText}, Remark: ${remarkText}, Has Remark: ${hasRemark}`); // Debug output

                let remarkMatch = true; // Default to showing all
                if (isRemarkedChecked && !isNoRemarkChecked) {
                    remarkMatch = hasRemark; // Only show rows where remark is not null
                } else if (!isRemarkedChecked && isNoRemarkChecked) {
                    remarkMatch = !hasRemark; // Only show rows where remark is null or 'No remark yet'
                }

                // Combine the text match and remark match conditions
                row.style.display = (nameText.includes(searchText) && remarkMatch) ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', filterReports);
        remarkedCheckbox.addEventListener('change', filterReports);
        noRemarkCheckbox.addEventListener('change', filterReports);
    });
    </script>



@endsection
