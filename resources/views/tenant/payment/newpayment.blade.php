@extends('layouts.tenant-layout')

@section('title', 'Payment • New Payment')

@section('content')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0" />

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

<div class="notice" style="margin-top: 75px; overflow: hidden;">
    @include('landlord.property.success-message')
</div>

<div class="details-container">
    <a href="{{ url('tenant/payment') }}" class="btn float-end" style="background-color: #023d90; color: white">Back</a>
    <div class="details-header" id="paymentDetailHeader">
        <h4>New Payment</h4>
    </div>
    <div class="card-body" id="paymentDetailBody">
        @if ($contract->balance != 0)
            <form action="{{ route('mstripe') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Type</label>
                    <select id="product_name" class="form-select" aria-describedby="emailHelp" name="product_name">
                        <option selected disabled>-Type-</option>
                        @if ($contract->deposit < $property->deposit)
                            <option value="Deposit">Deposit</option>
                        @elseif ($contract->deposit == 0)
                            <option value="Deposit">Deposit</option>
                        @else
                            <option value="Monthly">Monthly</option>
                        @endif
                    </select>
                    @error('product_name') <span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="mb-3" id="months-container" style="display:none;">
                    <label class="form-label">Months to Pay For</label>
                    <input type="number" class="form-control" id="months" name="months" value="1" min="1">
                </div>
                <div class="mb-3">
                    <label class="form-label">Amount (RM)</label>
                    <input type="number" class="form-control" id="price" name="price" readonly>
                    @error('price')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const productNameElement = document.getElementById('product_name');
                        const priceElement = document.getElementById('price');
                        const monthsInputElement = document.getElementById('months');
                        const monthsContainer = document.getElementById('months-container');
                        const propertyDeposit = {{ $property->deposit }};
                        const contractDeposit = {{ $contract->deposit }};
                        const propertyMonthly = {{ $property->monthly }};

                        productNameElement.addEventListener('change', function () {
                            const selectedValue = this.value;
                            if (selectedValue == "Deposit") {
                                priceElement.value = propertyDeposit - contractDeposit;
                                monthsContainer.style.display = 'none';
                            } else if (selectedValue == "Monthly") {
                                monthsContainer.style.display = 'block';
                                updateMonthlyTotal();
                            }
                        });

                        monthsInputElement.addEventListener('input', updateMonthlyTotal);

                        function updateMonthlyTotal() {
                            const months = monthsInputElement.value || 1;
                            priceElement.value = propertyMonthly * months;
                        }

                        // Initialize price on load
                        if (productNameElement.value) {
                            productNameElement.dispatchEvent(new Event('change'));
                        }
                    });
                </script>
                <input type="hidden" name="quantity" value="1">
                <input type="hidden" name="contract" value="{{ strval($contract->id) }}">
                <div class="form-section submit-button">
                    <button type="submit" class="btn" style="background-color: #023d90; color: white;">Pay Now</button>
                </div>
            </form>
        @else
        <h4 class="text-secondary" style="text-align: center;">Your contract currently out of balance.</h4>
        @endif
    </div>
</div>

@endsection
