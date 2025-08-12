<?php include_once __DIR__ . '/../../core/app.php'; ?>
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
        <?= partial('layouts/header'); ?>
    </div>

    <div class="container-body">
        <div class="row">
            <div class="col-12">
                <main class="container-main">
                    <!-- Hero -->
                    <?= partial('components/hero'); ?>

                    <!-- Contact -->
                    <?= featured('contact/components/contact'); ?>

                    <!-- Locations -->
                    <?= featured('contact/components/location'); ?>

                    <!-- Reserve -->
                    <?= partial('components/reserve'); ?>
                </main>
            </div>
            <!-- footer -->
            <div class="col-12">
                <?= partial('layouts/footer'); ?>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts'); ?>
    <script src="<?= featured('contact/js/main.js', true) ?>"></script>
</body>

</html>