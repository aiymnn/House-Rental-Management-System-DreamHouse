@extends('layouts.agent-layout')

@section('title', 'Tenant • View')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card mt-3">
            @include('landlord.property.success-message')
            <div class="card-header">
                <h4>Tenant Detail
                    <a href="{{ url('agent/tenant') }}" class="btn float-end" style="background-color: #022d6a; color: white;" >Back</a>
                </h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <div class="d-flex justify-content-start">
                        <div class="avatar-container position-relative">
                            <img src="{{ asset($tenants->avatar) }}" alt="Avatar" class="rounded-circle circle-image mt-3 p-2" id="avatar-image" style="width: 250px; height: 250px;">
                        </div>
                        <div class="tenant-detail ml-3 w-100">
                            <div class="container mt-3">
                                <div class="row">
                                    <div class="col-md-12" style="color: white;">
                                        <div class="row mb-2">
                                            <div class="col-md-3" style="background-color: #022d6a;">
                                                <div class="p-3 text-white">Name</div>
                                            </div>
                                            <div class="col-md-9 bg-secondary">
                                                <div class="p-3 text-white">{{ $tenants->name }}</div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-3" style="background-color: #022d6a;">
                                                <div class="p-3 text-white">IC Number</div>
                                            </div>
                                            <div class="col-md-9 bg-secondary">
                                                <div class="p-3 text-white">{{ substr($tenants->number_ic, 0, 6) }}-{{ substr($tenants->number_ic, 6, 2) }}-{{ substr($tenants->number_ic, 8) }}</div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-3" style="background-color: #022d6a;">
                                                <div class="p-3 text-white">Email</div>
                                            </div>
                                            <div class="col-md-9 bg-secondary">
                                                <div class="p-3 text-white">{{ $tenants->email }}</div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-3" style="background-color: #022d6a;">
                                                <div class="p-3 text-white">Phone</div>
                                            </div>
                                            <div class="col-md-9 bg-secondary">
                                                <div class="p-3 text-white">{{ substr($tenants->phone, 0, 3) }}-{{ substr($tenants->phone, 3) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </table>
            </div>
        </div>
    </div>
</div>




@endsection
