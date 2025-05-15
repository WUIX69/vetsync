<?php include_once '../../../src/utils/php/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta') ?>
    <title>Admin Appointments Dashboard</title>
    <?= shared('elements/styles') ?>
    <style>
        /* Admin Appointments Dashboard Styles */
        .appointments-container {
            padding: 1.5rem 0;
        }

        .calendar-view {
            background-color: var(--color-white);
            border-radius: 0.8rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .calendar-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--color-dark);
        }

        .calendar-nav {
            display: flex;
            gap: 0.5rem;
        }

        .calendar-nav-btn {
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

        .calendar-nav-btn:hover {
            background-color: var(--color-primary-light);
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.5rem;
        }

        .calendar-weekday {
            text-align: center;
            font-weight: 500;
            color: var(--color-dark-variant);
            padding: 0.5rem;
        }

        .calendar-day {
            aspect-ratio: 1/1;
            border-radius: 0.5rem;
            padding: 0.5rem;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            cursor: pointer;
            border: 1px solid var(--color-light);
            transition: all 0.2s ease;
        }

        .calendar-day:hover {
            background-color: var(--color-light);
        }

        .calendar-day.has-appointments {
            border-color: var(--color-primary-light);
        }

        .calendar-day.active {
            background-color: var(--color-primary-light);
            color: var(--color-primary);
            font-weight: 500;
        }

        .calendar-day-number {
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 0.3rem;
        }

        .appointment-count {
            font-size: 0.7rem;
            background-color: var(--color-primary);
            color: white;
            border-radius: 1rem;
            padding: 0.1rem 0.4rem;
        }

        .recent-appointments {
            background-color: var(--color-white);
            border-radius: 0.8rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            height: 100%;
        }

        .appointment-card {
            background-color: var(--color-white);
            border-radius: 0.6rem;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--color-primary);
            box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.03);
            transition: transform 0.2s ease;
        }

        .appointment-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.3rem 0.8rem rgba(0, 0, 0, 0.08);
        }

        .appointment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .appointment-time {
            font-size: 0.8rem;
            color: var(--color-dark-variant);
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .appointment-status {
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 1rem;
            font-weight: 500;
        }

        .status-confirmed {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .status-completed {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .status-cancelled {
            background-color: #ffebee;
            color: #c62828;
        }

        .status-pending {
            background-color: #fff8e1;
            color: #f57c00;
        }

        .appointment-patient {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .patient-avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background-color: var(--color-light);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .patient-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .patient-info h4 {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.2rem;
        }

        .patient-info p {
            font-size: 0.8rem;
            color: var(--color-dark-variant);
        }

        .appointment-service {
            font-size: 0.85rem;
            color: var(--color-dark);
            margin-bottom: 0.75rem;
        }

        .appointment-actions {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            border: none;
            background-color: var(--color-light);
            border-radius: 0.4rem;
            padding: 0.4rem 0.6rem;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            background-color: var(--color-primary-light);
        }

        .action-btn.primary {
            background-color: var(--color-primary);
            color: white;
        }

        .action-btn.primary:hover {
            background-color: var(--color-primary-dark);
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background-color: var(--color-white);
            border-radius: 0.8rem;
            padding: 1.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--color-dark-variant);
        }

        .upcoming-appointments {
            background-color: var(--color-white);
            border-radius: 0.8rem;
            padding: 1.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--color-dark);
        }

        .date-separator {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--color-dark-variant);
            margin: 1rem 0 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .date-separator::after {
            content: "";
            flex: 1;
            height: 1px;
            background-color: var(--color-light);
        }

        /* Admin Appointments Dashboard Styles */
        main section {
            padding: 1rem;
            background-color: #f6f8fb;
        }

        main section .appointments-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        main section .appointments-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--color-dark);
        }

        main section .calendar-section {
            background-color: var(--color-white);
            border-radius: 0.8rem;
            box-shadow: 0 0.15rem 0.5rem rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        main section .month-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        main section .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
        }

        main section .weekday-header {
            text-align: center;
            padding: 0.5rem;
            font-size: 0.9rem;
            color: var(--color-dark-variant);
            font-weight: 500;
        }

        main section .calendar-day {
            text-align: center;
            padding: 0.7rem;
            font-size: 0.9rem;
        }

        main section .calendar-day.active {
            background-color: var(--color-primary);
            color: white;
            border-radius: 50%;
        }

        main section .stats-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        main section .stat-card {
            background-color: var(--color-white);
            border-radius: 0.8rem;
            padding: 1.5rem;
            box-shadow: 0 0.15rem 0.5rem rgba(0, 0, 0, 0.05);
        }

        main section .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.3rem;
        }

        main section .stat-label {
            font-size: 0.85rem;
            color: var(--color-dark-variant);
        }

        main section .appointments-list {
            background-color: var(--color-white);
            border-radius: 0.8rem;
            padding: 1.5rem;
            box-shadow: 0 0.15rem 0.5rem rgba(0, 0, 0, 0.05);
        }

        main section .appointments-day-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--color-dark);
        }

        main section .time-section {
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: var(--color-dark-variant);
            font-weight: 500;
        }

        main section .appointment-item {
            margin-bottom: 2rem;
        }

        main section .appointment-time {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            color: var(--color-dark-variant);
        }

        main section .appointment-status {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border-radius: 1rem;
            font-size: 0.7rem;
            font-weight: 500;
            float: right;
        }

        main section .status-confirmed {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        main section .status-pending {
            background-color: #fff8e1;
            color: #f57c00;
        }

        main section .status-completed {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        main section .status-cancelled {
            background-color: #ffebee;
            color: #c62828;
        }

        main section .pet-info {
            display: flex;
            gap: 1.5rem;
        }

        main section .pet-avatar {
            width: 4rem;
            height: 4rem;
            border-radius: 50%;
            overflow: hidden;
        }

        main section .pet-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        main section .pet-details {
            flex: 1;
        }

        main section .pet-name {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.2rem;
        }

        main section .pet-owner {
            font-size: 0.85rem;
            color: var(--color-dark-variant);
            margin-bottom: 0.5rem;
        }

        main section .service-info {
            margin-top: 0.5rem;
            margin-bottom: 1rem;
        }

        main section .service-label {
            font-weight: 500;
            display: inline-block;
            margin-right: 0.3rem;
        }

        main section .action-buttons {
            display: flex;
            gap: 0.8rem;
        }

        main section .action-btn {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
            border-radius: 0.3rem;
            border: none;
            cursor: pointer;
        }

        main section .btn-primary {
            background-color: var(--color-primary);
            color: white;
        }

        main section .btn-secondary {
            background-color: transparent;
            color: var(--color-dark-variant);
        }

        /* Right sidebar */
        main section .recent-appointments {
            background-color: var(--color-white);
            border-radius: 0.8rem;
            padding: 1.5rem;
            box-shadow: 0 0.15rem 0.5rem rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        main section .recent-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--color-dark);
        }

        main section .recent-item {
            margin-bottom: 1.5rem;
        }

        main section .recent-time {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            color: var(--color-dark-variant);
        }

        main section .recent-avatar {
            width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
        }

        main section .recent-avatar img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 0.5rem;
        }

        main section .recent-pet-name {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.2rem;
        }

        main section .recent-service {
            font-size: 0.8rem;
            color: var(--color-dark-variant);
        }
    </style>
