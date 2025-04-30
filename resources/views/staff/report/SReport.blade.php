@extends('layouts.staff-layout')

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
    <div class="details-header" id="complaintDetailHeader">
        <h4>Tenant Complaints</h4>
    </div>
    <div class="d-flex flex-wrap justify-content-end align-items-center mb-3">
        <input type="text" id="searchInput" class="form-control me-2 mb-2" placeholder="Search by Contract ID or Tenant Name" style="max-width: 280px;">
        <div class="form-check form-check-inline mb-2">
            <input class="form-check-input" type="checkbox" id="damageCheckbox" value="1">
            <label class="form-check-label ml-2" for="damageCheckbox">Damaged by House</label>
        </div>
        <div class="form-check form-check-inline mb-2">
            <input class="form-check-input" type="checkbox" id="othersCheckbox" value="2">
            <label class="form-check-label ml-2" for="othersCheckbox">Others</label>
        </div>
        <div class="form-check form-check-inline mb-2">
            <input class="form-check-input" type="checkbox" id="remarkedCheckbox">
            <label class="form-check-label ml-2" for="remarkedCheckbox">Remarked</label>
        </div>
        <div class="form-check form-check-inline mb-2">
            <input class="form-check-input" type="checkbox" id="noRemarkCheckbox">
            <label class="form-check-label ml-2" for="noRemarkCheckbox">No Remark</label>
        </div>
    </div>
    <div class="card-body" id="complaintDetailBody">
        @if($reports->isEmpty())
            <div class="text-center p-3">
                <h3 class="text-secondary">You don't have any complaints from tenant yet.</h3>
            </div>
        @else
        <table class="details-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Agent</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Remark</th>
                    <th>Created at</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $report)
                <tr>
                    <td style="text-align: center;">
                        <a href="{{ url('staff/user/tenant/view/'.$report->tenant->id.'') }}" target="__blank">{{ $report->tenant->name }}</a>
                    </td>
                    <td style="text-align: center;">
                        <a href="{{ url('staff/user/agent/view/'.$report->agent->id.'') }}" target="__blank">{{ $report->agent->name }}</a>
                    </td>
                    <td style="text-align: center;" data-title="{{ $report->title }}">
                        @if ($report->title == 1)
                        Damage by House
                        @else
                        Others
                        @endif
                    </td>
                    <td>{{ $report->description }}</td>
                    <td style="text-align: center;" data-remark="{{ $report->remark ? 'remarked' : 'no-remark' }}">
                        {{ $report->remark ?: 'No remark yet' }}
                    </td>
                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($report->created_at)->format('d-m-Y') }}</td>
                    <td style="text-align: center">
                        <a href="{{ url('staff/report/reply/'.$report->id.'') }}" class="col" style="color:rgb(0, 128, 23); margin-right: 10px;" title="Reply">
                            <span><i class="fa fa-reply" aria-hidden="true"></i></span>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div id="notFoundMessage" class="text-center d-none">
            <h3 class="text-secondary">No complaints found.</h3>
        </div>

        @endif
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        function filterComplaints() {
            const searchInput = document.getElementById('searchInput');
            const damageCheckbox = document.getElementById('damageCheckbox');
            const othersCheckbox = document.getElementById('othersCheckbox');
            const remarkedCheckbox = document.getElementById('remarkedCheckbox');
            const noRemarkCheckbox = document.getElementById('noRemarkCheckbox');
            const tbody = document.querySelector('tbody');
            let foundComplaints = 0;

            if (tbody) {
                Array.from(tbody.children).forEach(row => {
                    const contractID = row.children[0].innerText.toLowerCase();
                    const name = row.children[1].innerText.toLowerCase();
                    const title = parseInt(row.children[2].getAttribute('data-title'), 10);
                    const remarkStatus = row.children[4].getAttribute('data-remark');

                    const searchTextMatch = contractID.includes(searchInput.value.toLowerCase()) || name.includes(searchInput.value.toLowerCase());
                    let titleMatch = false;
                    let remarkMatch = false;

                    if (!damageCheckbox.checked && !othersCheckbox.checked) {
                        titleMatch = true;
                    } else {
                        if (damageCheckbox.checked && title === 1) titleMatch = true;
                        if (othersCheckbox.checked && title === 2) titleMatch = true;
                    }

                    if (!remarkedCheckbox.checked && !noRemarkCheckbox.checked) {
                        remarkMatch = true;
                    } else {
                        if (remarkedCheckbox.checked && remarkStatus === 'remarked') remarkMatch = true;
                        if (noRemarkCheckbox.checked && remarkStatus === 'no-remark') remarkMatch = true;
                    }

                    if (searchTextMatch && titleMatch && remarkMatch) {
                        row.style.display = '';
                        foundComplaints++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                const notFoundMessage = document.getElementById('notFoundMessage');
                if (notFoundMessage) {
                    notFoundMessage.classList.toggle('d-none', foundComplaints > 0);
                }
            }
        }

        if (document.getElementById('searchInput')) {
            document.getElementById('searchInput').addEventListener('input', filterComplaints);
            document.getElementById('damageCheckbox').addEventListener('change', filterComplaints);
            document.getElementById('othersCheckbox').addEventListener('change', filterComplaints);
            document.getElementById('remarkedCheckbox').addEventListener('change', filterComplaints);
            document.getElementById('noRemarkCheckbox').addEventListener('change', filterComplaints);
        }
    });
    </script>

@endsection
