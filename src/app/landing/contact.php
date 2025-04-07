<?php include_once __DIR__ . '/../../utils/php/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta'); ?> <!-- rcs Meta -->
    <title>Contact Us - Josephine Anne Angeles Veterinary Clinic</title>
    <?= shared('elements/styles'); ?> <!-- rcs Styles -->
</head>

<body>

    <div class="shared-standalone-content">
        <!-- Window Spinner -->
        <?= shared('layouts/loader/window'); ?>
        <!-- top-redirect-button -->
        <?= shared('layouts/top-redirect-btn'); ?>
        <!-- Book Now Modal -->
        <?= shared('components/booknow-modal'); ?>
        <!--Navbar header -->
        <?= featured('landing/shared/layouts/header'); ?>
    </div>

    <div class="container-body">
        <div class="row">
            <div class="col-12">
                <main class="container-main">
                    <!-- Hero -->
                    <?= featured('landing/shared/components/section/hero'); ?>

                    <!-- Contact -->
                    <?= featured('landing/contact/components/section/contact'); ?>

                    <!-- Locations -->
                    <?= featured('landing/contact/components/section/location'); ?>

                    <!-- Reserve -->
                    <?= featured('landing/shared/components/section/reserve'); ?>
                </main>
            </div>
            <!-- footer -->
            <div class="col-12">
                <?= featured('landing/shared/layouts/footer'); ?>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts'); ?>
    <?= featured('landing/contact/scripts'); ?>
</body>

</html>