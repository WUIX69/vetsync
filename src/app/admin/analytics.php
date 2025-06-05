<?php include_once __DIR__ . '/../../core/app.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta') ?>
    <title>Analytics Dashboard</title>
    <?= shared('elements/styles') ?>
</head>

<body>

    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window') ?> <!-- Window Spinner -->
    </div>

    <div class="container-body pusher">
        <!-- Sidebar -->
        <?= partial('layouts/sidebar') ?> <!-- Sidebar -->
        <?= partial('layouts/navbar') ?> <!-- Navbar -->

        <!-- Main Content -->
        <main class="container-main">


            <?= featured('analytics/components/stats') ?>
            <div class="row">
                <div class="col-md-8">
                    <?= featured('analytics/components/revenue-trends') ?>
                </div>
                <div class="col-md-4">
                    <?= featured('analytics/components/traffic-sources') ?>
                </div>
                <div class="col-md-7">
                    <?= featured('analytics/components/user-activity') ?>
                </div>
                <div class="col-md-5">
                    <?= featured('analytics/components/quick-stats') ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts') ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="<?= featured('analytics/js/revenueChart.js', true) ?>"></script>
    <script src="<?= featured('analytics/js/trafficSourceChart.js', true) ?>"></script>
    <script src="<?= featured('analytics/js/userActivityChart.js', true) ?>"></script>
</body>

</html>