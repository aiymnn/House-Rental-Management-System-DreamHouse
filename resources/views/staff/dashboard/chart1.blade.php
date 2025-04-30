
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
            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                data-bs-toggle="dropdown" aria-expanded="false">
                Select Year
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="year-dropdown">
                <!-- Dropdown options dynamically populated -->
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
    // Sample dropdown options
    const years = [2024, 2023, 2022];

    // Calculate previous year
    const currentYear = new Date().getFullYear();
    const previousYear = currentYear - 1;

    // Populate dropdown options
    const dropdownMenu = document.getElementById('year-dropdown');

    // Add "All" option
    const allOption = document.createElement('li');
    allOption.innerHTML = '<a class="dropdown-item" href="#">All</a>';
    dropdownMenu.appendChild(allOption);

    // Add the rest of the years
    years.forEach(year => {
        const option = document.createElement('li');
        option.innerHTML = `<a class="dropdown-item" href="#">${year}</a>`;
        dropdownMenu.appendChild(option);
    });

    // Chart.js initialization and data

    // Initialize income chart
    const incomeCtx = document.getElementById('incomeChart').getContext('2d');
    const incomeChart = new Chart(incomeCtx, {
        type: 'line',
        data: {
            // Sample data
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [{
                label: 'Income',
                data: [2000, 2500, 1800, 3000, 2800, 3200, 3500, 3800, 3400, 3100, 2900, 2600],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Make the chart full-width
            plugins: {
                title: {
                    display: true,
                    text: 'Income Made'
                }
            }
        }
    });

    // Set the height of the canvas element
    document.getElementById('incomeChart').style.height = '300px';

    // Initialize property chart
    const propertyCtx = document.getElementById('propertyChart').getContext('2d');
    const propertyChart = new Chart(propertyCtx, {
        type: 'pie',
        data: {
            // Sample data
            labels: ['Bungalow/Villa', 'Semi-D', 'Terrace', 'Townhouse', 'Flat/Apartment', 'Condominium', 'Penthouse', 'Shop House'],
            datasets: [{
                label: 'Property Types',
                data: [30, 20, 15, 10, 8, 6, 5, 6],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Make the chart full-width
            plugins: {
                title: {
                    display: true,
                    text: 'Type of Property Registered'
                }
            }
        }
    });

    // Initialize tenant, landlord, and contract chart with sample data
    const tenantLandlordContractCtx = document.getElementById('tenantLandlordContractChart').getContext('2d');
    const tenantLandlordContractChart = new Chart(tenantLandlordContractCtx, {
        type: 'bar',
        data: {
            // Sample data
            labels: ['Tenants', 'Landlords', 'Contracts'],
            datasets: [{
                label: 'Counts',
                data: [100, 80, 120], // Sample data
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
            plugins: {
                title: {
                    display: true,
                    text: 'Number of Tenant, Landlord, Contract Registered'
                }
            }
        }
    });
</script>

