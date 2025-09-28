<?php

include '../../../core/app.php';
apiHeaders();

use VetSync\Models\Users;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response = [
        'success' => false,
        'message' => 'Invalid request method'
    ];
    echo json_encode($response);
    exit;
}

try {
    $user_uuid = userData()['uuid'] ?? null;

    if (!$user_uuid) {
        $response = [
            'success' => false,
            'message' => 'User not authenticated'
        ];
        echo json_encode($response);
        exit;
    }

    $currentPassword = trim($_POST['current_password'] ?? '');
    $newPassword = trim($_POST['new_password'] ?? '');
    $confirmPassword = trim($_POST['confirm_new_password'] ?? '');

    // Validate input
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $response = [
            'success' => false,
            'message' => 'All fields are required'
        ];
        echo json_encode($response);
        exit;
    }

    if ($newPassword !== $confirmPassword) {
        $response = [
            'success' => false,
            'message' => 'New passwords do not match'
        ];
        echo json_encode($response);
        exit;
    }

    if (strlen($newPassword) < 6) {
        $response = [
            'success' => false,
            'message' => 'New password must be at least 6 characters long'
        ];
        echo json_encode($response);
        exit;
    }

    // Get current password from database
    $userResponse = Users::single($user_uuid);
    if (!$userResponse['success'] || empty($userResponse['data'])) {
        $response = [
            'success' => false,
            'message' => 'User not found'
        ];
        echo json_encode($response);
        exit;
    }

    $UserOGPassword = $userResponse['data']['password'] ?? null;

    // Verify current password
    if (!password_verify($currentPassword, $UserOGPassword)) {
        $response = [
            'success' => false,
            'message' => 'Current password is incorrect'
        ];
        echo json_encode($response);
        exit;
    }

    // Hash new password and update
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $response = Users::updateWherePassword($hashedPassword, $user_uuid);

    // Add success logging
    if ($response['success']) {
        error_log("Password updated successfully for user: " . $user_uuid);
    }

} catch (Exception $e) {
    error_log("Change Password Error: " . $e->getMessage());
    $response = [
        'success' => false,
        'message' => 'An error occurred while updating your password. Please try again.'
    ];
}

echo json_encode($response);
exit;
?>