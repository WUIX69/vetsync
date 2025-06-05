<?php include_once __DIR__ . '/../../core/app.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta') ?>
    <title>Admin Appointments Dashboard</title>
    <?= shared('elements/styles') ?>
    <style>
        /* Admin Appointments Dashboard Styles */
        /* Calendar View Section */
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
            border: 1px solid var(--color-light);
            transition: all 0.2s ease;
        }

        main section.calendar-view .calendar-day:hover {
            background-color: var(--color-light);
        }

        main section.calendar-view .calendar-day.has-appointments {
            border-color: var(--color-primary-light);
        }

        main section.calendar-view .calendar-day.active {
            background-color: var(--color-primary-light);
            color: var(--color-primary);
            font-weight: 500;
        }

        main section.calendar-view .calendar-day-number {
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 0.3rem;
        }

        main section.calendar-view .appointment-count {
            font-size: 0.7rem;
            background-color: var(--color-primary);
            color: white;
            border-radius: 1rem;
            padding: 0.1rem 0.4rem;
        }

        /* Recent Appointments Section */
        main section.recent-appointments .appointment-card {
            background-color: var(--color-white);
            border-radius: 0.6rem;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--color-primary);
            box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.03);
            transition: transform 0.2s ease;
        }

        main section.recent-appointments .appointment-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.3rem 0.8rem rgba(0, 0, 0, 0.08);
        }

        main section.recent-appointments .appointment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        main section.recent-appointments .appointment-time {
            font-size: 0.8rem;
            color: var(--color-dark-variant);
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        main section.recent-appointments .appointment-status {
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 1rem;
            font-weight: 500;
        }

        main section.recent-appointments .status-confirmed {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        main section.recent-appointments .status-completed {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        main section.recent-appointments .status-cancelled {
            background-color: #ffebee;
            color: #c62828;
        }

        main section.recent-appointments .status-pending {
            background-color: #fff8e1;
            color: #f57c00;
        }

        main section.recent-appointments .appointment-patient {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        main section.recent-appointments .patient-avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background-color: var(--color-light);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        main section.recent-appointments .patient-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        main section.recent-appointments .patient-info h4 {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.2rem;
        }

        main section.recent-appointments .patient-info p {
            font-size: 0.8rem;
            color: var(--color-dark-variant);
        }

        main section.recent-appointments .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--color-dark);
        }
    </style>
</head>

<body>

    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window') ?> <!-- Window Spinner -->
    </div>

    <div class="container-body pusher">
        <?= partial('layouts/sidebar') ?> <!-- Sidebar -->
        <?= partial('layouts/navbar') ?> <!-- Navbar -->

        <!-- Main Content -->
        <main class="container-main">

            <div class="row">
                <div class="col-lg-9">
                    <!-- Stats overview -->
                    <?= featured('appointments/components/stats') ?>

                    <!-- Calendar View -->
                    <section class="calendar-view box">
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
                    </section>
                </div>

                <div class="col-lg-3">
                    <!-- Recent Appointments Widget -->
                    <section class="recent-appointments">
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
                    </section>
                </div>

                <div class="col-lg-12">
                    <?= featured('appointments/components/appointments') ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts') ?>
</body>

</html>