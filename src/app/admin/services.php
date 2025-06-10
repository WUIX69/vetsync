<?php include_once __DIR__ . '/../../core/app.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta') ?>
    <title>Services Management - Admin</title>
    <?= shared('elements/styles') ?>
    <?= shared('elements/filepond/styles') ?>
</head>

<body>

    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window') ?> <!-- Window Spinner -->
        <?= featured('services/components/service-modal') ?><!-- Service Modal -->
        <?= featured('services/components/category-modal') ?><!-- Service Category Modal -->
    </div>

    <div class="container-body pusher">
        <?= partial('layouts/sidebar') ?> <!-- Sidebar -->
        <?= partial('layouts/navbar') ?> <!-- Navbar -->

        <!-- Main Content -->
        <main class="container-main">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Services Stats -->
                    <?= featured('services/components/stats') ?>
                </div>
                <div class="col-lg-9">
                    <!-- Categories Table -->
                    <?= featured('services/components/categories-table') ?>
                </div>
                <div class="col-lg-3">
                    <!-- Most Booked Services-->
                    <?= featured('services/components/most-booked') ?>
                </div>
                <div class="col-lg-12">
                    <!-- Services Table -->
                    <?= featured('services/components/services-table') ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts') ?>
    <?= shared('elements/filepond/scripts') ?>
    <script src="<?= featured('services/js/serviceCategoriesTable.js', true) ?>"></script>
    <script src="<?= featured('services/js/servicesTable.js', true) ?>"></script>
</body>

</html>