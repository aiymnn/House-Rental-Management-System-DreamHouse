@extends('layouts.staff-layout')

@section('title', 'DreamHouse • Property')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <style>
        .details-container {
            color: #333;
            max-width: 99%;
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

        .dropdown-menu {
            max-height: 150px;
            overflow-y: auto;
        }



    </style>


    <div class="notice" style=" overflow: hidden;">
        @include('landlord.property.success-message')
    </div>

    <div class="details-container">
        {{-- <a href="{{ route('landlord-create-property') }}" class="btn float-end" style="background-color: #023d90; color: white;">New Property</a> --}}
        <div class="details-header" id="paymentDetailHeader">
            <h4>Property List
            </h4>
        </div>
        <div class="card-body" id="paymentDetailBody">
            @if($properties->isEmpty())
                <div class="text-center p-3">
                    <h3 class="text-secondary">You don't have any properties yet.</h3>
                </div>
            @else
            <table class="details-table">
                <thead>
                    <tr>
                        <th style="text-align: center">Landlord</th>
                        <th>Address</th>
                        <th style="text-align: center">Type</th>
                        <th style="text-align: center">Deposit (RM)</th>
                        {{-- <th>Monthly (RM)</th> --}}
                        <th>Description</th>
                        <th style="text-align: center">Agent</th>
                        <th style="text-align: center">Available</th>
                        <th style="width: 150px; text-align: center;">Status</th>
                        <th style="width: 150px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <div class="d-flex flex-wrap justify-content-end align-items-center mb-3">
                    <input type="text" id="searchField" class="form-control me-2 mb-2" placeholder="Search by address or landlord" style="max-width: 250px;">
                    <div class="form-check form-check-inline mb-2">
                        <input class="form-check-input status-checkbox" type="checkbox" id="approvedCheckbox" value="1">
                        <label class="form-check-label" for="approvedCheckbox">Approved</label>
                    </div>
                    <div class="form-check form-check-inline mb-2">
                        <input class="form-check-input status-checkbox" type="checkbox" id="pendingCheckbox" value="3">
                        <label class="form-check-label" for="pendingCheckbox">Pending</label>
                    </div>
                    <div class="form-check form-check-inline mb-2">
                        <input class="form-check-input status-checkbox" type="checkbox" id="rejectedCheckbox" value="2">
                        <label class="form-check-label" for="rejectedCheckbox">Disable</label>
                    </div>
                    <div class="form-check form-check-inline mb-2">
                        <input class="form-check-input status-checkbox" type="checkbox" id="incompleteCheckbox" value="4">
                        <label class="form-check-label" for="incompleteCheckbox">Incomplete</label>
                    </div>
                </div>
                <tbody id="propertyTableBody">
                    @foreach ($properties as $property)
                    <tr class="property-row" data-address="{{$property->address}}" data-landlord="{{$property->landlord->name}}" data-status="{{$property->status}}">
                        {{-- <td style="text-align: center">{{ explode(' ', $property->landlord->name)[1] }}</td> --}}
                        <td style="text-align: center">{{ $property->landlord->name }}</td>
                        <td>{{$property->address}}</td>
                        <td style="text-align: center">
                            @if ($property->type == 1)
                            Bungalow/Villa
                            @elseif ($property->type == 2)
                            Semi-D
                            @elseif ($property->type == 3)
                            Terrace
                            @elseif ($property->type == 4)
                            Townhouse
                            @elseif ($property->type == 5)
                            Flat/Apartment
                            @elseif ($property->type == 6)
                            Condominium
                            @elseif ($property->type == 7)
                            Penthouse
                            @else
                            Shop House
                            @endif
                        </td>
                        <td style="text-align: center">{{$property->deposit}}</td>
                        {{-- <td style="text-align: center">{{$property->monthly}}</td> --}}
                        <td>{{$property->description}}</td>
                        <td style="text-align: center;">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="agentDropdown{{$property->id}}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 120px;">
                                    @if ($property->agent_id)
                                    @php
                                    $nameParts = explode(' ', $property->agent->name);
                                    $firstName = end($nameParts);
                                    @endphp
                                    {{ $firstName }}
                                    @else
                                        Select
                                    @endif
                                </button>
                                <div class="dropdown-menu" aria-labelledby="agentDropdown{{$property->id}}">
                                    @foreach($agents as $agent)
                                        <a class="dropdown-item agent-option" href="#" data-property="{{$property->id}}" data-agent="{{$agent->id}}">{{$agent->name}}</a>
                                    @endforeach
                                </div>
                            </div>
                        </td>
                        <td style="text-align: center">
                            @if ($property->available == 1)
                            <a href="{{ url('staff/property/available/'.$property->id.'') }}">&#9989</a>
                            @else
                            <a href="{{ url('staff/property/unavailable/'.$property->id.'') }}">&#10060</a>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if ($property->status == 3)
                            <a href="{{ url('staff/property/approval/'.$property->id.'') }}" class="btn btn-success">Approval</a>
                            @elseif ($property->status == 1)
                            <a href="{{ url('staff/property/inactive/'.$property->id.'') }}" class="btn btn-primary">Active</a>
                            @elseif ($property->status == 2)
                            <a href="{{ url('staff/property/active/'.$property->id.'') }}" class="btn btn-danger">Inactive</a>
                            @else
                            Incomplete
                            @endif
                        </td>
                        <td style="text-align: center">
                            <a href="{{ url('staff/property/view/'.$property->id.'') }}" class="col" style="color:green; margin-right: 10px;" title="View"><span><i class="fa-solid fa-eye"></i></span></i></a>
                            <a href="{{ url('staff/property/edit/'.$property->id.'') }}" class="col" style="color:blue; margin-right: 10px;" title="Edit"><span><i class="fa-solid fa-pen-to-square"></i></span></a>
                            {{-- <a href="{{ url('landlord/property/image/'.$property->id.'') }}" class="col" style="color: orange; margin-right: 10px;" title="Update Image"><span><i class="fa-solid fa-image"></i></span></a> --}}
                            <a href="{{ url('staff/property/delete/'.$property->id.'') }}" class="col" style="color:red; margin-right: 10px;" title="Delete"
                                onclick="return confirm('Are you sure?')";>
                                <span><i class="fa fa-trash" aria-hidden="true"></span></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    <tr id="noResultsRow" style="display: none;">
                        <td colspan="10" class="text-center">Not Found</td>
                    </tr>
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Search function
        $('#searchField').on('keyup', function() {
            filterProperties();
        });

        // Checkbox filter function
        $('.status-checkbox').on('change', function() {
            filterProperties();
        });

        function filterProperties() {
            var searchValue = $('#searchField').val().toLowerCase();
            var selectedStatuses = $('.status-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            var anyVisible = false;
            $('.property-row').each(function() {
                var address = $(this).data('address').toLowerCase();
                var landlord = $(this).data('landlord').toLowerCase();
                var status = $(this).data('status').toString();

                var matchesSearch = (address.includes(searchValue) || landlord.includes(searchValue));
                var matchesStatus = selectedStatuses.length === 0 || selectedStatuses.includes(status);

                if (matchesSearch && matchesStatus) {
                    $(this).show();
                    anyVisible = true;
                } else {
                    $(this).hide();
                }
            });

            $('#noResultsRow').toggle(!anyVisible);
        }

        // Update agent AJAX request
        $('.agent-option').click(function(e) {
            e.preventDefault();
            var propertyId = $(this).data('property');
            var agentId = $(this).data('agent');

            $.ajax({
                type: 'POST',
                url: '{{ route("update-agent") }}',
                data: {
                    property_id: propertyId,
                    agent_id: agentId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    alert(data.message); // Alert the user with the message
                    location.reload();   // Reload the page after the alert is dismissed
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>

@endsection
