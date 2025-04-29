<?php include_once '../../../src/utils/php/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta') ?>
    <title>Admin Dashboard</title>
    <?= shared('elements/styles') ?>
    <?= featured('admin/dashboard/styles') ?>
</head>

<body>

    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window') ?> <!-- Window Spinner -->
        <?= shared('components/reminder-modal') ?> <!-- Reminder Modal -->
        <?= shared('components/flyout') ?> <!-- Flyout -->
    </div>

    <div class="container-body pusher">
        <div class="row">

            <!-- Sidebar -->
            <div class="col-auto">
                <?= featured('admin/shared/layouts/sidebar') ?> <!-- Sidebar -->
            </div>

            <!-- Main Content -->
            <div class="col">
                <main class="container-main">
                    <h1>Dashboard</h1>

                    <!-- Stats Cards -->
                    <?= featured('admin/dashboard/components/stats-cards') ?>

                    <!-- New Users -->
                    <?= featured('admin/dashboard/components/new-users') ?>

                    <!-- Recent Orders -->
                    <?= featured('admin/dashboard/components/recent-orders') ?>
                </main>
            </div>

            <!-- Right Section -->
            <div class="col-lg-3">
                <?= featured('admin/shared/components/right-section') ?>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts') ?>
    <?= featured('admin/dashboard/scripts') ?>
</body>

</html>