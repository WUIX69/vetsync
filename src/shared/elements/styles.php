<?php

$styles = [
    'vendor/bootstrap/dist/css/bootstrap-grid.min.css',
    'vendor/bootstrap/dist/css/bootstrap-utilities.min.css',
    'vendor/bootstrap/dist/css/bootstrap-reboot.min.css',
    'vendor/fomantic-ui/dist/semantic.min.css',
    'assets/css/global.css',
    'assets/css/auth.css',
    'assets/css/spinner.css',
];

foreach ($styles as $style) {
    echo '<link rel="stylesheet" href="' . statf($style) . '">';
}