<?php

$appDirName = uriAppPath();
$styles = [
    // 'vendor/bootstrap/dist/css/bootstrap-grid.min.css',
    // 'vendor/bootstrap/dist/css/bootstrap-utilities.min.css',
    // 'vendor/bootstrap/dist/css/bootstrap-reboot.min.css',
    'vendor/bootstrap/dist/css/bootstrap.css',
    'vendor/fomantic-ui/dist/semantic.min.css',
    'css/global.css',
    'css/loader/window.css',
    'css/' . $appDirName . '/style.css',
    'css/' . $appDirName . '/responsive.css',
];

foreach ($styles as $style) {
    echo '<link rel="stylesheet" href="' . asset($style) . '">';
}

// Load Favicon
echo '<link rel="icon" type="image/png" href="' . asset('favicon.png') . '">';