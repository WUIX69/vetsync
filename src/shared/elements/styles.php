<?php

$appUrlPath = $_SERVER['REQUEST_URI'] ?? null;
$appDirName = explode('/', trim($appUrlPath, '/'))[2] ?? '';

$styles = [
    'vendor/bootstrap/dist/css/bootstrap-grid.min.css',
    'vendor/bootstrap/dist/css/bootstrap-utilities.min.css',
    'vendor/bootstrap/dist/css/bootstrap-reboot.min.css',
    // 'vendor/bootstrap/dist/css/bootstrap.min.css',
    'vendor/fomantic-ui/dist/semantic.min.css',
    'assets/css/global.css',
    'assets/css/loader/window.css',
    'assets/css/' . $appDirName . '/style.css',
    'assets/css/' . $appDirName . '/responsive.css',
];

foreach ($styles as $style) {
    echo '<link rel="stylesheet" href="' . statf($style) . '">';
}