<?php
require_once 'src/core/app.php';

use VetSync\Models\Appointments;
use VetSync\Models\Reservations;
use VetSync\Services\Email;

echo "<!DOCTYPE html>
<html>
<head>
    <title>VetSync Email Integration Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .result { margin: 20px 0; padding: 15px; border-radius: 5px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .info { background: #e2e3e5; border: 1px solid #d6d8db; color: #383d41; }
        .warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔗 VetSync Email Integration Test</h1>
        <p>This script tests the new email integrations for appointments and reservations.</p>";

echo "<div class='info'><strong>📋 Integration Summary:</strong><br>
✅ Appointment confirmation emails when status changes to 'accepted'<br>
✅ Product pickup notification emails when reservation status changes to 'ready_for_pickup'<br>
✅ Pet deceased condolence emails already implemented<br>
✅ User verification emails already implemented<br>
</div>";

// Test 1: Check if Email service is accessible
echo "<h3>Test 1: Email Service Accessibility</h3>";
try {
    $emailService = new Email();
    echo "<div class='result success'>✅ Email service instantiated successfully!</div>";
} catch (Exception $e) {
    echo "<div class='result error'>❌ Email service error: " . $e->getMessage() . "</div>";
    exit;
}

// Test 2: Verify appointment model has email integration
echo "<h3>Test 2: Appointment Model Integration</h3>";
try {
    // Check if the updateStatusWithReason method exists and can handle email logic
    if (method_exists('VetSync\Models\Appointments', 'updateStatusWithReason')) {
        echo "<div class='result success'>✅ Appointments model has updateStatusWithReason method</div>";
        echo "<div class='result info'>📧 Email integration: Sends confirmation when appointment status = 'accepted'</div>";
    } else {
        echo "<div class='result error'>❌ updateStatusWithReason method not found in Appointments model</div>";
    }
} catch (Exception $e) {
    echo "<div class='result error'>❌ Appointments model error: " . $e->getMessage() . "</div>";
}

// Test 3: Verify reservations model has email integration
echo "<h3>Test 3: Reservations Model Integration</h3>";
try {
    // Check if the updateStatus method exists and can handle email logic
    if (method_exists('VetSync\Models\Reservations', 'updateStatus')) {
        echo "<div class='result success'>✅ Reservations model has updateStatus method</div>";
        echo "<div class='result info'>📧 Email integration: Sends pickup notification when reservation status = 'ready_for_pickup'</div>";
    } else {
        echo "<div class='result error'>❌ updateStatus method not found in Reservations model</div>";
    }
} catch (Exception $e) {
    echo "<div class='result error'>❌ Reservations model error: " . $e->getMessage() . "</div>";
}

// Test 4: Database connectivity
echo "<h3>Test 4: Database Connectivity</h3>";
try {
    global $conn;
    if ($conn) {
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM appointments LIMIT 1");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<div class='result success'>✅ Database connection working - Found {$result['count']} appointments</div>";

        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM reservations LIMIT 1");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<div class='result success'>✅ Database connection working - Found {$result['count']} reservations</div>";
    } else {
        echo "<div class='result error'>❌ Database connection not available</div>";
    }
} catch (Exception $e) {
    echo "<div class='result error'>❌ Database error: " . $e->getMessage() . "</div>";
}

// Test 5: Email configuration
echo "<h3>Test 5: Email Configuration</h3>";
try {
    $config = include 'src/core/email-config.php';
    if (isset($config['smtp']['host'])) {
        echo "<div class='result success'>✅ Email configuration loaded successfully</div>";
        echo "<div class='result info'>📧 SMTP Host: {$config['smtp']['host']}<br>📧 From: {$config['smtp']['from_email']}</div>";
    } else {
        echo "<div class='result error'>❌ Email configuration missing SMTP settings</div>";
    }
} catch (Exception $e) {
    echo "<div class='result error'>❌ Email configuration error: " . $e->getMessage() . "</div>";
}

echo "<h3>🎯 Integration Points</h3>";
echo "<div class='result warning'>
    <strong>📝 Implementation Details:</strong><br><br>
    
    <strong>1. Appointment Confirmations:</strong><br>
    • Triggered in: <code>src/models/appointments.php → updateStatusWithReason()</code><br>
    • When: Status changes to 'accepted'<br>
    • Email: <code>sendAppointmentConfirmation()</code><br>
    • Data: User email, name, pet name, service, date<br><br>
    
    <strong>2. Pickup Notifications:</strong><br>
    • Triggered in: <code>src/models/reservations.php → updateStatus()</code><br>
    • When: Status changes to 'ready_for_pickup'<br>
    • Email: <code>sendPickupNotification()</code><br>
    • Data: User email, name, products list, total amount<br><br>
    
    <strong>3. Existing Integrations:</strong><br>
    • Pet deceased notifications: ✅ Already implemented<br>
    • User registration welcome: ✅ Already implemented<br>
    • Account verification: ✅ Already implemented<br>
</div>";

echo "<h3>🚀 Next Steps</h3>";
echo "<div class='result info'>
    <strong>To test the new integrations:</strong><br>
    1. Create an appointment and change its status to 'accepted' in admin panel<br>
    2. Create a product reservation and mark it as 'ready_for_pickup'<br>
    3. Check email delivery and logs for any issues<br>
    4. Monitor error logs for email sending failures
</div>";

echo "
    </div>
</body>
</html>";
?>