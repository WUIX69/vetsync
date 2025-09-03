<?php include_once __DIR__ . '/../../core/app.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta') ?>
    <title>Product Reservations - VetSync</title>
    <?= shared('elements/styles') ?>
</head>

<body>
    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window') ?>
        <?= shared('components/flyout') ?>
    </div>

    <div class="container-body pusher">
        <!-- Sidebar -->
        <?= partial('layouts/sidebar') ?>
        <!-- Navbar -->
        <?= partial('layouts/navbar') ?>

        <!-- Main Content -->
        <main class="container-main">
            <?= featured('reservations/components/reservations-management') ?>
        </main>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts') ?>
    <script src="/src/features/reservations/js/reservations.js"></script>
</body>

</html>