<?php include_once __DIR__ . '/../../core/app.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta') ?>
    <title>Services Management - Admin</title>
    <?= shared('elements/styles') ?>

</head>

<body>

    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window') ?> <!-- Window Spinner -->

        <!-- Service Form Modal -->
        <?= featured('services/components/service-form-modal') ?>
    </div>

    <div class="container-body pusher">
        <!-- Sidebar -->
        <?= partial('layouts/sidebar') ?> <!-- Sidebar -->
        <!-- Navbar -->
        <?= partial('layouts/navbar') ?> <!-- Navbar -->

        <!-- Main Content -->
        <main class="container-main">

            <div class="row">
                <div class="col-lg-9">
                    <!-- Services Stats -->
                    <?= featured('services/components/services-stats') ?>

                    <!-- Services List -->
                    <?= featured('services/components/services-list') ?>
                </div>

                <!-- Right Section -->
                <div class="col-lg-3">
                    <!-- Service Activity -->
                    <?= featured('services/components/service-activity') ?>

                    <!-- Most Booked Services -->
                    <?= featured('services/components/most-booked-services') ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts') ?>
    <script src="<?= featured('services/js/main-admin.js', true) ?>"></script>

    </script>
</body>

</html>