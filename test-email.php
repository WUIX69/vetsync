<?php

require_once 'vendor/autoload.php';

use VetSync\Services\Email;

echo "=== EMAIL NOTIFICATION TEST ===\n\n";

$email = new Email();

// Test email address - CHANGE THIS TO YOUR EMAIL
$testEmail = 'tubanajordanmiguel@gmail.com';  // ← Change this to your actual email
$userName = 'Jordan Tubana';

echo "📧 Testing email notifications to: $testEmail\n";
echo "=" . str_repeat("=", 60) . "\n\n";

// Test 1: Appointment Confirmation
echo "1️⃣  Testing Appointment Confirmation Email...\n";
$result1 = $email->sendAppointmentConfirmation(
    $testEmail,
    $userName,
    'Buddy',                    // Pet name
    'Vaccination',              // Service
    '2024-10-15',              // Date
    '10:00:00'                 // Time
);

if ($result1['success']) {
    echo "   ✅ SUCCESS: " . $result1['message'] . "\n";
} else {
    echo "   ❌ FAILED: " . $result1['message'] . "\n";
}
echo "\n";

// Test 2: Product Ready for Pickup
echo "2️⃣  Testing Product Pickup Email...\n";
$result2 = $email->sendPickupNotification(
    $testEmail,
    $userName,
    'Dog Food (5kg), Vitamins',  // Products
    '2024-10-12',                // Order date
    1250.00                      // Total amount
);

if ($result2['success']) {
    echo "   ✅ SUCCESS: " . $result2['message'] . "\n";
} else {
    echo "   ❌ FAILED: " . $result2['message'] . "\n";
}
echo "\n";

// Test 3: Account Verification
echo "3️⃣  Testing Account Verified Email...\n";
$result3 = $email->sendAccountValidated($testEmail, $userName);

if ($result3['success']) {
    echo "   ✅ SUCCESS: " . $result3['message'] . "\n";
} else {
    echo "   ❌ FAILED: " . $result3['message'] . "\n";
}
echo "\n";

// Test 4: Welcome Email
echo "4️⃣  Testing Welcome Email...\n";
$result4 = $email->sendWelcomeEmail($testEmail, $userName);

if ($result4['success']) {
    echo "   ✅ SUCCESS: " . $result4['message'] . "\n";
} else {
    echo "   ❌ FAILED: " . $result4['message'] . "\n";
}
echo "\n";

// Summary
echo "=" . str_repeat("=", 60) . "\n";
echo "📊 TEST SUMMARY:\n";
echo "   Total Tests: 4\n";
$passed = 0;
if ($result1['success'])
    $passed++;
if ($result2['success'])
    $passed++;
if ($result3['success'])
    $passed++;
if ($result4['success'])
    $passed++;
echo "   Passed: $passed\n";
echo "   Failed: " . (4 - $passed) . "\n";
echo "\n";

if ($passed === 4) {
    echo "🎉 ALL TESTS PASSED! Check your inbox at: $testEmail\n";
} else {
    echo "⚠️  Some tests failed. Check the errors above.\n";
}

echo "\n=== TEST COMPLETE ===\n";
