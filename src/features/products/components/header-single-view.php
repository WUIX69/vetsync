<?php
$product = $GLOBALS['product'] ?? null;
if (!$product) {
    return;
}
?>
<section class="header py-5">
    <div class="container-xl">
        <h1><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?> <span class="emoji">🛍️</span></h1>
        <div class="ui breadcrumb mt-3">
            <a class="section" href="/src/app/user/dashboard.php">Dashboard</a>
            <i class="right angle icon divider"></i>
            <a class="section" href="/src/app/user/products.php">Products</a>
            <i class="right angle icon divider"></i>
            <div class="active section"><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></div>
        </div>
    </div>
</section>