<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/services/email.php';

use VetSync\Services\Email;

$emailService = new Email();

// Test 1: Appointment Confirmation with all details
echo "Testing Appointment Confirmation Email...\n";
$result = $emailService->sendAppointmentConfirmation(
    'tubanajordanmiguel@gmail.com', // ✅ Changed
    'John Doe',
    'Buddy',
    'Vaccination & Check-up',
    '2025-10-20',
    '10:30:00',
    '09123456789',
    'Please bring vaccination card. My pet is scared of needles.',
    'APT-ABC12345'
);

if ($result['success']) {
    echo "✅ Appointment email sent successfully!\n";
} else {
    echo "❌ Failed: " . $result['message'] . "\n";
}

echo "\n---\n\n";

// Test 2: Pickup Notification with all details
echo "Testing Pickup Notification Email...\n";
$result = $emailService->sendPickupNotification(
    'tubanajordanmiguel@gmail.com',
    'John Doe',
    "• Royal Canin Dog Food (5kg)<br>• Frontline Flea Treatment<br>• Pet Shampoo",
    '2025-10-15',
    2450.00,
    'ORD-XYZ78901',
    'Pending'
);

if ($result['success']) {
    echo "✅ Pickup email sent successfully!\n";
} else {
    echo "❌ Failed: " . $result['message'] . "\n";
}

echo "\n---\n\n";

// Test 3: Welcome Email
echo "Testing Welcome Email...\n";
$result = $emailService->sendWelcomeEmail(
    'tubanajordanmiguel@gmail.com', // ✅ Changed
    'John Doe'
);

if ($result['success']) {
    echo "✅ Welcome email sent successfully!\n";
} else {
    echo "❌ Failed: " . $result['message'] . "\n";
}

echo "\n---\n\n";

// Test 4: Account Validated Email
echo "Testing Account Validated Email...\n";
$result = $emailService->sendAccountValidated(
    'tubanajordanmiguel@gmail.com', // ✅ Changed
    'John Doe'
);

if ($result['success']) {
    echo "✅ Account validated email sent successfully!\n";
} else {
    echo "❌ Failed: " . $result['message'] . "\n";
}

echo "\n---\n\n";

// Test 5: Appointment Reminder (2 days before)
echo "Testing Appointment Reminder Email...\n";
$result = $emailService->sendAppointmentReminder(
    'tubanajordanmiguel@gmail.com',
    'Jordan Miguel',
    'Buddy',
    'Vaccination & Check-up',
    date('Y-m-d', strtotime('+2 days')), // 2 days from now
    '10:30:00',
    'APT-REMINDER123',
    '09123456789'
);

if ($result['success']) {
    echo "✅ Reminder email sent successfully!\n";
} else {
    echo "❌ Failed: " . $result['message'] . "\n";
}

echo "\n---\n\n";

// Test 6: Account Rejected Email
echo "Testing Account Rejected Email...\n";
$result = $emailService->sendAccountRejected(
    'tubanajordanmiguel@gmail.com',
    'Jordan Miguel',
    'Incomplete documentation. Please provide valid ID and proof of address.'
);

if ($result['success']) {
    echo "✅ Account rejected email sent successfully!\n";
} else {
    echo "❌ Failed: " . $result['message'] . "\n";
}

echo "\nAll tests completed!";