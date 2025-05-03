<?php include_once __DIR__ . '/../../../utils/php/functions.php'; ?>
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
        <?= featured('user/shared/layouts/header'); ?>

        <main class="site-main">
            <!-- Highlights -->
            <?= featured('user/product-view/components/header'); ?>

            <!-- Highlights -->
            <?= featured('user/product-view/components/highlights'); ?>

            <!-- About -->
            <?= featured('user/product-view/components/about'); ?>

            <!-- Related Services -->
            <?= featured('user/product-view/components/related'); ?>

            <!-- Reviews -->
            <?= featured('user/product-view/components/reviews'); ?>
        </main>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts'); ?> <!-- rcs Scripts -->
    <script>
        $(function () {
            $('.ui.rating').rating();
        });
    </script>
</body>

</html>