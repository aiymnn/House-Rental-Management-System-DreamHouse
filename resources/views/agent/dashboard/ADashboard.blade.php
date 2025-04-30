@extends('layouts.agent-layout')

@section('title', 'DreamHouse • Dashboard')

@section('content')

<section class="bdge">
    @include('agent.dashboard.badge')
</section>

<section class="profile">
    @include('agent.dashboard.profile')
</section>

<section class="appmnt">
    @include('agent.dashboard.appointment')
</section>


@endsection
