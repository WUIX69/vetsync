<?php
// Simple test for verification API
include 'src/core/app.php';

$postData = [
    'action' => 'update_verification',
    'user_uuid' => '03bd056a-4f17-40c0-bac1-365E60d6f1',  // From your database
    'status' => 'verified'
];

// Simulate POST request
$_POST = $postData;
$_SERVER['REQUEST_METHOD'] = 'POST';

// Capture output
ob_start();
include 'src/features/users/api/users.php';
$output = ob_get_clean();

echo "<h2>🔍 Testing Verification API</h2>";
echo "<h3>POST Data:</h3>";
echo "<pre>" . print_r($postData, true) . "</pre>";
echo "<h3>Raw Output:</h3>";
echo "<pre>" . htmlspecialchars($output) . "</pre>";

// Try to decode JSON
$decoded = json_decode($output, true);
echo "<h3>Decoded JSON:</h3>";
if ($decoded) {
    echo "<pre>" . print_r($decoded, true) . "</pre>";
} else {
    echo "<p style='color: red;'>❌ Invalid JSON response!</p>";
    echo "<p>JSON Error: " . json_last_error_msg() . "</p>";
}
?>