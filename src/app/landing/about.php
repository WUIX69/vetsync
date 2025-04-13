<?php include_once __DIR__ . '/../../utils/php/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta'); ?> <!-- rcs Meta -->
    <title>Landing Page</title>
    <?= shared('elements/styles'); ?> <!-- rcs Styles -->
    <?= featured('landing/about/styles') ?>
</head>

<body>

    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window'); ?> <!-- Window Spinner -->
        <?= shared('layouts/top-redirect-btn'); ?> <!-- Top Redirect Button -->
        <?= shared('components/booknow-modal'); ?> <!-- Book Now Modal -->
        <?= featured('landing/shared/layouts/header'); ?> <!-- Header -->
    </div>

    <div class="container-body">
        <div class="row">
            <div class="col-12">
                <main class="container-main">
                    <!-- Hero -->
                    <?= featured('landing/shared/components/hero'); ?>

                    <!-- Teams -->
                    <?= featured('landing/about/components/team'); ?>

                    <!-- Services -->
                    <?= featured('landing/about/components/services-overview'); ?>

                    <!-- Clinic history -->
                    <?= featured('landing/about/components/clinic-history'); ?>

                    <!-- Testimonials -->
                    <?= featured('landing/shared/components/testimonials'); ?>

                    <!-- Reserve -->
                    <?= featured('landing/shared/components/reserve'); ?>
                </main>
            </div>
            <div class="col-12">
                <?= featured('landing/shared/layouts/footer'); ?><!-- Footer -->
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts'); ?>
    <?= featured('landing/contact/scripts'); ?>
</body>

</html>