<?php

$appUrlPath = $_SERVER['REQUEST_URI'] ?? null;
$appDirName = explode('/', trim($appUrlPath, '/'))[2] ?? '';

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