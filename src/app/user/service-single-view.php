<?php include_once __DIR__ . '/../../core/app.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta'); ?> <!-- rcs Meta -->
    <title>Products (View) - VetSync</title>
    <?= shared('elements/styles'); ?> <!-- rcs Styles -->
</head>

<body>

    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window'); ?> <!-- Window Spinner -->
        <?= shared('layouts/top-redirect-btn'); ?> <!-- Top Redirect Button -->
        <?= featured('services/components/booknow-modal'); ?> <!-- Book Now Modal -->
    </div>

    <div class="site-cont">
        <!-- Site Header -->
        <?= partial('layouts/header'); ?>

        <main class="site-main">
            <!-- Header -->
            <?= featured('services/components/header-single-view'); ?>

            <!-- Highlights -->
            <?= featured('services/components/highlights'); ?>

            <!-- About -->
            <?= featured('services/components/about'); ?>

            <!-- Related Services -->
            <?= featured('services/components/related'); ?>
            <!-- Reviews -->
            <?= featured('services/components/reviews'); ?>
        </main>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts'); ?> <!-- rcs Scripts -->


    <?= featured('services/components/booknow-modal'); ?> <!-- Book Now Modal -->
</body>

</html>