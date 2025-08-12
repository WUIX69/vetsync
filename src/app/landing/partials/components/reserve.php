<?php
// Define the path to reserve images directory
$reservesPath = asset('img/contents/reserves/');
$fullPath = $_SERVER['DOCUMENT_ROOT'] . parse_url($reservesPath, PHP_URL_PATH);

// Get all jpg images from the reserves directory
$reserveImages = glob($fullPath . '*.jpg');

// Select random image and format path, default to empty if no images found
$reserveImage = '';
if (!empty($reserveImages)) {
    $randomImage = $reserveImages[array_rand($reserveImages)];
    $imageName = basename($randomImage);
    $reserveImage = $reservesPath . $imageName;
}
?>

<style>
    main section.reserve-section {
        background-image: url("<?= $reserveImage ?>");
    }
</style>
<section class="reserve-section">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-6">
                <div class="header">
                    <h2>Do You Want To Earn With Us? <br> So Don't Be Late.</h2>
                    <?= partial('components/ui/booknow-btn') ?>
                </div>
            </div>
        </div>
    </div>
</section>