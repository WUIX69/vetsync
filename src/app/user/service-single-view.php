<?php
include_once __DIR__ . '/../../core/app.php';

// Get service UUID from URL parameter
$service_uuid = $_GET['uuid'] ?? null;

if (!$service_uuid) {
    header('Location: /src/app/user/services.php');
    exit;
}

// Fetch service data
use VetSync\Models\Services;
$service_result = Services::single($service_uuid);

if (!$service_result['success'] || empty($service_result['data'])) {
    header('Location: /src/app/user/services.php');
    exit;
}

$service = $service_result['data'];

// Ensure essential fields exist with defaults
$service['name'] = $service['name'] ?? 'Unknown Service';
$service['description'] = $service['description'] ?? 'No description available';
$service['price'] = $service['price'] ?? 0;
$service['duration'] = $service['duration'] ?? 'Not specified';
$service['uuid'] = $service['uuid'] ?? '';

// Make service data globally available for components
$GLOBALS['service'] = $service;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta'); ?> <!-- rcs Meta -->
    <title><?= htmlspecialchars($service['name'], ENT_QUOTES, 'UTF-8') ?> - VetSync</title>
    <?= shared('elements/styles'); ?> <!-- rcs Styles -->
</head>

<body>

    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window'); ?> <!-- Window Spinner -->
        <?= shared('layouts/top-redirect-btn'); ?> <!-- Top Redirect Button -->
        <?= featured('services/components/booknow-modal'); ?> <!-- Book Now Modal -->
    </div>

    <div class="site-cont">
        <!-- Site Header -->
        <?= partial('layouts/header'); ?>

        <main class="site-main">
            <!-- Header -->
            <?= featured('services/components/header-single-view'); ?>

            <!-- Highlights -->
            <?= featured('services/components/highlights'); ?>

            <!-- About -->
            <?= featured('services/components/about'); ?>

            <!-- Related Services -->
            <?= featured('services/components/related'); ?>
            <!-- Reviews -->
            <?= featured('services/components/reviews'); ?>
        </main>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts'); ?> <!-- rcs Scripts -->

    <?= featured('services/components/booknow-modal'); ?> <!-- Book Now Modal -->
</body>

</html>