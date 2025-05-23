<?php include_once __DIR__ . '/../../core/app.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta'); ?> <!-- rcs Meta -->
    <title>Products - VetSync</title>
    <?= shared('elements/styles'); ?> <!-- rcs Styles -->
    <style></style>
</head>

<body>
    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window'); ?>
        <?= shared('layouts/top-redirect-btn'); ?>
    </div>

    <div class="container-body">
        <!-- Site Header -->
        <?= partial('layouts/header'); ?>

        <main class="site-main">
            <!-- Header -->
            <?= featured('products/components/header'); ?>

            <!-- Products -->
            <?= featured('products/components/products'); ?>
        </main>
    </div>

    <?= shared('elements/scripts'); ?>
    <script type="text/javascript">
        $(function () {
            $('.ui.rating').rating();
        });
    </script>
</body>

</html>