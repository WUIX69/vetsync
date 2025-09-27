<?php
include_once __DIR__ . '/../../core/app.php';

// Get product UUID from URL parameter
$product_uuid = $_GET['uuid'] ?? null;

if (!$product_uuid) {
    header('Location: /src/app/user/products.php');
    exit;
}

// Fetch product data
use VetSync\Models\Products;
$product_result = Products::single($product_uuid);

if (!$product_result['success'] || empty($product_result['data'])) {
    header('Location: /src/app/user/products.php');
    exit;
}

$product = $product_result['data'];

// Ensure essential fields exist with defaults
$product['name'] = $product['name'] ?? 'Unknown Product';
$product['description'] = $product['description'] ?? 'No description available';
$product['og_price'] = $product['og_price'] ?? 0;
$product['dc_price'] = $product['dc_price'] ?? 0;
$product['stock'] = $product['stock'] ?? 0;
$product['tags'] = $product['tags'] ?? '';
$product['specs'] = $product['specs'] ?? '';
$product['uuid'] = $product['uuid'] ?? '';

// Make product data globally available for components
$GLOBALS['product'] = $product;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta'); ?> <!-- rcs Meta -->
    <title><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?> - VetSync</title>
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
            <!-- Header -->
            <?= featured('products/components/header-single-view'); ?>

            <!-- Highlights -->
            <?= featured('products/components/highlights'); ?>

            <!-- About -->
            <?= featured('products/components/about'); ?>

            <!-- Related Products -->
            <?= featured('products/components/related'); ?>

            <!-- Reviews -->
            <?= featured('products/components/reviews'); ?>
        </main>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts'); ?> <!-- rcs Scripts -->
</body>

</html>