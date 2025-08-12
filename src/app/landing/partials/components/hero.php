<?php

global $activeLink; // Get the active link from the header
$activeLink = $activeLink === 'product-single-view' ? 'products' : $activeLink;
$activeLink = $activeLink === 'service-single-view' ? 'services' : $activeLink;

$heroImage = asset('img/contents/hero/' . $activeLink . '.jpg');
$pageTitle = match ($activeLink) {
    'services' => 'Our Services',
    'products' => 'Our Products',
    'contact' => 'Contact Us',
    'about' => 'About Us',
    'product-single-view' => 'Product Details',
    'service-single-view' => 'Service Details',
    '' => 'Welcome to VetSync',
    default => null,
};

?>
<style>
    /*----------- MAIN (Hero) -----------*/
    main section.hero-section {
        background-image: url("<?= $heroImage ?>");
        <?php if ($activeLink === 'contact'): ?>
            background-position: top center;
        <?php endif; ?>
    }
</style>

<section class="hero-section">
    <div class="container-xl">
        <div class="hero-content">
            <div class="hero-breadcrumb">
                <div class="ui breadcrumb">
                    <a class="section">Home</a>
                    <i class="right angle icon divider"></i>
                    <a class="section active text-capitalize"><?= $activeLink ?></a>
                </div>
            </div>
            <div class="hero-header text-capitalize">
                <?= $pageTitle ?>
            </div>
        </div>
    </div>
</section>