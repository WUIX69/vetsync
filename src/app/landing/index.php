<?php include_once __DIR__ . '/../../core/app.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta'); ?> <!-- rcs Meta -->
    <title>Landing - VetSync</title>
    <?= shared('elements/styles'); ?> <!-- rcs Styles -->
    <link rel="stylesheet" href="<?= asset('lib/swiper/swiper-bundle.min.css'); ?>" /><!-- Link Swiper's CSS -->
</head>

<body>

    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window'); ?> <!-- Window Spinner -->
        <?= shared('layouts/top-redirect-btn'); ?> <!-- Top Redirect Button -->
        <?= shared('components/booknow-modal'); ?> <!-- Book Now Modal -->
        <?= partial('layouts/header'); ?> <!-- Header -->
    </div>

    <div class="container-body">
        <div class="row">
            <div class="col-12">
                <main class="container-main">
                    <!-- Hero -->
                    <?= featured('home/components/hero'); ?>

                    <!-- Features -->
                    <?= featured('home/components/features'); ?>

                    <!-- Services -->
                    <?= featured('services/components/services'); ?>

                    <!-- Products -->
                    <?= featured('products/components/products'); ?>

                    <!-- Testimonials -->
                    <?= partial('components/testimonials'); ?>

                    <!-- Separator -->
                    <?= partial('components/separator'); ?>

                    <!-- Locations -->
                    <?= featured('home/components/location'); ?>

                    <!-- Reserve -->
                    <?= partial('components/reserve'); ?>
                </main>
            </div>
            <div class="col-12">
                <?= partial('layouts/footer'); ?> <!-- Footer -->
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts'); ?> <!-- rcs Scripts -->
    <script src="<?= asset('lib/swiper/swiper-bundle.min.js'); ?>"></script><!-- Swiper JS -->
    <script src="<?= featured('home/js/main.js', true); ?>"></script>
    <script src="<?= featured('services/js/landing-lock.js', true); ?>"></script>
    <script src="<?= featured('services/js/user-services-list.js', true); ?>"></script>
    <script src="<?= featured('products/js/landing-lock.js', true); ?>"></script>
    <script src="<?= featured('products/js/user-products-list.js', true); ?>"></script>
    <!-- More scripts to be added for home page here -->
</body>

</html>