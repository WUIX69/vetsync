<?php include_once __DIR__ . '/../../utils/php/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta'); ?> <!-- rcs Meta -->
    <title>Landing - VetSync</title>
    <?= shared('elements/styles'); ?> <!-- rcs Styles -->
    <?= featured('landing/home/styles') ?>
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
                    <?= featured('landing/home/components/section/hero'); ?>

                    <!-- Features -->
                    <?= featured('landing/home/components/section/features'); ?>

                    <!-- Services -->
                    <?= featured('landing/home/components/section/services'); ?>

                    <!-- Testimonials -->
                    <?= featured('landing/shared/components/section/testimonials'); ?>

                    <!-- Separator -->
                    <?= featured('landing/shared/components/section/separator'); ?>

                    <!-- Locations -->
                    <?= featured('landing/home/components/section/location'); ?>

                    <!-- Reserve -->
                    <?= featured('landing/shared/components/section/reserve'); ?>
                </main>
            </div>
            <div class="col-12">
                <?= featured('landing/shared/layouts/footer'); ?><!-- Footer -->
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts'); ?> <!-- rcs Scripts -->
    <?= featured('landing/home/scripts'); ?> <!-- Home Scripts -->
</body>

</html>