<?php
// TODO: move all the global logic in /config dir

global $activeLink; // Get the active link from the header
$heroImage = asset('img/contents/hero/' . $activeLink . '.jpg');
$heroTitles = [
    'services' => 'Our Services',
    'products' => 'Our Products',
    'contact' => 'Contact Us',
    'about' => 'About Us',
    '' => 'Welcome to VetSync'
];

// Handle single view pages
global $urlParts; // Get the urlParts from the header 
$isDetailView = in_array('single-view.php', $urlParts);
if ($isDetailView) {
    if ($activeLink === 'services') {
        $heroTitles[$activeLink] = 'Service Details';
    } elseif ($activeLink === 'products') {
        $heroTitles[$activeLink] = 'Product Details';
    }
}

$pageTitle = $heroTitles[$activeLink] ?? null;

?>
<style>
    /*----------- MAIN (Hero) -----------*/
    main .section-container:has(section.hero-section) {
        background-image: url("<?= $heroImage ?>");
        <?php if ($activeLink === 'contact'): ?>
            background-position: top center;
        <?php endif; ?>
    }
</style>
<div class="section-container">
    <section class="section-wrapper hero-section">
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
    </section>
</div>