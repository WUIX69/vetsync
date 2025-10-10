<?php

require_once 'vendor/autoload.php';

use VetSync\Services\Sms;

echo "=== TWILIO SMS TEST ===\n\n";

$sms = new Sms();

// Replace with your VERIFIED Twilio number
$testNumber = '+639102131240';
$testMessage = "Test SMS from J.A.A Veterinary Clinic via Twilio!";

echo "Sending test SMS to: $testNumber\n";
echo "Message: $testMessage\n\n";

$result = $sms->send($testNumber, $testMessage);

if ($result['success']) {
    echo "✅ SUCCESS!\n";
    echo "Message: " . $result['message'] . "\n";
    if (isset($result['sid'])) {
        echo "Twilio SID: " . $result['sid'] . "\n";
    }
} else {
    echo "❌ FAILED!\n";
    echo "Error: " . $result['message'] . "\n";
}

echo "\n=== TEST COMPLETE ===\n";