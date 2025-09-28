<style>
    main section.upcoming {
        background: #fff;
        padding: 24px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        height: fit-content;
    }

    main section.upcoming .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f1f3f4;
    }

    main section.upcoming .header h4 {
        color: #2c3e50;
        margin: 0;
        font-weight: 600;
        font-size: 1.1rem;
    }

    main section.upcoming .header .month-text {
        background: #f8f9fa;
        padding: 6px 12px;
        border-radius: 8px;
        color: #6c757d;
        font-size: 0.85rem;
        font-weight: 500;
    }

    /* Week dates - cleaner design */
    main section.upcoming .dates {
        display: flex;
        justify-content: space-between;
        margin-bottom: 24px;
        gap: 8px;
    }

    main section.upcoming .dates .item {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        transition: all 0.2s ease;
    }

    main section.upcoming .dates .item h5 {
        font-weight: 500;
        font-size: 0.75rem;
        color: #8e9aaf;
        margin: 0 0 8px 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    main section.upcoming .dates .item a {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.2s ease;
        color: #6c757d;
        background: #f8f9fa;
        border: 2px solid transparent;
        position: relative;
    }

    main section.upcoming .dates .item a:hover {
        background: #e3f2fd;
        color: #1976d2;
        transform: translateY(-2px);
    }

    main section.upcoming .dates .item.active a {
        background: #007bff;
        color: white;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
    }

    main section.upcoming .dates .item.today a {
        background: #28a745;
        color: white;
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    }

    main section.upcoming .dates .item.has-appointments a::after {
        content: '';
        position: absolute;
        top: -2px;
        right: -2px;
        width: 8px;
        height: 8px;
        background: #ff4757;
        border-radius: 50%;
        border: 2px solid white;
    }

    /* Events list - cleaner design */
    main section.upcoming .events {
        max-height: 300px;
        overflow-y: auto;
    }

    main section.upcoming .events .item {
        display: flex;
        align-items: center;
        padding: 16px;
        margin-bottom: 12px;
        background: #f8f9fa;
        border-radius: 12px;
        transition: all 0.2s ease;
        border-left: 4px solid transparent;
    }

    main section.upcoming .events .item:hover {
        background: #e3f2fd;
        transform: translateX(4px);
    }

    main section.upcoming .events .item.appointment {
        border-left-color: #007bff;
    }

    main section.upcoming .events .item.pickup {
        border-left-color: #28a745;
    }

    main section.upcoming .events .item img {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        object-fit: cover;
        margin-right: 16px;
        border: 2px solid #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    main section.upcoming .events .item .details {
        flex: 1;
    }

    main section.upcoming .events .item .details h5 {
        margin: 0 0 4px 0;
        font-size: 0.9rem;
        font-weight: 600;
        color: #2c3e50;
        line-height: 1.2;
    }

    main section.upcoming .events .item .details .meta {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.8rem;
        color: #6c757d;
    }

    main section.upcoming .events .item .details .meta span {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    main section.upcoming .events .item .status {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    main section.upcoming .events .item .status.confirmed {
        background: #d4edda;
        color: #155724;
    }

    main section.upcoming .events .item .status.ready {
        background: #d1ecf1;
        color: #0c5460;
    }

    /* Empty state */
    .no-appointments {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }

    .no-appointments i {
        font-size: 3rem;
        margin-bottom: 16px;
        opacity: 0.4;
        color: #adb5bd;
    }

    .no-appointments h5 {
        color: #495057;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .no-appointments p {
        font-size: 0.85rem;
        margin: 0;
        line-height: 1.4;
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
        color: #007bff;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    /* Scrollbar styling */
    main section.upcoming .events::-webkit-scrollbar {
        width: 4px;
    }

    main section.upcoming .events::-webkit-scrollbar-track {
        background: #f1f3f4;
        border-radius: 2px;
    }

    main section.upcoming .events::-webkit-scrollbar-thumb {
        background: #c1c8d4;
        border-radius: 2px;
    }

    main section.upcoming .events::-webkit-scrollbar-thumb:hover {
        background: #a8b2c1;
    }
</style>

<section class="upcoming">
    <div class="header">
        <h4>Upcoming</h4>
        <div class="month-text" id="monthText">September 2025</div>
    </div>

    <div class="dates" id="weekDates">
        <div class="loading-appointments">
            <i class='bx bx-loader-alt bx-spin'></i>
        </div>
    </div>

    <div class="events" id="appointmentsList">
        <div class="loading-appointments">
            <i class='bx bx-loader-alt bx-spin'></i>
            <p>Loading appointments...</p>
        </div>
    </div>
</section>

<script>
    function initUpcomingEvents() {
        if (typeof $ === 'undefined') {
            setTimeout(initUpcomingEvents, 100);
            return;
        }

        let allAppointments = [];
        let currentWeek = [];
        let selectedDate = new Date().toISOString().split('T')[0];

        function loadUpcomingAppointments() {
            $.ajax({
                url: '/src/features/dashboard/api/upcoming-appointments.php',
                method: 'GET',
                dataType: 'json',
                timeout: 10000,
                success: function (response) {
                    if (response && response.success) {
                        allAppointments = response.data.appointments || [];
                        currentWeek = response.data.current_week || [];

                        renderWeekDates();
                        renderAppointments();
                        updateMonthDisplay();
                    } else {
                        showError('Failed to load appointments');
                    }
                },
                error: function () {
                    showError('Network error occurred');
                }
            });
        }

        function renderWeekDates() {
            const weekDatesContainer = document.getElementById('weekDates');
            if (!weekDatesContainer) return;

            let weekDatesHtml = '';

            currentWeek.forEach(function (day) {
                const hasAppointments = day.appointments && day.appointments.length > 0;
                const isActive = day.date === selectedDate;
                const isToday = day.is_today;

                weekDatesHtml += `
                    <div class="item ${isActive ? 'active' : ''} ${hasAppointments ? 'has-appointments' : ''} ${isToday ? 'today' : ''}" 
                         data-date="${day.date}">
                        <h5>${day.day_name}</h5>
                        <a href="#" onclick="selectDate('${day.date}'); return false;">
                            ${day.day_number}
                        </a>
                    </div>
                `;
            });

            weekDatesContainer.innerHTML = weekDatesHtml;
        }

        function renderAppointments() {
            const appointmentsList = document.getElementById('appointmentsList');
            if (!appointmentsList) return;

            const dayAppointments = allAppointments.filter(function (appointment) {
                return appointment.date === selectedDate;
            });

            if (dayAppointments.length === 0) {
                const isToday = selectedDate === new Date().toISOString().split('T')[0];
                appointmentsList.innerHTML = `
                    <div class="no-appointments">
                        <i class='bx bx-calendar-x'></i>
                        <h5>${isToday ? 'No appointments today' : 'No appointments'}</h5>
                        <p>${isToday ? 'Your schedule is clear for today' : 'Select another date or book a new appointment'}</p>
                    </div>
                `;
                return;
            }

            let appointmentsHtml = '';
            dayAppointments.forEach(function (appointment) {
                const typeClass = appointment.type;
                const statusText = appointment.status === 'confirmed' ? 'Confirmed' : 'Ready';

                appointmentsHtml += `
                    <div class="item ${typeClass}" onclick="viewEvent('${appointment.uuid}', '${appointment.type}')">
                        <img src="${appointment.pet_image}" alt="${appointment.pet_name || 'Event'}" />
                        <div class="details">
                            <h5>${appointment.service_name}</h5>
                            <div class="meta">
                                ${appointment.pet_name ? `<span><i class='bx bx-heart'></i> ${appointment.pet_name}</span>` : ''}
                                <span><i class='bx bx-time'></i> ${appointment.formatted_time}</span>
                                ${appointment.is_today ? '<span><i class="bx bx-calendar-check"></i> Today</span>' : ''}
                            </div>
                        </div>
                        <div class="status ${appointment.status}">${statusText}</div>
                    </div>
                `;
            });

            appointmentsList.innerHTML = appointmentsHtml;
        }

        function selectDate(date) {
            selectedDate = date;
            document.querySelectorAll('.dates .item').forEach(function (item) {
                item.classList.remove('active');
            });
            document.querySelector(`[data-date="${date}"]`)?.classList.add('active');
            renderAppointments();
        }

        function updateMonthDisplay() {
            const monthText = document.getElementById('monthText');
            if (monthText) {
                const now = new Date();
                monthText.textContent = now.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
            }
        }

        function showError(message) {
            document.getElementById('weekDates').innerHTML = `
                <div class="loading-appointments">
                    <i class='bx bx-error-circle'></i>
                </div>
            `;
            document.getElementById('appointmentsList').innerHTML = `
                <div class="no-appointments">
                    <i class='bx bx-error-circle'></i>
                    <h5>Unable to load</h5>
                    <p>${message}</p>
                </div>
            `;
        }

        // Global functions
        window.selectDate = selectDate;
        window.viewEvent = function (uuid, type) {
            if (type === 'appointment') {
                window.location.href = `/src/app/user/appointments.php#${uuid}`;
            } else {
                window.location.href = `/src/app/user/cart.php#${uuid}`;
            }
        };

        // Initialize
        loadUpcomingAppointments();
        setInterval(loadUpcomingAppointments, 5 * 60 * 1000);
    }

    initUpcomingEvents();
</script>