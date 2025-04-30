<style>
    .contract-detail-header {
        padding-bottom: 15px;
        border-bottom: 2px solid #dee2e6; /* Light grey border for separation */
        margin-bottom: 20px; /* Space below the header */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .contract-detail-row {
        display: flex;
        justify-content: space-between; /* Space between the label and value */
        margin-bottom: 10px; /* Space between rows */
    }

    .contract-detail-label {
        font-weight: 600; /* Bold label for emphasis */
        color: #022d6a; /* Dark blue for a touch of color */
        flex-basis: 30%; /* Width of the label */
    }

    .contract-detail-value {
        background: #f8f9fa; /* Very light background for the value */
        padding: 8px 12px; /* Padding inside the value box */
        border-radius: 5px; /* Rounded corners for the value box */
        flex-basis: 65%; /* Width of the value area */
        /* text-align: right; */
    }

    .contract-action-alert {
        background-color: #fff6cd; /* Yellow background for attention */
        color: #333; /* Dark text for visibility */
        padding: 15px;
        margin: 20px 0;
        border-radius: 5px; /* Rounded corners */
        display: flex;
        justify-content: space-between; /* Space between text and button */
        align-items: center;
    }

    .contract-action-button {
        background-color: #0056b3; /* Dark blue background */
        color: #fff; /* White text */
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
    }
</style>


<div class="container contract-detail-container">
    <div class="contract-detail-header mt-2">
        <h4>Contract Details</h4>
    </div>

    @if ($contract->status == 2)
    <div class="contract-action-alert">
        <span>Pay deposit to proceed with the contract</span>
        <form action="{{ route('stripe') }}" method="POST" class="mb-0">
            @csrf
            <input type="hidden" name="contract" value="{{ strval($contract->id) }}">
            <input type="hidden" name="price" value="{{ strval($contract->property->deposit) }}">
            <input type="hidden" name="product_name" value="Deposit">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="contract-action-button">Pay Now</button>
        </form>
    </div>
    @endif

    <div class="contract-detail-row">
        <div class="contract-detail-label">Address</div>
        <div class="contract-detail-value">{{ $contract->property->address }}</div>
    </div>
    <div class="contract-detail-row">
        <div class="contract-detail-label">Period (month)</div>
        <div class="contract-detail-value">{{ $contract->period }}</div>
    </div>
    <div class="contract-detail-row">
        <div class="contract-detail-label">Deposit (RM)</div>
        <div class="contract-detail-value">
            {{ number_format($contract->deposit, 2) }}
            @if ($contract->deposit < $contract->property->deposit)
                @php
                    $b = $contract->deposit - $contract->property->deposit;
                @endphp
                &nbsp;&nbsp;&nbsp;&nbsp;<span style="color: red"> {{ number_format($b, 2) }}</span>
            @endif
        </div>
    </div>
    <div class="contract-detail-row">
        <div class="contract-detail-label">Monthly (RM)</div>
        <div class="contract-detail-value">{{ number_format($contract->property->monthly, 2) }}</div>
    </div>
    <div class="contract-detail-row">
        <div class="contract-detail-label">Total (RM)</div>
        <div class="contract-detail-value">{{ number_format($contract->total, 2) }}</div>
    </div>
    <div class="contract-detail-row">
        <div class="contract-detail-label">Balance (RM)</div>
        <div class="contract-detail-value">{{ number_format($contract->balance, 2) }}</div>
    </div>
    <div class="contract-detail-row">
        <div class="contract-detail-label">Start Date</div>
        <div class="contract-detail-value">{{ date('d-m-Y', strtotime($contract->start_date)) }}</div>
    </div>
    <div class="contract-detail-row">
        <div class="contract-detail-label">End Date</div>
        <div class="contract-detail-value">{{ date('d-m-Y', strtotime($contract->end_date)) }}</div>
    </div>
    <div class="contract-detail-row">
        <div class="contract-detail-label">Status</div>
        <div class="contract-detail-value">
            @if ($contract->status == 2)
            Pending
            @elseif ($contract->status == 1)
            Active
            @else
            Terminated
            @endif
        </div>
    </div>

    <!-- Add more rows as needed -->

</div>


