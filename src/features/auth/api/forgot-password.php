<?php

include '../../../core/app.php';
header('Content-Type: application/json');

use VetSync\Models\Users;
use VetSync\Services\Email;

$response = [
    'success' => false,
    'message' => '',
];

try {
    $email = trim($_POST['email'] ?? '');

    error_log("=== FORGOT PASSWORD START ===");
    error_log("Email: " . $email);

    if (!$email) {
        $response['message'] = 'Email is required!';
        echo json_encode($response);
        exit;
    }

    // Check if user exists
    $user = Users::singleWhereUserEmail($email);

    error_log("User found: " . (!empty($user) ? 'YES' : 'NO'));

    if (empty($user)) {
        $response['message'] = 'No account found with that email address.';
        echo json_encode($response);
        exit;
    }

    error_log("User UUID: " . $user['uuid']);
    error_log("Old password hash: " . substr($user['password'], 0, 30) . "...");

    // DEVELOPMENT MODE: Generate temporary password
    $tempPassword = 'Temp' . rand(1000, 9999) . '!';
    $hashedPassword = password_hash($tempPassword, PASSWORD_DEFAULT);

    error_log("Temp Password: " . $tempPassword);
    error_log("New Hash: " . substr($hashedPassword, 0, 30) . "...");

    // Update password directly in database
    global $conn;
    $stmt = $conn->prepare('
        UPDATE users 
        SET password = ?
        WHERE uuid = ?
    ');
    $stmt->execute([$hashedPassword, $user['uuid']]);

    $rowsAffected = $stmt->rowCount();
    error_log("Rows updated: " . $rowsAffected);

    // Verify the update worked
    $checkStmt = $conn->prepare('SELECT password FROM users WHERE uuid = ?');
    $checkStmt->execute([$user['uuid']]);
    $updatedUser = $checkStmt->fetch(PDO::FETCH_ASSOC);
    error_log("Verified new password in DB: " . substr($updatedUser['password'], 0, 30) . "...");

    // Test if password_verify works
    $testVerify = password_verify($tempPassword, $updatedUser['password']);
    error_log("Password verify test: " . ($testVerify ? 'PASS' : 'FAIL'));

    // Return success with temp password (DEVELOPMENT ONLY!)
    $response['success'] = true;
    $response['temp_password'] = $tempPassword;
    $response['message'] = 'Temporary password generated! Copy: ' . $tempPassword;

    error_log("=== FORGOT PASSWORD END ===");

} catch (Exception $e) {
    error_log('Forgot password error: ' . $e->getMessage());
    $response['message'] = 'An error occurred. Please try again later.';
}

echo json_encode($response);
exit;
