<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .border-rounded {
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 20px;
    }

    .charts-container {
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .top-right {
        align-self: flex-end;
        margin-top: 10px;
        margin-right: 10px;
    }

    @media only screen and (max-width: 1400px) {
        .chart-wrapper {
            flex-direction: column;
        }
    }
</style>

<div class="charts-container">
    <div class="top-right mb-2">
        <div class="dropdown">
            <button id="selectedYearButton" class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Select Year
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="year-dropdown">
                @foreach($availableYears as $year)
                    <li><a class="dropdown-item" href="{{ url()->current() }}?year={{ $year }}" onclick="updateSelectedYear('{{ $year }}')">{{ $year }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="chart-wrapper border-rounded" style="display: flex;">
        <div style="flex: 1;">
            <canvas id="incomeChart"></canvas>
        </div>
    </div>
    <div class="chart-wrapper" style="display: flex;">
        <div class="border-rounded" style="flex: 1; margin-right: 20px;">
            <canvas id="propertyChart"></canvas>
        </div>
        <div class="border-rounded" style="flex: 1;">
            <canvas id="tenantLandlordContractChart"></canvas>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

    function updateSelectedYear(year) {
        document.getElementById('selectedYearButton').innerText = 'Year: ' + year;
    }

    // Check if a year parameter exists in the URL and update the button text accordingly
    window.addEventListener('DOMContentLoaded', (event) => {
        const urlParams = new URLSearchParams(window.location.search);
        const selectedYear = urlParams.get('year');
        if (selectedYear) {
            updateSelectedYear(selectedYear);
        }
    });


    // PHP arrays converted to JavaScript arrays
    const labels = @json($formattedLabels);
    const data = @json($incomeValues);

    // Initialize income chart
    const incomeCtx = document.getElementById('incomeChart').getContext('2d');
    const incomeChart = new Chart(incomeCtx, {
        type: 'line',
        data: {
            labels: labels, // Use Laravel-generated formatted labels
            datasets: [{
                label: 'Income',
                data: data, // Use Laravel-generated data
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Payment made by tenants'
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Month'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Income'
                    }
                }
            }
        }
    });

    // Set the height of the canvas element
    document.getElementById('incomeChart').style.height = '300px';

    // PHP arrays converted to JavaScript arrays
    const chartLabels = @json($chartLabels);
    const chartData = @json($chartData);

    // Initialize property chart
    const propertyCtx = document.getElementById('propertyChart').getContext('2d');
    const propertyChart = new Chart(propertyCtx, {
        type: 'pie',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Number of Property',
                data: chartData,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(105, 231, 233, 0.51)',
                    'rgba(255, 93, 177, 0.61)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(12, 226, 229, 0.68)',
                    'rgba(254, 17, 140, 0.8)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Type of Property Registered'
                }
            }
        }
    });

    // PHP variables converted to JavaScript
    const tenantCount = @json($tenantCount);
    const landlordCount = @json($landlordCount);
    const contractCount = @json($contractCount);

    // Initialize tenant, landlord, and contract chart
    const tenantLandlordContractCtx = document.getElementById('tenantLandlordContractChart').getContext('2d');
    const tenantLandlordContractChart = new Chart(tenantLandlordContractCtx, {
        type: 'bar',
        data: {
            labels: ['Tenants', 'Landlords', 'Contracts'],
            datasets: [{
                label: 'Users',
                data: [tenantCount, landlordCount, contractCount],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Number of Tenants, Landlords, Contracts Registered'
                }
            }
        }
    });
</script>
