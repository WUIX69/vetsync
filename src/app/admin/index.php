<?php include_once '../../core/app.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta') ?>
    <title>Admin Dashboard</title>
    <?= shared('elements/styles') ?>
</head>

<body>

    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window') ?> <!-- Window Spinner -->
        <?= shared('components/reminder-modal') ?> <!-- Reminder Modal -->
        <?= shared('components/flyout') ?> <!-- Flyout -->
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
                    <!-- Stats Cards -->
                    <?= featured('admin/dashboard/components/stats') ?>

                    <!-- New Users -->
                    <?= featured('admin/dashboard/components/new-users') ?>

                    <!-- Recent Orders -->
                    <?= featured('admin/dashboard/components/recent-orders') ?>
                </div>

                <!-- Right Section -->
                <div class="col-lg-3">
                    <!-- System Info -->
                    <?= featured('admin/dashboard/components/sys-info') ?>

                    <!-- Reminders -->
                    <?= featured('admin/dashboard/components/reminders') ?>
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