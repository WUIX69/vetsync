<?php include_once __DIR__ . '/../../core/app.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta') ?>
    <title>Admin Appointments Dashboard</title>
    <?= shared('elements/styles') ?>
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
                    <?= featured('appointments/components/calendar') ?>
                </div>
                <div class="col-lg-3">
                    <!-- Recent Appointments Widget -->
                    <?= featured('appointments/components/appointments-recent') ?>
                </div>
                <div class="col-lg-12">
                    <!-- Appointments List -->
                    <?= featured('appointments/components/appointments') ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts') ?>
</body>

</html>