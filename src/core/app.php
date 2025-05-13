<?php
require_once 'config.php';

// Load configuration
Config::load();

// Example usage:
$dbConnection = Config::getDB();
$appName = Config::get('APP_NAME');
$dateFormat = Config::get('DATE_FORMAT');

// Example of formatting current date
$today = date(Config::get('DATE_FORMAT'));