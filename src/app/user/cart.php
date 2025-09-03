<?php include_once __DIR__ . '/../../core/app.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta'); ?> <!-- rcs Meta -->
    <title>Cart - VetSync</title>
    <?= shared('elements/styles'); ?> <!-- rcs Styles -->
    <!-- Add Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
            <!-- Header -->
            <?= featured('products/components/cart-header'); ?>

            <!-- Cart Content -->
            <?= featured('products/components/cart-content'); ?>

            <!-- Reservation Modal -->
            <?= featured('products/components/reservation-modal'); ?>
        </main>
    </div>

    <?= shared('elements/scripts'); ?> <!-- rcs Scripts -->
    <script src="/src/features/products/js/cart.js"></script>
</body>

</html>