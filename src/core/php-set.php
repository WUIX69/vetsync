<?php

// Configure PHP settings
ini_set('memory_limit', '512M');
ini_set('max_execution_time', '300');
ini_set('upload_max_filesize', '10M');
ini_set('post_max_size', '10M');

// PHP Error Reporting
if ($_ENV['APP_DEBUG']) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    ini_set('log_errors', 1);
    ini_set('error_log', $config['root_path'] . '/logs/error.log');
}

// Set timezone
date_default_timezone_set($_ENV['TIMEZONE']);