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
    </div>

    <div class="site-cont">
        <!-- Site Header -->
        <?= partial('layouts/header'); ?>

        <main class="site-main">
            <!-- Highlights -->
            <?= featured('user/products/components/header-single-view'); ?>

            <!-- Highlights -->
            <?= featured('user/products/components/highlights'); ?>

            <!-- About -->
            <?= featured('user/products/components/about'); ?>

            <!-- Related Services -->
            <?= featured('user/products/components/related'); ?>

            <!-- Reviews -->
            <?= featured('user/products/components/reviews'); ?>
        </main>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts'); ?> <!-- rcs Scripts -->
</body>

</html>