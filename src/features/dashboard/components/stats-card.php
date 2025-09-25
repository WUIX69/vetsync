<style>
    main section.status {
        padding: 20px 20px 26px;
        background: #006A71;
        border-radius: 0 0 30px 30px;
    }

    main section.status .header h4 {
        color: #f1f3f2;
        font-weight: 500;
        margin: 0 0 20px 0;
    }

    main section.status .items-list .item {
        background: #e0f2fe;
        width: 100%;
        padding: 20px;
        border-radius: 18px;
        transition: transform 0.3s ease;
        margin-bottom: 10px;
    }

    main section.status .items-list .item:hover {
        transform: translateY(-2px);
    }

    main section.status .items-list .item.item-2 {
        background: #fffbeb;
    }

    main section.status .items-list .item.item-3 {
        background: #f0fdf4;
    }

    main section.status .items-list .item.item-4 {
        background: #fef2f2;
    }

    main section.status .items-list .item .info {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    main section.status .items-list .item .info h5 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 5px;
        color: #1f2937;
    }

    main section.status .items-list .item .info p {
        font-size: 12px;
        font-weight: 500;
        color: #6b7280;
        margin: 0;
    }

    main section.status .items-list .item .info>i {
        font-size: 20px;
        padding: 10px;
        background: #031224;
        color: #f1f3f2;
        border-radius: 50%;
    }

    main section.status .items-list .item .progress {
        position: relative;
        height: 8px;
        background: #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }

    main section.status .items-list .item .progress .bar {
        height: 8px;
        background: #031224;
        border-radius: 10px;
        transition: width 0.8s ease;
        width: 0%;
    }

    main section.status .items-list .item.item-1 .progress .bar {
        background: #3b82f6;
    }

    main section.status .items-list .item.item-2 .progress .bar {
        background: #f59e0b;
    }

    main section.status .items-list .item.item-3 .progress .bar {
        background: #10b981;
    }

    main section.status .items-list .item.item-4 .progress .bar {
        background: #ef4444;
    }

    .progress-label {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        text-align: right;
        margin-bottom: 5px;
    }

    .loading-stats {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 200px;
        color: #f1f3f2;
    }

    .error-stats {
        text-align: center;
        padding: 40px;
        color: #f1f3f2;
    }

    .next-appointment {
        font-size: 11px;
        color: #6b7280;
        margin-top: 5px;
    }
</style>

<section class="status">
    <div class="header">
        <h4>Pet Health Dashboard</h4>
    </div>
    <div class="items-list" id="healthStats">
        <div class="loading-stats">
            <i class='bx bx-loader-alt' style="animation: spin 1s linear infinite; margin-right: 10px;"></i>
            <span>Loading health data...</span>
        </div>
    </div>
</section>

<style>
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        loadPetHealthStats();

        function loadPetHealthStats() {
            fetch('/src/features/dashboard/api/pet-health-stats.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Health stats response:', data);
                    if (data.success) {
                        renderHealthStats(data.data);
                    } else {
                        showStatsError('Failed to load health data: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error loading health stats:', error);
                    showStatsError('Error loading health data: ' + error.message);
                });
        }

        function renderHealthStats(data) {
            const statsContainer = document.getElementById('healthStats');
            const stats = data.stats;
            const progress = data.progress;

            statsContainer.innerHTML = `
                <div class="row">
                    <div class="col-lg-3">
                        <div class="item item-1 health-${stats.health_status}">
                            <div class="info">
                                <div>
                                    <h5>Health Status</h5>
                                    <p>${stats.health_message}</p>
                                    ${stats.pet_info ? `<div class="next-appointment">${stats.pet_info}</div>` : ''}
                                </div>
                                <i class='bx bx-heart'></i>
                            </div>
                            <div class="progress-label">${progress.care_score}%</div>
                            <div class="progress">
                                <div class="bar" style="width: ${progress.care_score}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="item item-2">
                            <div class="info">
                                <div>
                                    <h5>Vaccinations</h5>
                                    <p>${stats.vaccination_count} completed</p>
                                    <div class="next-appointment">${stats.vaccination_count < (stats.total_pets * 5) ? 'Core vaccines recommended' : 'Up to date!'}</div>
                                </div>
                                <i class='bx bx-shield-plus'></i>
                            </div>
                            <div class="progress-label">${progress.vaccination}%</div>
                            <div class="progress">
                                <div class="bar" style="width: ${progress.vaccination}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="item item-3">
                            <div class="info">
                                <div>
                                    <h5>Recent Care</h5>
                                    <p>${stats.total_visits} total visits</p>
                                    <div class="next-appointment">${stats.last_visit_date ?
                    `Last visit: ${new Date(stats.last_visit_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })}` :
                    'No visits yet'
                }</div>
                                </div>
                                <i class='bx bx-calendar-check'></i>
                            </div>
                            <div class="progress-label">${Math.min(100, Math.round((stats.total_visits / Math.max(1, stats.total_pets)) * 33))}%</div>
                            <div class="progress">
                                <div class="bar" style="width: ${Math.min(100, (stats.total_visits / Math.max(1, stats.total_pets)) * 33)}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="item item-4">
                            <div class="info">
                                <div>
                                    <h5>Next Appointment</h5>
                                    ${stats.next_appointment ?
                    `<p>${stats.next_appointment.service}</p>
                                         <div class="next-appointment">${stats.next_appointment.pet_name ? stats.next_appointment.pet_name + ' • ' : ''}${stats.next_appointment.formatted_date} • ${stats.next_appointment.days_until} days</div>` :
                    `<p>No upcoming appointments</p>
                                         <div class="next-appointment"><a href="/src/app/user/services.php" style="color: #3b82f6;">Book now</a></div>`
                }
                                </div>
                                <i class='bx bx-calendar-plus'></i>
                            </div>
                            <div class="progress-label">${stats.next_appointment ? '100' : '0'}%</div>
                            <div class="progress">
                                <div class="bar" style="width: ${stats.next_appointment ? '100' : '0'}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function showStatsError(message) {
            document.getElementById('healthStats').innerHTML = `
                <div class="error-stats">
                    <i class='bx bx-error-circle' style="font-size: 2rem; margin-bottom: 10px;"></i>
                    <p>${message}</p>
                    <button onclick="loadPetHealthStats()" style="background: #f1f3f2; color: #006A71; border: none; padding: 10px 20px; border-radius: 5px; margin-top: 10px; cursor: pointer;">
                        Try Again
                    </button>
                </div>
            `;
        }

        // Make function globally available for retry button
        window.loadPetHealthStats = loadPetHealthStats;

        // Refresh every 5 minutes
        setInterval(loadPetHealthStats, 5 * 60 * 1000);
    });
</script>
</script>