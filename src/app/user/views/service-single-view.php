<?php include_once __DIR__ . '/../../../utils/php/functions.php'; ?>
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
        <?= shared('components/booknow-modal'); ?> <!-- Book Now Modal -->
    </div>

    <div class="site-cont">
        <!-- Site Header -->
        <?= featured('user/shared/layouts/header'); ?>

        <main class="site-main">
            <!-- Header -->
            <?= featured('user/services/components/header-single-view'); ?>

            <!-- Highlights -->
            <?= featured('user/services/components/highlights'); ?>

            <!-- About -->
            <?= featured('user/services/components/about'); ?>

            <!-- Related Services -->
            <?= featured('user/services/components/related'); ?>
        </main>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts'); ?> <!-- rcs Scripts -->
</body>

</html>