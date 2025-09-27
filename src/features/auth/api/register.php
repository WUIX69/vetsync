<?php

// Clean output buffer first
if (ob_get_level()) {
    ob_end_clean();
}
ob_start();

// Set error reporting
error_reporting(0);
ini_set('display_errors', 0);

// Simple path resolution
$appPath = __DIR__ . '/../../../core/app.php';

if (!file_exists($appPath)) {
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'System configuration error']);
    exit;
}

include $appPath;

// Clean any previous output
ob_clean();
header('Content-Type: application/json');

use VetSync\Models\Users;
use Exception;

// Initialize response array
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

try {
    $data = [
        'uuid' => uuid(),
        'firstname' => $_POST['firstname'] ?? '',
        'lastname' => $_POST['lastname'] ?? '',
        'email' => $_POST['email'] ?? '',
        'telephone' => $_POST['telephone'] ?? '',
        'password' => isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : '',
    ];

    // Validate required fields
    if (empty($data['firstname']) || empty($data['lastname']) || empty($data['email']) || empty($data['password'])) {
        throw new Exception('All required fields must be filled');
    }

    $user = Users::store($data);
    $response = array_merge($response, $user);

    // Send welcome email after successful registration
    if ($response['success'] && !empty($data['email'])) {
        try {
            $emailService = new \VetSync\Services\Email();
            $userName = $data['firstname'] . ' ' . $data['lastname'];

            $emailResult = $emailService->sendWelcomeEmail(
                $data['email'],
                $userName
            );

            if ($emailResult['success']) {
                $response['message'] .= ' Welcome email sent successfully.';
            }
        } catch (\Exception $e) {
            error_log("Welcome email error: " . $e->getMessage());
            // Don't fail registration if email fails
        }
    }

} catch (Exception $e) {
    error_log("Registration error: " . $e->getMessage());
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;