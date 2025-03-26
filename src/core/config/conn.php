<?php

// Example database query
$db = Config::getDB();
$stmt = $db->query("SELECT * FROM users");

// Using custom settings
$currency = Config::get('CURRENCY');
$formattedDate = date(Config::get('DATE_FORMAT'));