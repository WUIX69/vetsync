<?php include_once __DIR__ . '/../../core/app.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta') ?>
    <title>Analytics Management - VetSync</title>
    <?= shared('elements/styles') ?>
</head>

<body>

    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window') ?> <!-- Window Spinner -->
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
                    <h2 class="mb-4">Analytics Management</h2>
                    <!-- Analytics Dashboard -->
                    <?= featured('analytics/components/analytics-dashboard') ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts') ?>
</body>

</html>