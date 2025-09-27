<?php
require_once 'src/core/app.php';

// Test user data
$testData = [
    'firstname' => 'Test',
    'lastname' => 'User',
    'email' => 'tubanajordanmiguel@gmail.com', // Your test email
    'telephone' => '09813905948',
    'password' => 'password123'
];

echo "<h1>🧪 Registration & Email Test</h1>";

// Simulate POST request to registration API
$_POST = $testData;
$_POST['password'] = password_hash($testData['password'], PASSWORD_DEFAULT);
$_SERVER['REQUEST_METHOD'] = 'POST';

echo "<h3>📝 Test Data:</h3>";
echo "<pre>" . print_r($testData, true) . "</pre>";

echo "<h3>🚀 Testing Registration...</h3>";

// Include the registration API
ob_start();
include 'src/features/auth/api/register.php';
$result = ob_get_clean();

echo "<h3>📧 API Response:</h3>";
echo "<pre>" . $result . "</pre>";

$response = json_decode($result, true);

if ($response && $response['success']) {
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; color: #155724; margin: 20px 0;'>
        ✅ <strong>Registration Successful!</strong><br>
        Check your email for the welcome message.
    </div>";
} else {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; color: #721c24; margin: 20px 0;'>
        ❌ <strong>Registration Failed:</strong><br>
        " . ($response['message'] ?? 'Unknown error') . "
    </div>";
}

echo "<h3>📋 Next Steps:</h3>";
echo "<ul>
    <li>Check your email inbox for welcome message</li>
    <li>If successful, the user should appear in admin panel</li>
    <li>Try logging in with the test credentials</li>
</ul>";
?>