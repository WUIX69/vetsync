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
        cursor: default;
    }

    main section.status .items-list .item:hover {
        transform: translateY(-2px);
    }

    main section.status .items-list .item.clickable {
        cursor: pointer;
    }

    main section.status .items-list .item.clickable:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    main section.status .items-list .item.item-2 {
        background: #fffbeb;
    }

    main section.status .items-list .item.item-3 {
        background: #fef2f2;
    }

    main section.status .items-list .item .info {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
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

    /* Vaccination Modal Styles */
    .vaccination-modal .content {
        max-height: 70vh;
        overflow-y: auto;
    }

    .vaccination-item {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 1rem;
        border-left: 4px solid #f59e0b;
    }

    .vaccination-item.completed {
        border-left-color: #10b981;
        background: #f0fdf4;
    }

    .vaccination-item.overdue {
        border-left-color: #ef4444;
        background: #fef2f2;
    }

    .vaccination-item .pet-name {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .vaccination-item .vaccine-name {
        font-size: 1rem;
        color: #6b7280;
        margin-bottom: 1rem;
    }

    .vaccination-item .progress-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .vaccination-item .session-badge {
        background: #f59e0b;
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .vaccination-item.completed .session-badge {
        background: #10b981;
    }

    .vaccination-item.overdue .session-badge {
        background: #ef4444;
    }

    .vaccination-item .next-date {
        font-size: 0.9rem;
        color: #4b5563;
    }

    .vaccination-item .book-btn {
        margin-top: 1rem;
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

<!-- Vaccination Tracking Modal -->
<div class="ui modal vaccination-modal">
    <i class="close icon"></i>
    <div class="header">
        <i class="syringe icon"></i> Vaccination Tracking
    </div>
    <div class="content" id="vaccinationContent">
        <div class="ui active centered inline loader"></div>
    </div>
    <div class="actions">
        <div class="ui black deny button">Close</div>
    </div>
</div>

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

            statsContainer.innerHTML = `
                <div class="row">
                    <div class="col-lg-4">
                        <div class="item item-1 health-${stats.health_status}">
                            <div class="info">
                                <div>
                                    <h5>Health Status</h5>
                                    <p>${stats.health_message}</p>
                                    ${stats.pet_info ? `<div class="next-appointment">${stats.pet_info}</div>` : ''}
                                </div>
                                <i class='bx bx-heart'></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="item item-2 clickable" onclick="showVaccinationModal()">
                            <div class="info">
                                <div>
                                    <h5>Vaccinations</h5>
                                    <p>${stats.vaccination_count} completed</p>
                                    <div class="next-appointment">
                                        ${stats.vaccination_count < (stats.total_pets * 5) ? 'Core vaccines recommended' : 'Up to date!'}
                                        <br><span style="color: #2196f3; font-weight: 600;">Click to view details →</span>
                                    </div>
                                </div>
                                <i class='bx bx-shield-plus'></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="item item-3">
                            <div class="info">
                                <div>
                                    <h5>Next Appointment</h5>
                                    ${stats.next_appointment ?
                    `<p>${stats.next_appointment.service}</p>
                                         <div class="next-appointment">${stats.next_appointment.pet_name ? stats.next_appointment.pet_name + ' • ' : ''}${stats.next_appointment.formatted_date} • ${stats.next_appointment.days_until}</div>` :
                    `<p>No upcoming appointments</p>
                                         <div class="next-appointment"><a href="/src/app/user/services.php" style="color: #3b82f6;">Book now</a></div>`
                }
                                </div>
                                <i class='bx bx-calendar-plus'></i>
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

        // Show vaccination tracking modal
        window.showVaccinationModal = function () {
            $('.vaccination-modal').modal('show');
            loadVaccinationTracking();
        };

        function loadVaccinationTracking() {
            const content = document.getElementById('vaccinationContent');
            content.innerHTML = '<div class="ui active centered inline loader"></div>';

            fetch('/src/features/dashboard/api/vaccination-tracking.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderVaccinationTracking(data.data);
                    } else {
                        content.innerHTML = `<div class="ui negative message">${data.message || 'Failed to load vaccination data'}</div>`;
                    }
                })
                .catch(error => {
                    content.innerHTML = `<div class="ui negative message">Error: ${error.message}</div>`;
                });
        }

        function renderVaccinationTracking(vaccinations) {
            const content = document.getElementById('vaccinationContent');

            if (vaccinations.length === 0) {
                content.innerHTML = `
                    <div class="ui info message">
                        <div class="header">No Vaccination Records</div>
                        <p>No vaccination appointments found. Book a vaccination service to start tracking.</p>
                        <a href="/src/app/user/services.php" class="ui primary button" style="margin-top: 1rem;">
                            <i class="calendar plus icon"></i> Book Vaccination
                        </a>
                    </div>
                `;
                return;
            }

            let html = '';
            vaccinations.forEach(vacc => {
                let badgeClass = 'teal';
                let statusText = vacc.completed_sessions + ' of ' + vacc.total_sessions + ' sessions completed';

                if (vacc.status === 'completed') {
                    badgeClass = 'green';
                } else if (vacc.status === 'ongoing') {
                    badgeClass = 'blue';
                } else if (vacc.status === 'overdue' || vacc.status === 'booster_overdue') {
                    badgeClass = 'red';
                } else if (vacc.status === 'not_started') {
                    badgeClass = 'orange';
                }

                // Determine next step message
                let nextMessage = '';
                if (vacc.status === 'completed') {
                    nextMessage = `<div class="ui success message" style="margin-top: 1rem; padding: 0.75rem;">
                        <i class="check circle icon"></i> Vaccination series completed!<br>
                        <strong>Next:</strong> ${vacc.next_recommended_date}
                    </div>`;
                } else if (vacc.status === 'booster_overdue') {
                    nextMessage = `<div class="ui warning message" style="margin-top: 1rem; padding: 0.75rem;">
                        <i class="exclamation triangle icon"></i> <strong>${vacc.next_recommended_date}</strong>
                    </div>`;
                } else if (vacc.status === 'overdue') {
                    nextMessage = `<div class="ui warning message" style="margin-top: 1rem; padding: 0.75rem;">
                        <i class="exclamation triangle icon"></i> <strong>Next:</strong> ${vacc.next_recommended_date}
                    </div>`;
                } else if (vacc.status === 'ongoing') {
                    nextMessage = `<div class="ui info message" style="margin-top: 1rem; padding: 0.75rem;">
                        <i class="clock icon"></i> <strong>Next session:</strong> ${vacc.next_recommended_date}
                    </div>`;
                } else {
                    nextMessage = `<p style="margin-top: 1rem; color: #666;">
                        <strong>Next:</strong> ${vacc.next_recommended_date}
                    </p>`;
                }

                let actionButton = '';
                if (vacc.status !== 'completed') {
                    actionButton = `<a href="/src/app/user/services.php" class="ui orange button" style="margin-top: 0.5rem;">
                        <i class="calendar plus icon"></i> Book Next Session
                    </a>`;
                } else if (vacc.status === 'booster_overdue') {
                    actionButton = `<a href="/src/app/user/services.php" class="ui red button" style="margin-top: 0.5rem;">
                        <i class="calendar plus icon"></i> Book Annual Booster
                    </a>`;
                }

                html += `
                    <div class="vaccination-item ${vacc.status}">
                        <div class="pet-name">🐾 ${vacc.pet_name}</div>
                        <div class="vaccine-name">${vacc.service_name}</div>
                        <div class="progress-info">
                            <span class="ui ${badgeClass} label" style="font-size: 0.9rem;">
                                ${statusText}
                            </span>
                        </div>
                        ${vacc.last_date_formatted ? `<div class="next-date">Last session: ${vacc.last_date_formatted}</div>` : ''}
                        ${nextMessage}
                        ${actionButton}
                    </div>
                `;
            });

            content.innerHTML = html;
        }

        // Make function globally available for retry button
        window.loadPetHealthStats = loadPetHealthStats;

        // Refresh every 5 minutes
        setInterval(loadPetHealthStats, 5 * 60 * 1000);
    });
</script>