@extends('layouts.staff-layout')

@section('title', 'DreamHouse • Dashboard')

@section('content')


<div class="container">
    <section class="bdge">
        @include('staff.dashboard.badge')
    </section>

    <section class="chart">
        @include('staff.dashboard.chart')
    </section>
</div>

@endsection