</head>

<body>

    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window') ?> <!-- Window Spinner -->
    </div>

    <div class="container-body pusher">
        <!-- Sidebar -->
        <?= featured('admin/shared/layouts/sidebar') ?> <!-- Sidebar -->

        <!-- Main Content -->
        <main class="container-main">
            <!-- Header Navbar -->
            <?= featured('admin/appointment/components/header') ?>

            <div class="row appointments-container">
                <div class="col-lg-9">
                    <!-- Calendar View -->
                    <div class="calendar-view">
                        <div class="calendar-header">
                            <h2 class="calendar-title">June 2023</h2>
                            <div class="calendar-nav">
                                <button class="calendar-nav-btn">
                                    <i class="material-icons-sharp">chevron_left</i>
                                </button>
                                <button class="calendar-nav-btn">
                                    <i class="material-icons-sharp">chevron_right</i>
                                </button>
                            </div>
                        </div>
                        <div class="calendar-grid">
                            <!-- Weekday Headers -->
                            <div class="calendar-weekday">Sun</div>
                            <div class="calendar-weekday">Mon</div>
                            <div class="calendar-weekday">Tue</div>
                            <div class="calendar-weekday">Wed</div>
                            <div class="calendar-weekday">Thu</div>
                            <div class="calendar-weekday">Fri</div>
                            <div class="calendar-weekday">Sat</div>

                            <!-- Calendar Days -->
                            <div class="calendar-day">
                                <span class="calendar-day-number">28</span>
                            </div>
                            <div class="calendar-day">
                                <span class="calendar-day-number">29</span>
                            </div>
                            <div class="calendar-day">
                                <span class="calendar-day-number">30</span>
                            </div>
                            <div class="calendar-day">
                                <span class="calendar-day-number">31</span>
                            </div>
                            <div class="calendar-day has-appointments">
                                <span class="calendar-day-number">1</span>
                                <span class="appointment-count">3</span>
                            </div>
                            <div class="calendar-day">
                                <span class="calendar-day-number">2</span>
                            </div>
                            <div class="calendar-day">
                                <span class="calendar-day-number">3</span>
                            </div>

                            <div class="calendar-day">
                                <span class="calendar-day-number">4</span>
                            </div>
                            <div class="calendar-day has-appointments">
                                <span class="calendar-day-number">5</span>
                                <span class="appointment-count">2</span>
                            </div>
                            <div class="calendar-day">
                                <span class="calendar-day-number">6</span>
                            </div>
                            <div class="calendar-day has-appointments">
                                <span class="calendar-day-number">7</span>
                                <span class="appointment-count">1</span>
                            </div>
                            <div class="calendar-day active">
                                <span class="calendar-day-number">8</span>
                                <span class="appointment-count">5</span>
                            </div>
                            <div class="calendar-day">
                                <span class="calendar-day-number">9</span>
                            </div>
                            <div class="calendar-day">
                                <span class="calendar-day-number">10</span>
                            </div>

                            <!-- Additional rows would continue... -->
                        </div>
                    </div>

                    <!-- Stats overview -->
                    <div class="stats-container">
                        <div class="stat-card">
                            <div class="stat-value">18</div>
                            <div class="stat-label">Appointments this month</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">5</div>
                            <div class="stat-label">Appointments today</div>
                        </div>
                    </div>

                    <!-- Recent Appointments for selected day -->
                    <div class="upcoming-appointments">
                        <h3 class="section-title">Appointments for June 8, 2023</h3>

                        <div class="date-separator">Morning</div>

                        <div class="appointment-card">
                            <div class="appointment-header">
                                <div class="appointment-time">
                                    <i class="material-icons-sharp">schedule</i>
                                    09:00 AM
                                </div>
                                <span class="appointment-status status-confirmed">Confirmed</span>
                            </div>
                            <div class="appointment-patient">
                                <div class="patient-avatar">
                                    <img src="<?= asset('img/avatars/chris.jpg'); ?>" alt="Pet">
                                </div>
                                <div class="patient-info">
                                    <h4>Max (Golden Retriever)</h4>
                                    <p>Owner: John Smith</p>
                                </div>
                            </div>
                            <div class="appointment-service">
                                <strong>Service:</strong> Vaccination
                            </div>
                            <div class="appointment-actions">
                                <button class="action-btn primary">Confirm</button>
                                <button class="action-btn">Reschedule</button>
                                <button class="action-btn">Cancel</button>
                            </div>
                        </div>

                        <div class="appointment-card">
                            <div class="appointment-header">
                                <div class="appointment-time">
                                    <i class="material-icons-sharp">schedule</i>
                                    10:30 AM
                                </div>
                                <span class="appointment-status status-pending">Pending</span>
                            </div>
                            <div class="appointment-patient">
                                <div class="patient-avatar">
                                    <img src="<?= asset('img/avatars/elliot.jpg'); ?>" alt="Pet">
                                </div>
                                <div class="patient-info">
                                    <h4>Luna (Persian Cat)</h4>
                                    <p>Owner: Sarah Johnson</p>
                                </div>
                            </div>
                            <div class="appointment-service">
                                <strong>Service:</strong> Routine Check-up
                            </div>
                            <div class="appointment-actions">
                                <button class="action-btn primary">Confirm</button>
                                <button class="action-btn">Reschedule</button>
                                <button class="action-btn">Cancel</button>
                            </div>
                        </div>

                        <div class="date-separator">Afternoon</div>

                        <div class="appointment-card">
                            <div class="appointment-header">
                                <div class="appointment-time">
                                    <i class="material-icons-sharp">schedule</i>
                                    01:15 PM
                                </div>
                                <span class="appointment-status status-confirmed">Confirmed</span>
                            </div>
                            <div class="appointment-patient">
                                <div class="patient-avatar">
                                    <img src="<?= asset('img/avatars/helen.jpg'); ?>" alt="Pet">
                                </div>
                                <div class="patient-info">
                                    <h4>Bella (Labrador)</h4>
                                    <p>Owner: Michael Brown</p>
                                </div>
                            </div>
                            <div class="appointment-service">
                                <strong>Service:</strong> Dental Cleaning
                            </div>
                            <div class="appointment-actions">
                                <button class="action-btn primary">Confirm</button>
                                <button class="action-btn">Reschedule</button>
                                <button class="action-btn">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <!-- Recent Appointments Widget -->
                    <div class="recent-appointments">
                        <h3 class="section-title">Recent Appointments</h3>

                        <div class="appointment-card">
                            <div class="appointment-header">
                                <div class="appointment-time">
                                    <i class="material-icons-sharp">today</i>
                                    Today, 10:00 AM
                                </div>
                                <span class="appointment-status status-completed">Completed</span>
                            </div>
                            <div class="appointment-patient">
                                <div class="patient-avatar">
                                    <img src="<?= asset('img/avatars/jenny.jpg'); ?>" alt="Pet">
                                </div>
                                <div class="patient-info">
                                    <h4>Charlie (Beagle)</h4>
                                    <p>Vaccination</p>
                                </div>
                            </div>
                        </div>

                        <div class="appointment-card">
                            <div class="appointment-header">
                                <div class="appointment-time">
                                    <i class="material-icons-sharp">today</i>
                                    Today, 08:30 AM
                                </div>
                                <span class="appointment-status status-completed">Completed</span>
                            </div>
                            <div class="appointment-patient">
                                <div class="patient-avatar">
                                    <img src="<?= asset('img/avatars/joe.jpg'); ?>" alt="Pet">
                                </div>
                                <div class="patient-info">
                                    <h4>Ruby (Maine Coon)</h4>
                                    <p>Check-up</p>
                                </div>
                            </div>
                        </div>

                        <div class="appointment-card">
                            <div class="appointment-header">
                                <div class="appointment-time">
                                    <i class="material-icons-sharp">event</i>
                                    Yesterday, 03:45 PM
                                </div>
                                <span class="appointment-status status-cancelled">Cancelled</span>
                            </div>
                            <div class="appointment-patient">
                                <div class="patient-avatar">
                                    <img src="<?= asset('img/avatars/justen.jpg'); ?>" alt="Pet">
                                </div>
                                <div class="patient-info">
                                    <h4>Cooper (Bulldog)</h4>
                                    <p>Surgery</p>
                                </div>
                            </div>
                        </div>

                        <div class="appointment-card">
                            <div class="appointment-header">
                                <div class="appointment-time">
                                    <i class="material-icons-sharp">event</i>
                                    Yesterday, 11:30 AM
                                </div>
                                <span class="appointment-status status-completed">Completed</span>
                            </div>
                            <div class="appointment-patient">
                                <div class="patient-avatar">
                                    <img src="<?= asset('img/avatars/nan.jpg'); ?>" alt="Pet">
                                </div>
                                <div class="patient-info">
                                    <h4>Daisy (Siamese Cat)</h4>
                                    <p>Grooming</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts') ?>
</body>

</html>