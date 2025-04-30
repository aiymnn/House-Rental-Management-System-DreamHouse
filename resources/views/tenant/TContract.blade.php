@extends('layouts.tenant-layout')

@section('title', 'DreamHouse • Contract')

@section('content')

<style type="text/css">
    .notice {
        margin-bottom: 20px;
    }

    .nav-tabs {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        background: #f8f9fa;
        border-radius: 5px;
    }

    .nav-item {
        flex-grow: 1;
        text-align: center;
    }

    .nav-link {
        display: block;
        padding: 10px 15px;
        text-decoration: none;
        color: #022d6a;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .nav-link.active {
        background-color: #fff;
        color: #006ad4;
    }

    .tab-content {
        background: #fff;
        border-radius: 8px;
        margin-bottom: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
    }


</style>

<div class="notice">
    @include('landlord.property.success-message')
</div>

<div class="nav-tabs">
    @foreach ($contracts as $index => $contract)
        <div class="nav-item">
            <a href="#" class="nav-link @if($index == 0) active @endif" onclick="changeTab(event, 'contract-pane-{{ $contract->id }}')">
                Contract {{ $contract->id }}
                @if ($contract->deposit != $contract->property->deposit)
                    <span style="color: yellow; font-size: 20px;">•</span>
                @elseif ($contract->status == '1')
                    <span style="color: green; font-size: 20px;">•</span>
                @else
                    <span style="color: red; font-size: 20px;">•</span>
                @endif
            </a>
        </div>
    @endforeach

</div>

<div>
    @foreach ($contracts as $index => $contract)
        <div id="contract-pane-{{ $contract->id }}" class="tab-pane @if($index == 0)  @endif" style="@if($index == 0) display: block; @else display: none; @endif">
            <div class="tab-content">
                @include('tenant.contract-details', ['contract' => $contract])
            </div>


                @include('tenant.agent-details', ['agent' => $agents[$contract->id]])

        </div>
    @endforeach
</div>

<script>
    function changeTab(evt, tabId) {
        evt.preventDefault(); // Stop the link from causing a page top jump
        var tabs = document.querySelectorAll('.tab-pane');
        var links = document.querySelectorAll('.nav-link');

        // Hide all tabs and remove 'active' class from all links
        tabs.forEach(function(tab) {
            tab.style.display = 'none'; // Hide tab
        });
        links.forEach(function(link) {
            link.classList.remove('active'); // Remove 'active' class
        });

        // Show the clicked tab and add 'active' class to the clicked link
        document.getElementById(tabId).style.display = 'block'; // Show the selected tab
        evt.currentTarget.classList.add('active'); // Add 'active' class to the clicked link
    }
</script>

@endsection
