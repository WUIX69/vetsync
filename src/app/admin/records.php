<?php include_once __DIR__ . '/../../core/app.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta') ?>
    <title>Admin Dashboard</title>
    <?= shared('elements/styles') ?>
    <?= featured('admin/dashboard/styles') ?>
    <style>
        /* Pet Records Styles */
        .pet-records-container {
            background-color: var(--color-white);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .pet-records-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding-bottom: 1rem;
        }

        .pet-records-header h2 {
            font-size: 1.25rem;
            color: var(--color-dark);
            margin: 0;
            font-weight: 600;
        }

        .add-record-btn {
            background-color: #6c9bcf;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .add-record-btn:hover {
            background-color: #5a89bb;
            transform: translateY(-2px);
        }

        .records-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .record-item {
            background-color: #f9fafc;
            border-radius: 8px;
            padding: 1rem;
            border-left: 4px solid #6c9bcf;
            position: relative;
            transition: all 0.2s ease;
        }

        .record-item:hover {
            transform: translateX(5px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        .record-item.completed {
            border-left-color: #2ecc71;
        }

        .record-item.scheduled {
            border-left-color: #f39c12;
        }

        .record-item.overdue {
            border-left-color: #e74c3c;
        }

        .record-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .record-type {
            font-weight: 600;
            color: var(--color-dark);
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .record-type i {
            font-size: 1.2rem;
            color: #6c9bcf;
        }

        .record-status {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
        }

        .record-status.status-completed {
            background-color: #e6f7ed;
            color: #2ecc71;
        }

        .record-status.status-scheduled {
            background-color: #fef5e7;
            color: #f39c12;
        }

        .record-status.status-overdue {
            background-color: #fdeaea;
            color: #e74c3c;
        }

        .record-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
            margin-top: 0.75rem;
        }

        .record-detail {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: var(--color-dark-variant);
        }

        .record-detail i {
            color: #6c9bcf;
            font-size: 1rem;
        }

        .record-notes {
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px dashed rgba(0, 0, 0, 0.1);
            font-size: 0.85rem;
            color: var(--color-dark-variant);
            line-height: 1.5;
        }

        .record-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .record-action-btn {
            background-color: transparent;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            padding: 0.4rem 0.75rem;
            font-size: 0.75rem;
            color: var(--color-dark-variant);
            display: flex;
            align-items: center;
            gap: 0.3rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .record-action-btn:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }

        /* Filter Controls */
        .records-filter {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .filter-control {
            flex: 1;
        }

        .filter-control select {
            width: 100%;
            padding: 0.6rem;
            border-radius: 5px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            font-size: 0.85rem;
            color: var(--color-dark-variant);
        }

        /* Right sidebar styles */
        .record-summary {
            background-color: var(--color-white);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .summary-title {
            font-size: 1.1rem;
            color: var(--color-dark);
            margin-bottom: 1rem;
            font-weight: 600;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding-bottom: 0.75rem;
        }

        .summary-stat {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            padding: 0.75rem;
            border-radius: 8px;
            background-color: #f9fafc;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
        }

        .stat-icon.icon-blue {
            background-color: #6c9bcf;
        }

        .stat-icon.icon-green {
            background-color: #2ecc71;
        }

        .stat-icon.icon-orange {
            background-color: #f39c12;
        }

        .stat-info {
            flex: 1;
        }

        .stat-value {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--color-dark);
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--color-dark-variant);
        }

        /* Modern flat design styles */
        main {
            padding: 1rem;
            background-color: #f6f8fb;
        }

        main section {
            margin-top: 1rem;
        }

        main section .record-list-container {
            background-color: var(--color-white);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
            padding: 1.5rem;
        }

        main section .record-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        main section .record-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--color-dark);
        }

        main section .add-button {
            background-color: var(--color-primary);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        main section .filter-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        main section .filter-select {
            flex: 1;
            padding: 0.6rem;
            border-radius: 5px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            font-size: 0.85rem;
        }

        main section .record-entry {
            background-color: var(--color-white);
            border-radius: 8px;
            padding: 1.2rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--color-primary);
        }

        main section .record-entry.completed {
            border-left-color: #2ecc71;
        }

        main section .record-entry.scheduled {
            border-left-color: #f39c12;
        }

        main section .record-entry.overdue {
            border-left-color: #e74c3c;
        }

        main section .entry-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        main section .entry-type {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: var(--color-dark);
        }

        main section .entry-status {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
        }

        main section .entry-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        main section .detail-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: var(--color-dark-variant);
        }

        main section .entry-notes {
            font-size: 0.85rem;
            line-height: 1.5;
            color: var(--color-dark-variant);
            margin-bottom: 1rem;
            padding-top: 0.75rem;
            border-top: 1px dashed rgba(0, 0, 0, 0.1);
        }

        main section .entry-actions {
            display: flex;
            gap: 0.5rem;
        }

        main section .action-button {
            background-color: transparent;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            padding: 0.4rem 0.75rem;
            font-size: 0.75rem;
            color: var(--color-dark-variant);
            display: flex;
            align-items: center;
            gap: 0.3rem;
            cursor: pointer;
        }

        main section .sidebar-widget {
            background-color: var(--color-white);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        main section .widget-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--color-dark);
        }

        main section .stat-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            padding: 0.75rem;
            border-radius: 8px;
            background-color: #f9fafc;
        }

        main section .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
        }

        main section .update-list {
            margin-top: 1rem;
        }

        main section .update-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        main section .update-icon {
            font-size: 1.2rem;
        }

        main section .update-content {
            flex: 1;
        }

        main section .update-title {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.2rem;
        }

        main section .update-time {
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
            <!-- Header -->
            <?= featured('admin/dashboard/components/header') ?>

            <div class="row">
                <div class="col-lg-9">
                    <!-- Records List -->
                    <div class="pet-records-container">
                        <div class="pet-records-header">
                            <h2>Pet Medical Records</h2>
                            <button class="add-record-btn">
                                <i class="plus icon"></i>
                                Add New Record
                            </button>
                        </div>

                        <div class="records-filter">
                            <div class="filter-control">
                                <select>
                                    <option value="">All Pets</option>
                                    <option>Max (Golden Retriever)</option>
                                    <option>Luna (Persian Cat)</option>
                                    <option>Charlie (Beagle)</option>
                                    <option>Ruby (Maine Coon)</option>
                                </select>
                            </div>
                            <div class="filter-control">
                                <select>
                                    <option value="">All Record Types</option>
                                    <option>Vaccination</option>
                                    <option>Check-up</option>
                                    <option>Treatment</option>
                                    <option>Surgery</option>
                                </select>
                            </div>
                            <div class="filter-control">
                                <select>
                                    <option value="">All Statuses</option>
                                    <option>Completed</option>
                                    <option>Scheduled</option>
                                    <option>Overdue</option>
                                </select>
                            </div>
                        </div>

                        <div class="records-list">
                            <!-- Completed Vaccination Record -->
                            <div class="record-item completed">
                                <div class="record-header">
                                    <div class="record-type">
                                        <i class="syringe icon"></i>
                                        Vaccination
                                    </div>
                                    <span class="record-status status-completed">Completed</span>
                                </div>
                                <div class="record-details">
                                    <div class="record-detail">
                                        <i class="pet icon"></i>
                                        <span>Charlie (Beagle)</span>
                                    </div>
                                    <div class="record-detail">
                                        <i class="calendar icon"></i>
                                        <span>May 7, 2024</span>
                                    </div>
                                    <div class="record-detail">
                                        <i class="user md icon"></i>
                                        <span>Dr. Sarah Johnson</span>
                                    </div>
                                    <div class="record-detail">
                                        <i class="tag icon"></i>
                                        <span>Rabies Vaccine</span>
                                    </div>
                                </div>
                                <div class="record-notes">
                                    <p>Annual rabies vaccination completed. Next vaccination due in May 2025. Patient
                                        showed no adverse reactions.</p>
                                </div>
                                <div class="record-actions">
                                    <button class="record-action-btn">
                                        <i class="edit outline icon"></i>
                                        Edit
                                    </button>
                                    <button class="record-action-btn">
                                        <i class="print icon"></i>
                                        Print
                                    </button>
                                </div>
                            </div>

                            <!-- Upcoming Vaccination -->
                            <div class="record-item scheduled">
                                <div class="record-header">
                                    <div class="record-type">
                                        <i class="syringe icon"></i>
                                        Vaccination
                                    </div>
                                    <span class="record-status status-scheduled">Scheduled</span>
                                </div>
                                <div class="record-details">
                                    <div class="record-detail">
                                        <i class="pet icon"></i>
                                        <span>Max (Golden Retriever)</span>
                                    </div>
                                    <div class="record-detail">
                                        <i class="calendar icon"></i>
                                        <span>June 15, 2024</span>
                                    </div>
                                    <div class="record-detail">
                                        <i class="user md icon"></i>
                                        <span>Dr. Michael Chen</span>
                                    </div>
                                    <div class="record-detail">
                                        <i class="tag icon"></i>
                                        <span>DHPP Booster</span>
                                    </div>
                                </div>
                                <div class="record-notes">
                                    <p>Scheduled for DHPP booster vaccination. Patient is due for annual booster to
                                        maintain immunity.</p>
                                </div>
                                <div class="record-actions">
                                    <button class="record-action-btn">
                                        <i class="edit outline icon"></i>
                                        Edit
                                    </button>
                                    <button class="record-action-btn">
                                        <i class="calendar alternate outline icon"></i>
                                        Reschedule
                                    </button>
                                </div>
                            </div>

                            <!-- Overdue Vaccination -->
                            <div class="record-item overdue">
                                <div class="record-header">
                                    <div class="record-type">
                                        <i class="syringe icon"></i>
                                        Vaccination
                                    </div>
                                    <span class="record-status status-overdue">Overdue</span>
                                </div>
                                <div class="record-details">
                                    <div class="record-detail">
                                        <i class="pet icon"></i>
                                        <span>Luna (Persian Cat)</span>
                                    </div>
                                    <div class="record-detail">
                                        <i class="calendar icon"></i>
                                        <span>April 20, 2024</span>
                                    </div>
                                    <div class="record-detail">
                                        <i class="user md icon"></i>
                                        <span>Dr. Emily Wilson</span>
                                    </div>
                                    <div class="record-detail">
                                        <i class="tag icon"></i>
                                        <span>FVRCP Vaccine</span>
                                    </div>
                                </div>
                                <div class="record-notes">
                                    <p>Annual FVRCP vaccination overdue. Please contact pet owner to reschedule as soon
                                        as possible.</p>
                                </div>
                                <div class="record-actions">
                                    <button class="record-action-btn">
                                        <i class="phone icon"></i>
                                        Contact Owner
                                    </button>
                                    <button class="record-action-btn">
                                        <i class="calendar alternate outline icon"></i>
                                        Schedule
                                    </button>
                                </div>
                            </div>

                            <!-- Completed Health Check -->
                            <div class="record-item completed">
                                <div class="record-header">
                                    <div class="record-type">
                                        <i class="stethoscope icon"></i>
                                        Check-up
                                    </div>
                                    <span class="record-status status-completed">Completed</span>
                                </div>
                                <div class="record-details">
                                    <div class="record-detail">
                                        <i class="pet icon"></i>
                                        <span>Ruby (Maine Coon)</span>
                                    </div>
                                    <div class="record-detail">
                                        <i class="calendar icon"></i>
                                        <span>May 2, 2024</span>
                                    </div>
                                    <div class="record-detail">
                                        <i class="user md icon"></i>
                                        <span>Dr. Sarah Johnson</span>
                                    </div>
                                    <div class="record-detail">
                                        <i class="tag icon"></i>
                                        <span>Annual Wellness</span>
                                    </div>
                                </div>
                                <div class="record-notes">
                                    <p>Annual wellness exam completed. All vitals normal. Dental cleaning recommended
                                        for next visit.</p>
                                </div>
                                <div class="record-actions">
                                    <button class="record-action-btn">
                                        <i class="edit outline icon"></i>
                                        Edit
                                    </button>
                                    <button class="record-action-btn">
                                        <i class="print icon"></i>
                                        Print
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Section -->
                <div class="col-lg-3">
                    <!-- Records Summary -->
                    <div class="record-summary">
                        <h3 class="summary-title">Records Summary</h3>

                        <div class="summary-stat">
                            <div class="stat-icon icon-blue">
                                <i class="clipboard list icon"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value">42</div>
                                <div class="stat-label">Total Records</div>
                            </div>
                        </div>

                        <div class="summary-stat">
                            <div class="stat-icon icon-green">
                                <i class="check circle icon"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value">28</div>
                                <div class="stat-label">Completed</div>
                            </div>
                        </div>

                        <div class="summary-stat">
                            <div class="stat-icon icon-orange">
                                <i class="clock icon"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value">14</div>
                                <div class="stat-label">Pending/Scheduled</div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Updates -->
                    <div class="record-summary">
                        <h3 class="summary-title">Recent Updates</h3>

                        <div class="ui relaxed list">
                            <div class="item">
                                <i class="check circle icon green"></i>
                                <div class="content">
                                    <div class="header">Charlie's vaccination completed</div>
                                    <div class="description">Today at 10:30 AM</div>
                                </div>
                            </div>
                            <div class="item">
                                <i class="plus circle icon blue"></i>
                                <div class="content">
                                    <div class="header">New appointment scheduled</div>
                                    <div class="description">Yesterday at 3:15 PM</div>
                                </div>
                            </div>
                            <div class="item">
                                <i class="exclamation circle icon red"></i>
                                <div class="content">
                                    <div class="header">Luna's vaccination overdue</div>
                                    <div class="description">May 1, 2024</div>
                                </div>
                            </div>
                            <div class="item">
                                <i class="edit icon teal"></i>
                                <div class="content">
                                    <div class="header">Max's record updated</div>
                                    <div class="description">April 28, 2024</div>
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
    <script src="<?= featured('admin/dashboard/js/main.js', true) ?>"></script>
    <script src="<?= featured('admin/dashboard/js/recentOrders.js', true) ?>"></script>
</body>

</html>