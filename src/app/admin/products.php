<?php include_once __DIR__ . '/../../core/app.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta') ?>
    <title>Admin Products Dashboard</title>
    <?= shared('elements/styles') ?>
    <?= shared('elements/filepond/styles') ?>
</head>

<body>

    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window') ?> <!-- Window Spinner -->
        <?= featured('products/components/product-modal') ?><!-- Product Modal -->
        <?= featured('products/components/category-modal') ?><!-- Product Category Modal -->
    </div>

    <div class="container-body pusher">
        <?= partial('layouts/sidebar') ?> <!-- Sidebar -->
        <?= partial('layouts/navbar') ?> <!-- Navbar -->

        <!-- Main Content -->
        <main class="container-main">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Products Stats -->
                    <?= featured('products/components/stats') ?>
                </div>
                <div class="col-lg-9">
                    <!-- Categories Table -->
                    <?= featured('products/components/categories-table') ?>
                </div>
                <div class="col-lg-3">
                    <!-- System Info -->
                    <?= featured('products/components/sys-info') ?>
                </div>
                <div class="col-lg-12">
                    <!-- Products Table -->
                    <?= featured('products/components/products-table') ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts') ?>
    <?= shared('elements/filepond/scripts') ?>
    <script src="<?= featured('products/js/productCategories.js', true) ?>"></script>
    <script src="<?= featured('products/js/productsTable.js', true) ?>"></script>

</body>

</html>