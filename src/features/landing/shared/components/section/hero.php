<?php
$featureUrlPath = $_SERVER['REQUEST_URI'] ?? null;
$featurePage = str_replace('.php', '', explode('/', trim($featureUrlPath, '/'))[3] ?? 'default');
$heroImage = statf('assets/img/contents/hero/' . $featurePage . '.jpg');
?>
<style>
    /*----------- MAIN (Hero) -----------*/
    main .section-container:has(section.hero-section) {
        background-image: url("<?= $heroImage ?>");
        <?php if ($featurePage === 'contact'): ?>
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
                    <a class="section active text-capitalize"><?= $featurePage ?></a>
                </div>
            </div>
            <div class="hero-header text-capitalize">
                <?= $featurePage ?>
            </div>
        </div>
    </section>
</div>