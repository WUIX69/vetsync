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
    }

    main section.calendar-view .calendar-day.has-appointments {
        border-color: #dc2626;
        background-color: #fef2f2;
    }

    main section.calendar-view .calendar-day.has-appointments:hover {
        background-color: #fee2e2;
    }

    main section.calendar-view .calendar-day.today {
        background-color: #3b82f6;
        color: white;
        font-weight: 600;
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
        background-color: #dc2626;
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
    }

    main section.calendar-view .calendar-day.today .appointment-indicator {
        background-color: #fbbf24;
        color: #1f2937;
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

    // Render calendar
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
            if (hasAppointments) dayClass += ' has-appointments';

            html += `<div class="${dayClass}" data-date="${data.year}-${String(data.month).padStart(2, '0')}-${String(day).padStart(2, '0')}">
            <span class="calendar-day-number">${day}</span>`;

            if (hasAppointments) {
                html += `<div class="appointment-indicator">${hasAppointments.count}</div>`;
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

    // Show appointments for a specific date
    function showAppointmentsForDate(date) {
        // You can implement this to show appointments for the clicked date
        // For now, let's just show an alert
        alert(`Show appointments for ${date}`);
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