<?php

$appUrlPath = $_SERVER['REQUEST_URI'] ?? null;
$appDirName = explode('/', trim($appUrlPath, '/'))[2] ?? '';

// All resources required scripts
$scripts = [
    'lib/jquery/jquery.min.js',
    'vendor/fomantic-ui/dist/semantic.min.js',
    'lib/lodash/lodash.min.js',
    'js/loader/window.js',
    'js/darkmode.js',
    'js/' . $appDirName . '/script.js',
    'js/' . $appDirName . '/prefetch.js',
    'js/scripts.js',
];
foreach ($scripts as $script) {
    echo '<script src="' . asset($script) . '"></script>';
}

// Required utils that can be used in any page
$utils = [
    'js/validateHandler.js',
    'js/middleware.js',
];
foreach ($utils as $util) {
    echo '<script src="' . utils($util, true) . '"></script>';
}

?>

<script>
    $(function () {
        // Custom js here...
    });
</script>