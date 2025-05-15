<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../utils/php/functions.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/php-set.php';
require_once __DIR__ . '/conn.php';
require_once __DIR__ . '/session.php';

// Example usage:
// $dbConnection = Config::getDB();
// $appName = Config::get('APP_NAME');
// $dateFormat = Config::get('DATE_FORMAT');

// Example of formatting current date
// $today = date(Config::get('DATE_FORMAT'));