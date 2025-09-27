<?php
// Direct registration test
include 'src/core/app.php';

use VetSync\Models\Users;

$testData = [
    'uuid' => uuid(),
    'firstname' => 'Test',
    'lastname' => 'User',
    'email' => 'test@example.com',
    'telephone' => '1234567890',
    'password' => password_hash('test123', PASSWORD_DEFAULT)
];

echo "Testing direct registration...\n";
$result = Users::store($testData);
echo "Result: " . json_encode($result) . "\n";
?>