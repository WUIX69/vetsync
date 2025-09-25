<style>
    main section.upcoming .dates {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    main section.upcoming .dates .item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        flex: 1;
    }

    main section.upcoming .dates .item h5 {
        font-weight: 600;
        font-size: 0.8rem;
        color: #666;
        margin: 0;
    }

    main section.upcoming .dates .item a {
        color: #666;
        font-size: 13px;
        padding: 8px;
        border-radius: 50%;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid transparent;
        position: relative;
    }

    main section.upcoming .dates .item.active a,
    main section.upcoming .dates .item a:hover {
        color: #fff;
        background: #007bff;
        border-color: #007bff;
    }

    main section.upcoming .dates .item.has-appointments a {
        background: #e3f2fd;
        color: #1976d2;
        border-color: #1976d2;
    }

    main section.upcoming .dates .item.has-appointments.active a {
        background: #1976d2;
        color: white;
    }

    /* Appointment indicator dot */
    main section.upcoming .dates .item.has-appointments a::after {
        content: '';
        position: absolute;
        bottom: -2px;
        right: -2px;
        width: 8px;
        height: 8px;
        background: #ff4444;
        border-radius: 50%;
        border: 2px solid white;
    }

    main section.upcoming .events {
        display: flex;
        flex-direction: column;
        gap: 12px;
        max-height: 300px;
        overflow-y: auto;
    }

    main section.upcoming .events .item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f8f9fa;
        padding: 12px;
        border-radius: 12px;
        border-left: 4px solid #007bff;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    main section.upcoming .events .item:hover {
        background: #e9ecef;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    main section.upcoming .events .item.confirmed {
        border-left-color: #28a745;
    }

    main section.upcoming .events .item.pending {
        border-left-color: #ffc107;
    }

    main section.upcoming .events .item>div {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }

    main section.upcoming .events .item .pet-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    main section.upcoming .events .item .pet-avatar.placeholder {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 0.8rem;
    }

    main section.upcoming .events .item .event-info {
        flex: 1;
    }

    main section.upcoming .events .item .event-info a {
        font-size: 14px;
        color: #2c3e50;
        font-weight: 600;
        text-decoration: none;
        display: block;
        margin-bottom: 4px;
    }

    main section.upcoming .events .item .event-info p {
        font-size: 12px;
        color: #6c757d;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    main section.upcoming .events .item .status-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    main section.upcoming .events .item .status-badge.confirmed {
        background: #d4edda;
        color: #155724;
    }

    main section.upcoming .events .item .status-badge.pending {
        background: #fff3cd;
        color: #856404;
    }

    /* Empty state */
    .empty-appointments {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }

    .empty-appointments i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-appointments h5 {
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .empty-appointments p {
        font-size: 0.9rem;
        margin: 0;
    }

    /* Loading state */
    .loading-appointments {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }

    .loading-appointments i {
        font-size: 2rem;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Header improvements */
    main section.upcoming .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    main section.upcoming .header h4 {
        color: #2c3e50;
        margin: 0;
        font-weight: 600;
    }

    main section.upcoming .header .month-selector {
        background: #f8f9fa;
        border: none;
        padding: 6px 12px;
        border-radius: 8px;
        color: #495057;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    main section.upcoming .header .month-selector:hover {
        background: #e9ecef;
    }
</style>

<section class="upcoming">
    <div class="header">
        <h4>Upcoming Appointments</h4>
        <button class="month-selector" id="currentMonth">
            <span id="monthText">Loading...</span>
            <i class='bx bx-chevron-down'></i>
        </button>
    </div>

    <div class="dates" id="weekDates">
        <!-- Week dates will be loaded here -->
        <div class="loading-appointments">
            <i class='bx bx-loader-alt'></i>
        </div>
    </div>

    <div class="events" id="appointmentsList">
        <!-- Appointments will be loaded here -->
        <div class="loading-appointments">
            <i class='bx bx-loader-alt'></i>
            <p>Loading your appointments...</p>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let currentSelectedDate = null;
        let appointmentsData = null;

        // Load upcoming appointments
        function loadUpcomingAppointments() {
            fetch('/src/features/dashboard/api/upcoming-appointments.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        appointmentsData = data.data;
                        renderWeekDates(data.data.current_week);

                        // Show all appointments by default, or today's if selected
                        const today = new Date().toISOString().split('T')[0];
                        if (currentSelectedDate === null) {
                            currentSelectedDate = today;
                        }

                        // Filter appointments for current selected date
                        const selectedDateAppointments = appointmentsData.grouped_by_date[currentSelectedDate] || [];
                        renderAppointments(selectedDateAppointments);

                        updateMonthDisplay();
                    } else {
                        showError('Failed to load appointments');
                    }
                })
                .catch(error => {
                    console.error('Error loading appointments:', error);
                    showError('Error loading appointments');
                });
        }

        // Render week dates
        function renderWeekDates(weekData) {
            const weekDatesContainer = document.getElementById('weekDates');
            const today = new Date().toISOString().split('T')[0];

            weekDatesContainer.innerHTML = weekData.map((day, index) => {
                const hasAppointments = day.appointments.length > 0;
                const isToday = day.date === today;
                const isActive = currentSelectedDate === day.date;

                return `
                <div class="item ${isActive ? 'active' : ''} ${hasAppointments ? 'has-appointments' : ''}" 
                     data-date="${day.date}">
                    <h5>${day.day_name}</h5>
                    <a href="javascript:void(0)" onclick="selectDate('${day.date}')">${day.day_number}</a>
                </div>
            `;
            }).join('');
        }

        // Select a specific date
        window.selectDate = function (date) {
            currentSelectedDate = date;

            // Update active date
            document.querySelectorAll('.dates .item').forEach(item => {
                item.classList.remove('active');
            });
            document.querySelector(`[data-date="${date}"]`)?.classList.add('active');

            // Filter and show appointments for selected date
            if (appointmentsData) {
                const dateAppointments = appointmentsData.grouped_by_date[date] || [];
                renderAppointments(dateAppointments);
            }
        };

        // Render appointments
        function renderAppointments(appointments) {
            const appointmentsList = document.getElementById('appointmentsList');

            if (appointments.length === 0) {
                const selectedDateText = currentSelectedDate ?
                    new Date(currentSelectedDate + 'T00:00:00').toLocaleDateString('en-US', {
                        weekday: 'long',
                        month: 'long',
                        day: 'numeric'
                    }) : 'the next 2 weeks';

                appointmentsList.innerHTML = `
                    <div class="empty-appointments">
                        <i class='bx bx-calendar-x'></i>
                        <h5>No accepted events</h5>
                        <p>No accepted appointments or ready pickups for ${selectedDateText}</p>
                    </div>
                `;
                return;
            }

            appointmentsList.innerHTML = appointments.map(appointment => {
                if (appointment.type === 'appointment') {
                    // Render accepted appointment
                    const petInitial = appointment.pet_name ? appointment.pet_name.charAt(0).toUpperCase() : 'P';
                    const serviceName = appointment.service_name.startsWith('CUSTOM SERVICE REQUEST:')
                        ? appointment.service_name.replace('CUSTOM SERVICE REQUEST:', '').trim()
                        : appointment.service_name;

                    return `
                        <div class="item confirmed" onclick="viewAppointment('${appointment.uuid}')">
                            <div>
                                ${appointment.pet_image && appointment.pet_image !== '/public/img/placeholders/image.png' ?
                            `<img src="${appointment.pet_image}" alt="${appointment.pet_name}" class="pet-avatar">` :
                            `<div class="pet-avatar placeholder">${petInitial}</div>`
                        }
                                <div class="event-info">
                                    <a href="javascript:void(0)">${serviceName}</a>
                                    <p>
                                        <i class='bx bx-time'></i>
                                        ${appointment.formatted_time}
                                        ${appointment.pet_name ? ` • ${appointment.pet_name}` : ''}
                                    </p>
                                </div>
                            </div>
                            <div class="status-badge confirmed">Accepted</div>
                        </div>
                    `;
                } else {
                    // Render ready product reservation
                    return `
                        <div class="item confirmed reservation" onclick="viewReservation('${appointment.uuid}')">
                            <div>
                                <div class="pet-avatar placeholder">📦</div>
                                <div class="event-info">
                                    <a href="javascript:void(0)">${appointment.service_name}</a>
                                    <p>
                                        <i class='bx bx-time'></i>
                                        ${appointment.formatted_time}
                                        <span style="margin-left: 8px; color: #6c757d;">
                                            ${appointment.product_names ? appointment.product_names.slice(0, 2).join(', ') : 'Products'}
                                            ${appointment.product_names && appointment.product_names.length > 2 ? '...' : ''}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="status-badge confirmed">Ready</div>
                        </div>
                    `;
                }
            }).join('');
        }

        // View appointment details
        window.viewAppointment = function (appointmentUuid) {
            // Redirect to appointments page with specific appointment
            window.location.href = `/src/app/user/appointments.php#${appointmentUuid}`;
        };

        // View reservation details
        window.viewReservation = function (reservationUuid) {
            // Redirect to reservations/cart page 
            window.location.href = `/src/app/user/cart.php#${reservationUuid}`;
        };

        // Update month display
        function updateMonthDisplay() {
            const monthText = document.getElementById('monthText');
            const currentDate = new Date();
            monthText.textContent = currentDate.toLocaleDateString('en-US', {
                month: 'long',
                year: 'numeric'
            });
        }

        // Show error state
        function showError(message) {
            document.getElementById('weekDates').innerHTML = `
            <div style="text-align: center; padding: 20px; color: #dc3545;">
                <i class='bx bx-error-circle'></i>
                <p>${message}</p>
            </div>
        `;

            document.getElementById('appointmentsList').innerHTML = `
            <div style="text-align: center; padding: 20px; color: #dc3545;">
                <p>Unable to load appointments</p>
            </div>
        `;
        }

        // Initialize
        loadUpcomingAppointments();

        // Refresh every 5 minutes
        setInterval(loadUpcomingAppointments, 5 * 60 * 1000);
    });
</script>