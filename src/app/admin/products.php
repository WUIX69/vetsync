<?php include_once __DIR__ . '/../../core/app.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta') ?>
    <title>Admin Products Dashboard</title>
    <?= shared('elements/styles') ?>
</head>

<body>

    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window') ?> <!-- Window Spinner -->
    </div>

    <div class="container-body pusher">
        <!-- Sidebar -->
        <?= partial('layouts/sidebar') ?> <!-- Sidebar -->
        <!-- Navbar -->
        <?= partial('layouts/navbar') ?> <!-- Navbar -->

        <!-- Main Content -->
        <main class="container-main">

            <div class="row">
                <div class="col-lg-12">
                    <!-- Products Management -->
                    <?= featured('products/components/products-management') ?>

                    <!-- Product Modal -->
                    <?= featured('products/components/product-modal') ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts') ?>
    <script src="<?= featured('admin/dashboard/js/main.js', true) ?>"></script>
    <script src="<?= featured('admin/dashboard/js/product-management.js', true) ?>"></script>

</body>

</html>