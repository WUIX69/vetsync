<?php include_once __DIR__ . '/../../../core/app.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta'); ?> <!-- rcs Meta -->
    <title>Services (View) - VetSync</title>
    <?= shared('elements/styles'); ?> <!-- rcs Styles -->
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
                    <?= partial('components/hero'); ?>

                    <!-- Highlights -->
                    <?= featured('landing/services/components/highlights'); ?>

                    <!-- About -->
                    <?= featured('landing/services/components/about'); ?>

                    <!-- Related Services -->
                    <?= featured('landing/services/components/related'); ?>
                </main>
            </div>
            <div class="col-12">
                <?= partial('layouts/footer'); ?> <!-- Footer -->
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts'); ?> <!-- rcs Scripts -->
</body>

</html>