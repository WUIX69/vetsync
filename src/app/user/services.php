<?php include_once __DIR__ . '/../../core/app.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta'); ?> <!-- rcs Meta -->
    <title>Services - VetSync</title>
    <?= shared('elements/styles'); ?> <!-- rcs Styles -->
    <!-- Add Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div class="shared-standalone-content">


        <?= shared('layouts/loader/window'); ?> <!-- Window Spinner -->
        <?= shared('layouts/top-redirect-btn'); ?> <!-- Top Redirect Button -->
    </div>

    <div class="site-cont">
        <!-- Site Header -->
        <?= partial('layouts/header'); ?>

        <main class="site-main">
            <!-- Header -->
            <?= featured('services/components/header'); ?>

            <!-- Services -->
            <?= featured('services/components/services'); ?>
        </main>
    </div>

    <?= shared('elements/scripts'); ?> <!-- rcs Scripts -->

    <script src="/src/features/services/js/booknow.js"></script>
    <script src="/src/features/services/js/user-services-list.js"></script>

</body>

</html>