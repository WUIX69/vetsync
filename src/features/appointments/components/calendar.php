<style>
    /* Enhanced Calendar View Section */
    main section.calendar-view .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    main section.calendar-view .calendar-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--color-dark);
    }

    main section.calendar-view .calendar-nav {
        display: flex;
        gap: 0.5rem;
    }

    main section.calendar-view .calendar-nav-btn {
        background-color: var(--color-light);
        border: none;
        border-radius: 0.4rem;
        width: 2rem;
        height: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    main section.calendar-view .calendar-nav-btn:hover {
        background-color: var(--color-primary-light);
    }

    main section.calendar-view .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0.5rem;
    }

    main section.calendar-view .calendar-weekday {
        text-align: center;
        font-weight: 500;
        color: var(--color-dark-variant);
        padding: 0.5rem;
        font-size: 0.9rem;
    }

    main section.calendar-view .calendar-day {
        aspect-ratio: 1/1;
        border-radius: 0.5rem;
        padding: 0.5rem;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: center;
        cursor: pointer;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
        position: relative;
        min-height: 60px;
    }

    main section.calendar-view .calendar-day:hover {
        background-color: #f8fafc;
        border-color: #cbd5e1;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Status-based calendar day styling */
    main section.calendar-view .calendar-day.has-appointments {
        border-width: 2px;
        font-weight: 600;
    }

    /* Mixed statuses - default styling */
    main section.calendar-view .calendar-day.has-appointments {
        border-color: #6b7280;
        background-color: #f9fafb;
    }

    /* Pending appointments - Yellow */
    main section.calendar-view .calendar-day.status-pending {
        border-color: #f59e0b;
        background-color: #fef3c7;
        color: #92400e;
    }

    main section.calendar-view .calendar-day.status-pending:hover {
        background-color: #fde68a;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
    }

    /* Confirmed appointments - Blue */
    main section.calendar-view .calendar-day.status-confirmed {
        border-color: #3b82f6;
        background-color: #dbeafe;
        color: #1d4ed8;
    }

    main section.calendar-view .calendar-day.status-confirmed:hover {
        background-color: #bfdbfe;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
    }

    /* Completed appointments - Green */
    main section.calendar-view .calendar-day.status-completed {
        border-color: #10b981;
        background-color: #d1fae5;
        color: #047857;
    }

    main section.calendar-view .calendar-day.status-completed:hover {
        background-color: #a7f3d0;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }

    /* Cancelled appointments - Red */
    main section.calendar-view .calendar-day.status-cancelled {
        border-color: #ef4444;
        background-color: #fee2e2;
        color: #dc2626;
    }

    main section.calendar-view .calendar-day.status-cancelled:hover {
        background-color: #fecaca;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }

    /* Mixed statuses - Multiple colors */
    main section.calendar-view .calendar-day.status-mixed {
        border: 2px solid;
        border-image: linear-gradient(45deg, #f59e0b, #3b82f6, #10b981, #ef4444) 1;
        background: linear-gradient(45deg, #fef3c7, #dbeafe, #d1fae5, #fee2e2);
        color: #374151;
    }

    main section.calendar-view .calendar-day.status-mixed:hover {
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15);
    }

    main section.calendar-view .calendar-day.today {
        background-color: #3b82f6;
        color: white;
        font-weight: 600;
        border-color: #1d4ed8;
    }

    main section.calendar-view .calendar-day.today:hover {
        background-color: #2563eb;
    }

    main section.calendar-view .calendar-day.other-month {
        color: #9ca3af;
        background-color: #f9fafb;
    }

    main section.calendar-view .calendar-day-number {
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    main section.calendar-view .appointment-indicator {
        background-color: #374151;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: bold;
        margin-top: auto;
        position: relative;
    }

    /* Status-specific appointment indicators */
    main section.calendar-view .calendar-day.status-pending .appointment-indicator {
        background-color: #f59e0b;
        color: white;
    }

    main section.calendar-view .calendar-day.status-confirmed .appointment-indicator {
        background-color: #3b82f6;
        color: white;
    }

    main section.calendar-view .calendar-day.status-completed .appointment-indicator {
        background-color: #10b981;
        color: white;
    }

    main section.calendar-view .calendar-day.status-cancelled .appointment-indicator {
        background-color: #ef4444;
        color: white;
    }

    main section.calendar-view .calendar-day.status-mixed .appointment-indicator {
        background: linear-gradient(45deg, #f59e0b, #3b82f6, #10b981, #ef4444);
        color: white;
    }

    main section.calendar-view .calendar-day.today .appointment-indicator {
        background-color: #fbbf24;
        color: #1f2937;
    }

    /* Status indicators in appointment indicator */
    main section.calendar-view .status-dots {
        display: flex;
        gap: 2px;
        margin-top: 2px;
        justify-content: center;
    }

    main section.calendar-view .status-dot {
        width: 4px;
        height: 4px;
        border-radius: 50%;
    }

    main section.calendar-view .status-dot.pending {
        background-color: #f59e0b;
    }

    main section.calendar-view .status-dot.confirmed {
        background-color: #3b82f6;
    }

    main section.calendar-view .status-dot.completed {
        background-color: #10b981;
    }

    main section.calendar-view .status-dot.cancelled {
        background-color: #ef4444;
    }

    /* Calendar Legend - SIMPLE & COMPACT */
    main section.calendar-view .calendar-legend {
        display: flex !important;
        justify-content: center;
        gap: 1rem;
        margin: 1rem 0;
        padding: 1rem 1.5rem;
        background: white !important;
        border-radius: 8px;
        flex-wrap: wrap;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
        border: 2px solid #e5e7eb !important;
        visibility: visible !important;
    }

    main section.calendar-view .legend-item {
        display: flex !important;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem !important;
        font-weight: 600 !important;
        color: #374151 !important;
        padding: 0.25rem 0;
    }

    main section.calendar-view .legend-color {
        width: 16px !important;
        height: 16px !important;
        border-radius: 3px;
        border: 2px solid !important;
        flex-shrink: 0;
    }

    main section.calendar-view .legend-color.pending {
        background-color: #fbbf24 !important;
        border-color: #f59e0b !important;
    }

    main section.calendar-view .legend-color.confirmed {
        background-color: #60a5fa !important;
        border-color: #3b82f6 !important;
    }

    main section.calendar-view .legend-color.completed {
        background-color: #34d399 !important;
        border-color: #10b981 !important;
    }

    main section.calendar-view .legend-color.cancelled {
        background-color: #f87171 !important;
        border-color: #ef4444 !important;
    }

    main section.calendar-view .legend-color.mixed {
        background: linear-gradient(45deg, #fbbf24, #60a5fa, #34d399, #f87171) !important;
        border: 2px solid #6b7280 !important;
    }

    /* Loading state */
    main section.calendar-view .loading {
        text-align: center;
        padding: 2rem;
        color: #6b7280;
    }
</style>

<section class="calendar-view box">
    <div class="calendar-header">
        <h2 class="calendar-title" id="calendarTitle">Loading...</h2>
        <div class="calendar-nav">
            <button class="calendar-nav-btn" id="prevMonth">
                <i class="material-icons-sharp">chevron_left</i>
            </button>
            <button class="calendar-nav-btn" id="nextMonth">
                <i class="material-icons-sharp">chevron_right</i>
            </button>
        </div>
    </div>
    <div class="calendar-grid" id="calendarGrid">
        <div class="loading">Loading calendar...</div>
    </div>

    <!-- Calendar Legend -->
    <div class="calendar-legend">
        <div class="legend-item">
            <div class="legend-color pending"></div>
            <span>Pending</span>
        </div>
        <div class="legend-item">
            <div class="legend-color confirmed"></div>
            <span>Confirmed</span>
        </div>
        <div class="legend-item">
            <div class="legend-color completed"></div>
            <span>Completed</span>
        </div>
        <div class="legend-item">
            <div class="legend-color cancelled"></div>
            <span>Cancelled</span>
        </div>
        <div class="legend-item">
            <div class="legend-color mixed"></div>
            <span>Mixed Status</span>
        </div>
    </div>
</section>

<script>
    // Calendar functionality
    let currentMonth = new Date().getMonth() + 1;
    let currentYear = new Date().getFullYear();

    // Load calendar data
    function loadCalendar(month, year) {
        const calendarTitle = document.getElementById('calendarTitle');
        const calendarGrid = document.getElementById('calendarGrid');

        // Show loading
        calendarGrid.innerHTML = '<div class="loading">Loading calendar...</div>';

        fetch(`/src/features/appointments/api/calendar.php?month=${month}&year=${year}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderCalendar(data.data);
                } else {
                    calendarGrid.innerHTML = '<div class="loading">Error loading calendar</div>';
                }
            })
            .catch(error => {
                console.error('Calendar error:', error);
                calendarGrid.innerHTML = '<div class="loading">Error loading calendar</div>';
            });
    }

    // Render calendar with status-based styling
    function renderCalendar(data) {
        const calendarTitle = document.getElementById('calendarTitle');
        const calendarGrid = document.getElementById('calendarGrid');

        calendarTitle.textContent = data.monthName;

        let html = '';

        // Weekday headers
        const weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        weekdays.forEach(day => {
            html += `<div class="calendar-weekday">${day}</div>`;
        });

        // Previous month days
        const prevMonth = data.month === 1 ? 12 : data.month - 1;
        const prevYear = data.month === 1 ? data.year - 1 : data.year;
        const daysInPrevMonth = new Date(prevYear, prevMonth, 0).getDate();

        for (let i = data.firstDayOfWeek - 1; i >= 0; i--) {
            const day = daysInPrevMonth - i;
            html += `<div class="calendar-day other-month">
            <span class="calendar-day-number">${day}</span>
        </div>`;
        }

        // Current month days
        const today = new Date();
        const isCurrentMonth = data.month === (today.getMonth() + 1) && data.year === today.getFullYear();

        for (let day = 1; day <= data.daysInMonth; day++) {
            const isToday = isCurrentMonth && day === today.getDate();
            const hasAppointments = data.appointments[day];

            let dayClass = 'calendar-day';
            if (isToday) dayClass += ' today';
            if (hasAppointments) {
                dayClass += ' has-appointments';

                // Determine the primary status for styling
                const statuses = hasAppointments.statuses;
                if (statuses.length === 1) {
                    // Single status
                    const status = statuses[0];
                    if (status === 'accepted') {
                        dayClass += ' status-confirmed';
                    } else {
                        dayClass += ` status-${status}`;
                    }
                } else {
                    // Multiple statuses
                    dayClass += ' status-mixed';
                }
            }

            html += `<div class="${dayClass}" data-date="${data.year}-${String(data.month).padStart(2, '0')}-${String(day).padStart(2, '0')}">
            <span class="calendar-day-number">${day}</span>`;

            if (hasAppointments) {
                html += `<div class="appointment-indicator">${hasAppointments.count}</div>`;

                // Add status dots for mixed statuses
                if (hasAppointments.statuses.length > 1) {
                    html += '<div class="status-dots">';
                    const uniqueStatuses = [...new Set(hasAppointments.statuses)];
                    uniqueStatuses.forEach(status => {
                        const statusClass = status === 'accepted' ? 'confirmed' : status;
                        html += `<div class="status-dot ${statusClass}"></div>`;
                    });
                    html += '</div>';
                }
            }

            html += '</div>';
        }

        // Next month days to fill the grid
        const totalCells = Math.ceil((data.daysInMonth + data.firstDayOfWeek) / 7) * 7;
        const remainingCells = totalCells - (data.daysInMonth + data.firstDayOfWeek);

        for (let day = 1; day <= remainingCells; day++) {
            html += `<div class="calendar-day other-month">
            <span class="calendar-day-number">${day}</span>
        </div>`;
        }

        calendarGrid.innerHTML = html;

        // Add click handlers for days with appointments
        document.querySelectorAll('.calendar-day.has-appointments').forEach(dayEl => {
            dayEl.addEventListener('click', function () {
                const date = this.dataset.date;
                if (date) {
                    showAppointmentsForDate(date);
                }
            });
        });
    }

    // Show appointments for a specific date with quick actions
    function showAppointmentsForDate(date) {
        console.log('Fetching appointments for date:', date); // Debug log

        // Show loading indicator
        showCalendarLoading(true);

        // Fetch appointments for the specific date
        fetch(`/src/features/appointments/api/appointments.php?action=get_by_date&date=${date}`)
            .then(response => {
                console.log('Response status:', response.status); // Debug log
                return response.json();
            })
            .then(data => {
                console.log('API Response:', data); // Debug log
                showCalendarLoading(false);

                if (data.success) {
                    if (data.data && data.data.length > 0) {
                        // Always show modal for any appointments (single or multiple)
                        showDateAppointmentsModal(date, data.data);
                    } else {
                        // No appointments found - redirect to appointments page with date filter
                        if (confirm(`No appointments found for ${new Date(date).toLocaleDateString()}.\n\nWould you like to view the appointments page for this date?`)) {
                            window.location.href = `/src/app/admin/appointments.php?date=${date}`;
                        }
                    }
                } else {
                    alert('Error: ' + (data.message || 'Failed to load appointments'));
                }
            })
            .catch(error => {
                showCalendarLoading(false);
                console.error('Fetch error:', error);
                alert('Network error loading appointments. Please check your connection.');
            });
    }

    // Show loading indicator
    function showCalendarLoading(show) {
        const existingLoader = document.getElementById('calendar-date-loader');
        if (existingLoader) {
            existingLoader.remove();
        }

        if (show) {
            const loader = document.createElement('div');
            loader.id = 'calendar-date-loader';
            loader.innerHTML = `
                <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; align-items: center; justify-content: center;">
                    <div style="background: white; padding: 2rem; border-radius: 8px; text-align: center;">
                        <div class="ui active inline loader"></div>
                        <p style="margin-top: 1rem; margin-bottom: 0;">Loading appointments...</p>
                    </div>
                </div>
            `;
            document.body.appendChild(loader);
        }
    }

    // Show modal with appointments for a specific date
    function showDateAppointmentsModal(date, appointments) {
        const modalHtml = `
            <div class="ui modal" id="dateAppointmentsModal">
                <div class="header">
                    <i class="calendar icon"></i>
                    Appointments for ${new Date(date).toLocaleDateString()}
                </div>
                <div class="content">
                    <div class="ui divided items">
                        ${appointments.map(appointment => createAppointmentModalItem(appointment)).join('')}
                    </div>
                </div>
                <div class="actions">
                    <div class="ui cancel button">Close</div>
                    <div class="ui primary button" onclick="viewAllAppointments('${date}')">
                        <i class="external link icon"></i>
                        View All in Appointments Page
                    </div>
                </div>
            </div>
        `;

        // Remove existing modal if any
        const existingModal = document.getElementById('dateAppointmentsModal');
        if (existingModal) {
            existingModal.remove();
        }

        // Add modal to page
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        // Show modal
        $('#dateAppointmentsModal').modal({
            closable: true,
            onHidden: function () {
                $(this).remove();
            }
        }).modal('show');
    }

    // Create appointment item for modal
    function createAppointmentModalItem(appointment) {
        const statusColors = {
            'pending': 'orange',
            'accepted': 'blue',
            'completed': 'green',
            'cancelled': 'red'
        };

        const statusLabels = {
            'pending': 'Pending',
            'accepted': 'Confirmed',
            'completed': 'Completed',
            'cancelled': 'Cancelled'
        };

        const statusColor = statusColors[appointment.status] || 'grey';
        const statusLabel = statusLabels[appointment.status] || appointment.status;

        let actionButtons = '';

        if (appointment.status === 'pending') {
            actionButtons = `
                <button class="ui mini green button" onclick="quickUpdateStatus('${appointment.uuid}', 'accepted')">
                    <i class="check icon"></i> Confirm
                </button>
                <button class="ui mini orange button" onclick="quickReschedule('${appointment.uuid}')">
                    <i class="calendar icon"></i> Reschedule
                </button>
                <button class="ui mini red button" onclick="quickCancel('${appointment.uuid}')">
                    <i class="times icon"></i> Cancel
                </button>
            `;
        } else if (appointment.status === 'accepted') {
            actionButtons = `
                <button class="ui mini green button" onclick="quickUpdateStatus('${appointment.uuid}', 'completed')">
                    <i class="checkmark icon"></i> Complete
                </button>
                <button class="ui mini orange button" onclick="quickReschedule('${appointment.uuid}')">
                    <i class="calendar icon"></i> Reschedule
                </button>
                <button class="ui mini red button" onclick="quickCancel('${appointment.uuid}')">
                    <i class="times icon"></i> Cancel
                </button>
            `;
        } else {
            actionButtons = `
                <button class="ui mini basic button" onclick="viewAppointmentDetails('${appointment.uuid}')">
                    <i class="eye icon"></i> View Details
                </button>
            `;
        }

        return `
            <div class="item">
                <div class="ui mini circular image">
                    <img src="${appointment.pet_image || '/public/img/placeholders/image.png'}" 
                         onerror="this.src='/public/img/placeholders/image.png'">
                </div>
                <div class="content">
                    <div class="header">
                        ${appointment.pet_name || 'Unknown Pet'}
                        <div class="ui ${statusColor} label mini">${statusLabel}</div>
                    </div>
                    <div class="meta">
                        <span><i class="user icon"></i> ${appointment.user_name || appointment.user_email || 'Unknown Owner'}</span>
                    </div>
                    <div class="description">
                        <strong>Service:</strong> ${appointment.service_name || 'Custom Service'}<br>
                        <strong>Time:</strong> ${appointment.formatted_time || 'Not specified'}
                        ${appointment.note ? `<br><strong>Notes:</strong> ${appointment.note.substring(0, 100)}${appointment.note.length > 100 ? '...' : ''}` : ''}
                    </div>
                    <div class="extra">
                        ${actionButtons}
                    </div>
                </div>
            </div>
        `;
    }

    // Quick status update function
    function quickUpdateStatus(uuid, newStatus) {
        const statusNames = {
            'accepted': 'confirmed',
            'completed': 'completed',
            'cancelled': 'cancelled'
        };

        if (!confirm(`Are you sure you want to mark this appointment as ${statusNames[newStatus] || newStatus}?`)) {
            return;
        }

        fetch('/src/features/appointments/api/appointments.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=update_status&uuid=${uuid}&status=${newStatus}`
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    $('#dateAppointmentsModal').modal('hide');
                    // Reload calendar
                    loadCalendar(currentMonth, currentYear);
                    // Show success message
                    alert('Appointment status updated successfully!');
                } else {
                    alert('Failed to update appointment: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error updating appointment:', error);
                alert('Error updating appointment status');
            });
    }

    // Quick reschedule function
    function quickReschedule(uuid) {
        const newDate = prompt('Enter new date (YYYY-MM-DD):');
        if (!newDate) return;

        // Validate date format
        const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
        if (!dateRegex.test(newDate)) {
            alert('Please enter date in YYYY-MM-DD format');
            return;
        }

        const reason = prompt('Reason for rescheduling:') || 'Rescheduled by admin';

        fetch('/src/features/appointments/api/appointments.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=reschedule&uuid=${uuid}&new_date=${newDate}&reason=${encodeURIComponent(reason)}`
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#dateAppointmentsModal').modal('hide');
                    loadCalendar(currentMonth, currentYear);
                    alert('Appointment rescheduled successfully!');
                } else {
                    alert('Failed to reschedule appointment: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error rescheduling appointment:', error);
                alert('Error rescheduling appointment');
            });
    }

    // Quick cancel function  
    function quickCancel(uuid) {
        const reason = prompt('Reason for cancellation:');
        if (!reason) return;

        fetch('/src/features/appointments/api/appointments.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=update_status&uuid=${uuid}&status=cancelled&cancellation_reason=${encodeURIComponent(reason)}`
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#dateAppointmentsModal').modal('hide');
                    loadCalendar(currentMonth, currentYear);
                    alert('Appointment cancelled successfully!');
                } else {
                    alert('Failed to cancel appointment: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error cancelling appointment:', error);
                alert('Error cancelling appointment');
            });
    }

    // View all appointments for date
    function viewAllAppointments(date) {
        window.location.href = `/src/app/admin/appointments.php?date=${date}`;
    }

    // View appointment details
    function viewAppointmentDetails(uuid) {
        window.location.href = `/src/app/admin/appointments.php?highlight=${uuid}`;
    }

    // Navigation handlers
    document.getElementById('prevMonth').addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 1) {
            currentMonth = 12;
            currentYear--;
        }
        loadCalendar(currentMonth, currentYear);
    });

    document.getElementById('nextMonth').addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 12) {
            currentMonth = 1;
            currentYear++;
        }
        loadCalendar(currentMonth, currentYear);
    });

    // Load initial calendar
    loadCalendar(currentMonth, currentYear);
</script>