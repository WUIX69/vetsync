<?php
require_once 'src/core/app.php';

use VetSync\Services\Email;
use Exception;

echo "<!DOCTYPE html>
<html>
<head>
    <title>VetSync Email Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .result { margin: 20px 0; padding: 15px; border-radius: 5px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .info { background: #e2e3e5; border: 1px solid #d6d8db; color: #383d41; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 3px; overflow-x: auto; }
        input[type='email'] { width: 300px; padding: 8px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🐾 VetSync Email Test</h1>
        <p>This script will test if your email configuration is working properly.</p>";

// Check if form was submitted
if ($_POST['test_email']) {
    $testEmail = $_POST['test_email'];

    echo "<div class='info'><strong>Testing email to:</strong> $testEmail</div>";

    try {
        $emailService = new Email();

        // Test 1: Basic Email
        echo "<h3>Test 1: Basic Email</h3>";
        $result1 = $emailService->send(
            $testEmail,
            'VetSync Email System Test',
            '<h1>🎉 Email Test Successful!</h1>
            <p>Congratulations! Your VetSync email system is working correctly.</p>
            <p>This is a basic test email to verify the configuration.</p>
            <p><strong>Timestamp:</strong> ' . date('Y-m-d H:i:s') . '</p>'
        );

        if ($result1['success']) {
            echo "<div class='result success'>✅ Basic email sent successfully!</div>";
        } else {
            echo "<div class='result error'>❌ Basic email failed: " . $result1['message'] . "</div>";
        }

        // Test 2: Appointment Confirmation Email
        echo "<h3>Test 2: Appointment Confirmation Email</h3>";
        $result2 = $emailService->sendAppointmentConfirmation(
            $testEmail,
            'Test User',
            'Fluffy the Cat',
            'General Health Checkup',
            date('Y-m-d', strtotime('+7 days')),
            '10:30:00'
        );

        if ($result2['success']) {
            echo "<div class='result success'>✅ Appointment confirmation email sent successfully!</div>";
        } else {
            echo "<div class='result error'>❌ Appointment confirmation failed: " . $result2['message'] . "</div>";
        }

        // Test 3: Pet Deceased Notification Email
        echo "<h3>Test 3: Pet Deceased Notification Email</h3>";
        $result3 = $emailService->sendPetDeceasedNotification(
            $testEmail,
            'Test User',
            'Buddy the Dog',
            2 // cancelled appointments
        );

        if ($result3['success']) {
            echo "<div class='result success'>✅ Pet deceased notification email sent successfully!</div>";
        } else {
            echo "<div class='result error'>❌ Pet deceased notification failed: " . $result3['message'] . "</div>";
        }

        // Test 4: Pickup Notification Email
        echo "<h3>Test 4: Product Pickup Notification Email</h3>";
        $result4 = $emailService->sendPickupNotification(
            $testEmail,
            'Test User',
            '• Premium Dog Food (Qty: 2)<br>• Flea & Tick Shampoo (Qty: 1)<br>• Pet Vitamins (Qty: 3)<br>',
            date('Y-m-d'),
            1250.00
        );

        if ($result4['success']) {
            echo "<div class='result success'>✅ Pickup notification email sent successfully!</div>";
        } else {
            echo "<div class='result error'>❌ Pickup notification failed: " . $result4['message'] . "</div>";
        }

        // Test 5: Account Validation Email
        echo "<h3>Test 5: Account Validation Email</h3>";
        $result5 = $emailService->sendAccountValidated($testEmail, 'Test User');

        if ($result5['success']) {
            echo "<div class='result success'>✅ Account validation email sent successfully!</div>";
        } else {
            echo "<div class='result error'>❌ Account validation failed: " . $result5['message'] . "</div>";
        }

        // Test 6: Account Rejection Email
        echo "<h3>Test 6: Account Rejection Email</h3>";
        $result6 = $emailService->sendAccountRejected($testEmail, 'Test User', 'Incomplete documentation provided');

        if ($result6['success']) {
            echo "<div class='result success'>✅ Account rejection email sent successfully!</div>";
        } else {
            echo "<div class='result error'>❌ Account rejection failed: " . $result6['message'] . "</div>";
        }

        // Test 7: Welcome Email
        echo "<h3>Test 7: Welcome Email</h3>";
        $result7 = $emailService->sendWelcomeEmail($testEmail, 'Test User');

        if ($result7['success']) {
            echo "<div class='result success'>✅ Welcome email sent successfully!</div>";
        } else {
            echo "<div class='result error'>❌ Welcome email failed: " . $result7['message'] . "</div>";
        }

        // Summary
        echo "<h3>📋 Test Summary</h3>";
        $successCount = ($result1['success'] ? 1 : 0) + ($result2['success'] ? 1 : 0) + ($result3['success'] ? 1 : 0) + ($result4['success'] ? 1 : 0) + ($result5['success'] ? 1 : 0) + ($result6['success'] ? 1 : 0) + ($result7['success'] ? 1 : 0);
        echo "<div class='result info'>
            <strong>Results:</strong> $successCount out of 7 tests passed<br>
            <strong>Email Configuration:</strong> " . ($successCount > 0 ? "Working ✅" : "Needs attention ❌") . "
        </div>";

        if ($successCount === 7) {
            echo "<div class='result success'>
                <strong>🎉 Excellent!</strong> All email tests passed. Your VetSync email system is fully functional!
                <br><br>
                <strong>What this means:</strong>
                <ul>
                    <li>✅ Users get confirmation emails when they book appointments</li>
                    <li>🌈 Condolence emails are sent when pets are marked as deceased</li>
                    <li>📦 Pickup notifications are sent when products are ready</li>
                    <li>🎉 Welcome emails are sent to new users</li>
                    <li>✅ Account validation emails are sent when accounts are verified</li>
                    <li>❌ Rejection emails are sent if accounts are not approved</li>
                    <li>📧 All emails sent from: Vetsync.01@gmail.com</li>
                </ul>
            </div>";
        }

    } catch (Exception $e) {
        echo "<div class='result error'>
            <strong>❌ Critical Error:</strong> " . $e->getMessage() . "
            <br><br>
            <strong>Possible issues:</strong>
            <ul>
                <li>PHPMailer not installed (run: composer require phpmailer/phpmailer)</li>
                <li>Invalid Gmail credentials</li>
                <li>App password not set correctly</li>
                <li>2-Factor Authentication not enabled on Gmail</li>
            </ul>
        </div>";
    }

} else {
    // Show form
    echo "
        <form method='POST'>
            <h3>📧 Enter Test Email Address</h3>
            <p>Enter a Gmail address where you want to receive the test emails:</p>
            <input type='email' name='test_email' placeholder='your-email@gmail.com' required>
            <br>
            <button type='submit'>🚀 Run Email Tests</button>
        </form>
        
        <div class='info'>
            <strong>📋 Current Configuration:</strong><br>
            <strong>From Email:</strong> Vetsync.01@gmail.com<br>
            <strong>SMTP Server:</strong> smtp.gmail.com:587<br>
            <strong>Encryption:</strong> TLS
        </div>";
}

echo "
    </div>
</body>
</html>";
?>