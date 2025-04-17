<?php include_once __DIR__ . '/../../../utils/php/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta'); ?> <!-- rcs Meta -->
    <title>Services - VetSync</title>
    <?= shared('elements/styles'); ?> <!-- rcs Styles -->
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

                    <!-- Products -->
                    <?= featured('landing/products/components/products'); ?>

                    <!-- Reserve -->
                    <?= featured('landing/shared/components/reserve'); ?>
                </main>
            </div>
            <div class="col-12">
                <?= featured('landing/shared/layouts/footer'); ?> <!-- Footer -->
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts'); ?>
    <?= featured('landing/services/scripts'); ?>
</body>

</html>