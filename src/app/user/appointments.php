<?php include_once __DIR__ . '/../../core/app.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta'); ?> <!-- rcs Meta -->
    <title>My Appointments - VetSync</title>
    <?= shared('elements/styles'); ?> <!-- rcs Styles -->
    <style>
        /*----------- MAIN (Appointments) -----------*/
        main section.appointments {
            background: var(--color-background);
            padding-top: 1rem;
            padding-bottom: 3rem;
        }

        /* Header */
        main section.appointments .header {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            align-items: center;
            margin-bottom: 2.3rem;
            flex-wrap: nowrap;
        }

        /* Header - Filter Bar */
        main section.appointments .header .filter-bar {
            display: flex;
            justify-content: start;
            align-items: center;
            padding: 0;
            margin: 0;
            flex: 1;
        }

        main section.appointments .header .filter-bar button {
            padding: 0.8rem 1.5rem;
            margin: 0 0.3rem;
            border-radius: 0.3rem;
            background-color: var(--color-white);
            color: var(--color-dark);
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        main section.appointments .header .filter-bar button:hover {
            transform: translateY(-3px);
            box-shadow: var(--box-shadow);
        }

        main section.appointments .header .filter-bar button.active {
            background-color: var(--color-dark);
            color: var(--color-white);
        }

        main section .card {
            position: relative;
            overflow: hidden;
            border-radius: 0.8rem;
            border: 1px solid var(--bs-card-border-color) !important;
        }

        main section.appointments .appointment-grid-list {
            position: relative;
            padding: 0 !important;
        }

        /* Appointment Card */
        main section.appointments .appointment-listing {
            position: relative;
            border-bottom: 1px solid var(--bs-card-border-color) !important;
            padding: 1.5rem;
            background: var(--color-white);
            transition: transform 0.3s ease, box-shadow 0.3s ease;

            display: flex;
            justify-content: center;
            align-items: start;
            flex-direction: column;
            gap: 1.6rem;
        }

        main section.appointments .appointment-listing .appointment-header {
            display: flex;
            justify-content: start;
            gap: 1rem;
            align-items: center;
        }

        main section.appointments .appointment-listing .appointment-header h3 {
            font-size: 1.6rem;
            font-weight: bold;
        }

        main section.appointments .appointment-listing .appointment-status {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .status-upcoming {
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

        main section.appointments .appointment-listing .appointment-description {
            max-width: 75%;
        }

        main section.appointments .appointment-listing .appointment-details {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: row;
            flex-wrap: nowrap;
            gap: 1rem;
        }

        main section.appointments .appointment-listing .detail-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        main section.appointments .appointment-listing .detail-item i {
            color: var(--color-primary);
            font-size: 1.4rem;
        }

        main section.appointments .appointment-listing .appointment-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1rem;
        }

        /* Section Header Styles */
        main section.appointments .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        main section.appointments .section-title .sub-title {
            display: inline-block;
            color: var(--color-primary);
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        main section.appointments .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--color-dark);
            margin-bottom: 1rem;
        }

        main section.appointments .section-title p {
            color: var(--color-text);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Appointment Nav Start */
        main section.appointments .appointments-nav {
            background: var(--color-white);
            display: flex !important;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin: 0;
        }

        main section.appointments .appointments-nav .nav-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.7rem;

            padding: 1rem 1.5rem;
            border-radius: 1rem;
            transition: all 0.3s ease;
            text-align: center;
            cursor: pointer;
            width: 100%;
            color: var(--color-dark);
        }

        main section.appointments .appointments-nav .nav-link:hover {
            background-color: rgba(0, 0, 0, .03);
        }

        main section.appointments .appointments-nav .nav-link.active {
            background-color: var(--color-dark-variant);
            color: var(--color-white);
            box-shadow: 0 4px 15px rgba(33, 186, 69, 0.2);
        }

        main section.appointments .appointments-nav .nav-link i {
            font-size: 1.4rem;
        }

        /* Appointment Nav END */
        .cancellation-info {
            margin: 0.5rem 0;
        }

        .cancellation-info .alert {
            padding: 0.75rem;
            margin-bottom: 0;
            border-radius: 0.375rem;
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }

        /* Reason Info Styling */
        /* Simple Yellow Banner Design */
        .reason-info {
            margin: 0.75rem 0;
        }

        .reason-info .alert {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            margin-bottom: 0;
            font-size: 0.85rem;
            color: #856404;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .reason-info .alert i {
            color: #f39c12;
            font-size: 1rem;
        }

        /* Remove different colors - all use same yellow design */
        .admin-reason .alert,
        .user-reason .alert,
        .reschedule-reason .alert {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }
    </style>
</head>

<body>
    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window'); ?> <!-- Window Spinner -->
        <?= shared('layouts/top-redirect-btn'); ?> <!-- Top Redirect Button -->
    </div>

    <div class="site-cont">
        <!-- Site Header -->
        <?= partial('layouts/header'); ?>

        <main class="site-main">
            <!-- Header -->
            <section class="header py-5">
                <div class="container-xl">
                    <h1>My Appointments <span class="emoji">📅</span></h1>
                    <p>View and manage your veterinary appointments.</p>
                </div>
            </section>

            <!-- Appointments Section -->
            <section class="appointments">
                <div class="container-xl">
                    <!-- <div class="header">
                        <div class="filter-bar">
                            <button class="ui mini button active">All Appointments</button>
                            <button class="ui mini button">Upcoming</button>
                            <button class="ui mini button">Completed</button>
                            <button class="ui mini button">Cancelled</button>
                        </div>

                        <div class="new-appointment">
                            <button class="ui teal button">
                                <i class="plus icon"></i>
                                New Appointment
                            </button>
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card appointments-nav">
                                <div class="card-body nav flex-column nav-pills">
                                    <a href="#" class="nav-link active" data-filter="all">
                                        <i class="list icon"></i>
                                        <span>All</span>
                                    </a>
                                    <a href="#" class="nav-link" data-filter="upcoming">
                                        <i class="clock outline icon"></i>
                                        <span>Upcoming</span>
                                    </a>
                                    <a href="#" class="nav-link" data-filter="completed">
                                        <i class="check circle icon"></i>
                                        <span>Completed</span>
                                    </a>
                                    <a href="#" class="nav-link" data-filter="cancelled">
                                        <i class="times circle icon"></i>
                                        <span>Cancelled</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Appointments List -->
                        <div class="col-md-9">
                            <div class="card appointment-grid-list">
                                <div class="tab-content card-body">
                                    <!-- Dynamic Appointments Container -->
                                    <div class="tab-pane fade show active" id="all">
                                        <div class="appointments-list">
                                            <!-- JavaScript will populate appointments here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <?= shared('elements/scripts'); ?> <!-- rcs Scripts -->
    <script src="/src/features/appointments/js/user-appointments.js"></script>
</body>

</html>