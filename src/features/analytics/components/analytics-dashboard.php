<div class="ui container">
    <!-- Fomantic UI Statistics -->
    <div class="ui four statistics">
        <div class="green statistic">
            <div class="value">
                <i class="money bill alternate icon"></i>
                <span id="total-revenue">₱90</span>
            </div>
            <div class="label">Total Revenue</div>
        </div>

        <div class="blue statistic">
            <div class="value">
                <i class="users icon"></i>
                <span id="total-users">24</span>
            </div>
            <div class="label">Total Users</div>
        </div>

        <div class="teal statistic">
            <div class="value">
                <i class="calendar icon"></i>
                <span id="total-appointments">15</span>
            </div>
            <div class="label">Total Appointments</div>
        </div>

        <div class="orange statistic">
            <div class="value">
                <i class="shopping cart icon"></i>
                <span id="total-reservations">15</span>
            </div>
            <div class="label">Total Orders</div>
        </div>
    </div>

    <div class="ui divider"></div>

    <!-- Charts Section - 3 Columns -->
    <div class="ui three column grid">
        <div class="column">
            <div class="ui segment">
                <h4 class="ui header">
                    <i class="chart bar icon"></i>
                    Top Selling Products
                </h4>
                <div style="height: 250px; position: relative;">
                    <canvas id="productsChart"></canvas>
                </div>
            </div>
        </div>

        <div class="column">
            <div class="ui segment">
                <h4 class="ui header">
                    <i class="calendar check icon"></i>
                    Most Booked Services
                </h4>
                <div style="height: 250px; position: relative;">
                    <canvas id="servicesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="column">
            <div class="ui segment">
                <h4 class="ui header">
                    <i class="chart pie icon"></i>
                    Orders Status
                </h4>
                <div style="height: 250px; position: relative;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats -->
    <div class="ui three statistics">
        <div class="blue statistic">
            <div class="value" id="orders-today">0</div>
            <div class="label">Orders Today</div>
        </div>

        <div class="green statistic">
            <div class="value">₱<span id="revenue-today">0</span></div>
            <div class="label">Revenue Today</div>
        </div>

        <div class="teal statistic">
            <div class="value" id="new-users-today">19</div>
            <div class="label">New Users This Month</div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let productsChart, servicesChart, statusChart;

    function loadAnalytics() {
        console.log('Loading analytics...');

        fetch('/src/features/analytics/api/analytics.php')
            .then(response => response.json())
            .then(data => {
                console.log('Response:', data);

                if (data.success) {
                    // Update the numbers
                    document.getElementById('total-revenue').innerText = '₱' + formatNumber(data.data.total_revenue || 0);
                    document.getElementById('total-users').innerText = formatNumber(data.data.total_users || 0);
                    document.getElementById('total-appointments').innerText = formatNumber(data.data.total_appointments || 0);
                    document.getElementById('total-reservations').innerText = formatNumber(data.data.total_reservations || 0);
                    document.getElementById('orders-today').innerText = formatNumber(data.data.orders_today || 0);
                    document.getElementById('revenue-today').innerText = formatNumber(data.data.revenue_today || 0);
                    document.getElementById('new-users-today').innerText = formatNumber(data.data.new_users_this_month || 0);

                    // Create all charts
                    createAllCharts(data.data);

                    console.log('✅ Analytics updated successfully!');
                } else {
                    console.error('API Error:', data.message);
                    createDemoCharts();
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                createDemoCharts();
            });
    }

    function createAllCharts(data) {
        // 1. Products Chart - Use real data from API
        const topSales = data.top_sales || [];
        let productNames = ['Whiskas', 'Diaper', 'Lunganisa', 'Dog Food', 'Cat Food'];
        let productSales = [12, 8, 6, 4, 3];

        if (topSales.length > 0) {
            productNames = topSales.slice(0, 5).map(item => item.product_name);
            productSales = topSales.slice(0, 5).map(item => item.total_quantity); // Use total_quantity instead of reservations
        }

        createProductsChart(productNames, productSales);

        // 2. Services Chart - Demo data (you can enhance with real data later)
        createServicesChart();

        // 3. Status Chart
        const totalReservations = data.total_reservations || 15;
        const completedOrders = Math.floor(totalReservations * 0.8); // 80% completed
        const rejectedOrders = Math.floor(totalReservations * 0.1);  // 10% rejected  
        const pendingOrders = totalReservations - completedOrders - rejectedOrders; // remaining

        createStatusChart([completedOrders, rejectedOrders, pendingOrders]);
    }

    function createProductsChart(labels, data) {
        const ctx = document.getElementById('productsChart').getContext('2d');

        if (productsChart) {
            productsChart.destroy();
        }

        productsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Sales',
                    data: data,
                    backgroundColor: [
                        '#21ba45',
                        '#2185d0',
                        '#00b5ad',
                        '#f2711c',
                        '#a333c8'
                    ],
                    borderRadius: 4,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45
                        }
                    }
                }
            }
        });
    }

    function createServicesChart() {
        const ctx = document.getElementById('servicesChart').getContext('2d');

        if (servicesChart) {
            servicesChart.destroy();
        }

        const services = ['Vaccination', 'Check-up', 'Grooming', 'Surgery', 'Dental'];
        const bookings = [15, 12, 8, 5, 3];

        servicesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: services,
                datasets: [{
                    label: 'Bookings',
                    data: bookings,
                    backgroundColor: [
                        '#00b5ad',
                        '#2185d0',
                        '#21ba45',
                        '#f2711c',
                        '#a333c8'
                    ],
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    function createStatusChart(data) {
        const ctx = document.getElementById('statusChart').getContext('2d');

        if (statusChart) {
            statusChart.destroy();
        }

        statusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Rejected', 'Pending'],
                datasets: [{
                    data: data,
                    backgroundColor: [
                        '#21ba45', // Green
                        '#db2828', // Red
                        '#fbbd08'  // Yellow
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    }

    function createDemoCharts() {
        console.log('Creating demo charts...');
        createProductsChart(['Whiskas', 'Diaper', 'Lunganisa', 'Dog Food', 'Cat Food'], [12, 8, 6, 4, 3]);
        createServicesChart();
        createStatusChart([12, 2, 1]);
    }

    function formatNumber(num) {
        return Number(num).toLocaleString();
    }

    // Load when page is ready
    document.addEventListener('DOMContentLoaded', function () {
        loadAnalytics();
    });

    if (typeof $ !== 'undefined') {
        $(document).ready(function () {
            loadAnalytics();
        });
    }
</script>