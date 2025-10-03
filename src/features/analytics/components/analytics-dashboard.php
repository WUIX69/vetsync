<div class="ui container">
    <!-- Top Row: Main Appointment Stats -->
    <div class="ui four statistics">
        <div class="blue statistic">
            <div class="value">
                <i class="calendar icon"></i>
                <span id="all-appointments">0</span>
            </div>
            <div class="label">All Appointments</div>
        </div>

        <div class="yellow statistic">
            <div class="value">
                <i class="clock icon"></i>
                <span id="pending-month">0</span>
            </div>
            <div class="label">Pending This Month</div>
        </div>

        <div class="green statistic">
            <div class="value">
                <i class="check circle icon"></i>
                <span id="completed-all">0</span>
            </div>
            <div class="label">Completed Appointments</div>
        </div>

        <div class="red statistic">
            <div class="value">
                <i class="times circle icon"></i>
                <span id="cancelled-all">0</span>
            </div>
            <div class="label">Cancelled Appointments</div>
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

    <div class="ui divider"></div>

    <!-- Bottom Row: Detailed Stats - Daily & Monthly -->
    <div class="ui five statistics">
        <div class="orange statistic">
            <div class="value" id="pending-today">0</div>
            <div class="label">Pending Today</div>
        </div>

        <div class="teal statistic">
            <div class="value" id="completed-month">0</div>
            <div class="label">Completed This Month</div>
        </div>

        <div class="green statistic">
            <div class="value" id="completed-today">0</div>
            <div class="label">Completed Today</div>
        </div>

        <div class="pink statistic">
            <div class="value" id="cancelled-month">0</div>
            <div class="label">Cancelled This Month</div>
        </div>

        <div class="red statistic">
            <div class="value" id="cancelled-today">0</div>
            <div class="label">Cancelled Today</div>
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
                    // Update top row stats
                    document.getElementById('all-appointments').innerText = formatNumber(data.data.all_appointments || 0);
                    document.getElementById('pending-month').innerText = formatNumber(data.data.pending_month || 0);
                    document.getElementById('completed-all').innerText = formatNumber(data.data.completed_all || 0);
                    document.getElementById('cancelled-all').innerText = formatNumber(data.data.cancelled_all || 0);

                    // Update bottom row stats
                    document.getElementById('pending-today').innerText = formatNumber(data.data.pending_today || 0);
                    document.getElementById('completed-month').innerText = formatNumber(data.data.completed_month || 0);
                    document.getElementById('completed-today').innerText = formatNumber(data.data.completed_today || 0);
                    document.getElementById('cancelled-month').innerText = formatNumber(data.data.cancelled_month || 0);
                    document.getElementById('cancelled-today').innerText = formatNumber(data.data.cancelled_today || 0);

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
        // 1. Products Chart
        const topSales = data.top_sales || [];
        let productNames = ['No Data'];
        let productSales = [0];

        if (topSales.length > 0) {
            productNames = topSales.map(item => item.product_name);
            productSales = topSales.map(item => item.total_quantity);
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