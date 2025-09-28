<?php include_once __DIR__ . '/../../core/app.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta') ?>
    <title>Admin Dashboard - VetSync</title>
    <?= shared('elements/styles') ?>
</head>

<body>

    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window') ?> <!-- Window Spinner -->
        <?= featured('dashboard/components/reminder-modal') ?> <!-- Reminder Modal -->
        <?= shared('components/flyout') ?> <!-- Flyout -->
    </div>

    <div class="container-body pusher">
        <!-- Sidebar -->
        <?= partial('layouts/sidebar') ?>
        <!-- Navbar -->
        <?= partial('layouts/navbar') ?>

        <!-- Main Content -->
        <main class="container-main">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Stats Cards -->
                    <?= featured('dashboard/components/stats-card-admin') ?>
                </div>
                <div class="col-lg-6">
                    <!-- Recent Appointments -->
                    <?= featured('dashboard/components/recent-appointments-admin') ?>
                </div>
                <div class="col-lg-6">
                    <!-- Recent Users -->
                    <?= featured('dashboard/components/new-users') ?>
                </div>
                <div class="col-lg-8">
                    <!-- Recent Reservations -->
                    <?= featured('dashboard/components/recent-reservations-admin') ?>
                </div>
                <div class="col-lg-4">
                    <!-- Quick Actions -->
                    <?= featured('dashboard/components/quick-actions-admin') ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts') ?>
    <script src="<?= featured('dashboard/js/adminDashboard.js', true) ?>"></script>
</body>

</html>